<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\User;
use App\Models\Role;
use App\Models\Stock;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $mainCategories = MainCategory::with('subCategories.subSubCategories')->get();
        $subCategories = SubCategory::with('subSubCategories')->get();
        $subSubCategories = SubSubCategory::all();
        $products = Product::with('images')->where('stock', '>', 0)->get();
        $wishlistCount = $this->getWishlistCount();
        $cartCount = $this->getCartCount();
        return view('user.dashboard', compact('mainCategories', 'subCategories', 'subSubCategories', 'products', 'wishlistCount', 'cartCount'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('product_name', 'LIKE', "%{$query}%")
                          ->orWhere('description', 'LIKE', "%{$query}%")
                          ->where('stock', '>', 0)
                          ->get();

        if ($request->ajax()) {
            $html = view('user.search-results', compact('products'))->render();
            return response()->json(['html' => $html]);
        }

        $mainCategories = MainCategory::with('subCategories.subSubCategories')->get();
        $subCategories = SubCategory::with('subSubCategories')->get();
        $subSubCategories = SubSubCategory::all();
        $wishlistCount = $this->getWishlistCount();
        $cartCount = $this->getCartCount();

        return view('user.dashboard', compact('mainCategories', 'subCategories', 'subSubCategories', 'products', 'wishlistCount', 'cartCount'));
    }

    public function filterProducts(Request $request)
    {
        $query = Product::query();

        if ($request->has('main_category') && !empty($request->main_category)) {
            $query->whereIn('main_category_id', $request->main_category);
        }

        if ($request->has('sub_category') && !empty($request->sub_category)) {
            $query->whereIn('sub_category_id', $request->sub_category);
        }

        if ($request->has('sub_sub_category') && !empty($request->sub_sub_category)) {
            $query->whereIn('sub_sub_category_id', $request->sub_sub_category);
        }

        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        $products = $query->where('stock', '>', 0)->get();
        $mainCategories = MainCategory::with('subCategories.subSubCategories')->get();
        $html = view('user.dashboard', compact('products', 'mainCategories'))->render();
        return response()->json(['html' => $html]);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->roles()->where('role_name', 'admin')->exists()) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('user.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('user.dashboard');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'nullable', // Make middle_name optional
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->middle_name = $request->middle_name; // Set middle_name if provided
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->avatar = 'default-avatar.png'; // Set default avatar
        $user->save();

        // Assign the 'user' role to the newly registered user
        $role = Role::where('role_name', 'user')->first();
        if ($role) {
            $user->roles()->attach($role->id);
        }

        return redirect()->route('login')->with('success', 'User registered successfully.');
    }

    public function showProfile()
    {
        $user = Auth::user();
        return view('user.profile-dashboard', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        $user = auth()->user();
        $user->last_name = $request->last_name;
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->email = $request->email;
        $user->save();

        return redirect()->route('user.profile-dashboard')->with('success', 'Profile updated successfully.');
    }

    public function showChangePasswordForm()
    {
        return view('user.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('user.profile')->with('success', 'Password updated successfully.');
    }

    public function showUserProfile()
    {
        $user = Auth::user();
        return view('user.profile-dashboard', compact('user'));
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            // Delete old avatar if it's not the default
            if ($user->avatar !== 'default-avatar.png') {
                Storage::delete('public/avatars/' . $user->avatar);
            }

            $avatarName = time() . '_' . $request->file('avatar')->getClientOriginalName();
            $request->file('avatar')->storeAs('public/avatars', $avatarName);
            $user->avatar = $avatarName;
            $user->save();
        }

        return redirect()->route('user.profile.dashboard')->with('success', 'Avatar updated successfully.');
    }

    public function addToWishlist(Request $request, $productId)
    {
        $user = Auth::user();
        $user->wishlist()->attach($productId);
        return redirect()->route('user.dashboard')->with('success', 'Product added to wishlist.');
    }

    public function addToCart(Request $request, $productId)
    {
        $user = Auth::user();
        $color = $request->input('color');
        $size = $request->input('size');
        $cartItem = $user->cart()->where('product_id', $productId)->where('color', $color)->where('size', $size)->first();

        if ($cartItem) {
            $cartItem->pivot->quantity += 1;
            $cartItem->pivot->save();
        } else {
            $user->cart()->attach($productId, ['quantity' => 1, 'color' => $color, 'size' => $size]);
        }

        // Remove from wishlist if added to cart
        $user->wishlist()->detach($productId);

        return redirect()->back()->with('success', 'Product added to cart.');
    }

    public function updateCartQuantity(Request $request)
    {
        $user = Auth::user();
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $color = $request->input('color');
        $size = $request->input('size');

        $cartItem = $user->cart()->where('product_id', $productId)
                                ->where('color', $color)
                                ->where('size', $size)
                                ->first();

        if ($cartItem) {
            $cartItem->pivot->quantity = $quantity;
            $cartItem->pivot->save();
            return response()->json(['success' => true]);
        } else {
            // Check if there is another item with the same product ID but different color or size
            $existingItem = $user->cart()->where('product_id', $productId)->first();
            if ($existingItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot update quantity. There is another item with the same product but different color or size.',
                    'current_quantity' => $existingItem->pivot->quantity
                ]);
            }
        }

        return response()->json(['success' => false]);
    }


    public function showWishlist()
    {
        $user = Auth::user();
        $wishlistItems = $user->wishlist;
        $wishlistCount = $this->getWishlistCount();
        $cartCount = $this->getCartCount();
        return view('user.wishlist', compact('wishlistItems', 'wishlistCount', 'cartCount'));
    }

    public function showCart()
    {
        $user = Auth::user();
        $cartItems = $user->cart;
        $wishlistCount = $this->getWishlistCount();
        $cartCount = $this->getCartCount();
        return view('user.cart', compact('cartItems', 'wishlistCount', 'cartCount'));
    }

    public function removeFromWishlist($productId)
    {
        $user = Auth::user();
        $user->wishlist()->detach($productId);
        return redirect()->route('user.wishlist.dashboard')->with('success', 'Product removed from wishlist.');
    }

    public function removeFromCart(Request $request, $productId)
    {
        $user = Auth::user();
        $color = $request->input('color');
        $size = $request->input('size');
        $cartItem = $user->cart()->where('product_id', $productId)->where('color', $color)->where('size', $size)->first();

        if ($cartItem) {
            if ($cartItem->pivot->quantity > 1) {
                $cartItem->pivot->quantity -= 1;
                $cartItem->pivot->save();
            } else {
                $user->cart()->detach($productId);
            }
        }

        return redirect()->route('user.cart.dashboard')->with('success', 'Product quantity updated in cart.');
    }


    private function getWishlistCount()
    {
        $user = Auth::user();
        return $user->wishlist()->count();
    }

    private function getCartCount()
    {
        $user = Auth::user();
        return $user->cart()->sum('quantity');
    }

    public function showCheckoutForm()
    {
        $user = Auth::user();
        $cartItems = $user->cart;

        // Calculate totals
        $subtotal = $cartItems->sum(function($item) {
            return $item->price * $item->pivot->quantity;
        });

        $promotions = $cartItems->sum(function($item) {
            return ($item->price - $item->getDiscountedPrice()) * $item->pivot->quantity;
        });

        $shipping = 0; // You can set this to a fixed value or calculate it dynamically
        $tax = 0; // You can set this to a fixed value or calculate it dynamically
        $total = $subtotal - $promotions + $shipping + $tax;

        return view('user.checkout', compact('cartItems', 'subtotal', 'promotions', 'shipping', 'tax', 'total'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_number' => 'required',
            'state' => 'required',
            'city' => 'required',
            'post_zip_code' => 'required',
            'address_line_1' => 'required',
            'payment_method' => 'required',
        ]);

        $user = Auth::user();
        $cartItems = $user->cart;

        // Create an order
        $order = new Order();
        $order->user_id = $user->id;
        $order->first_name = $request->first_name;
        $order->last_name = $request->last_name;
        $order->phone_number = $request->phone_number;
        $order->state = $request->state;
        $order->city = $request->city;
        $order->post_zip_code = $request->post_zip_code;
        $order->address_line_1 = $request->address_line_1;
        $order->address_line_2 = $request->address_line_2;
        $order->payment_method = $request->payment_method;
        $order->save();

        // Update the stock of each product variation in the cart
        foreach ($cartItems as $cartItem) {
            $variation = ProductVariation::where('product_id', $cartItem->id)
                                         ->where('color', $cartItem->pivot->color)
                                         ->where('size', $cartItem->pivot->size)
                                         ->first();

            if ($variation) {
                $variation->stock -= $cartItem->pivot->quantity;
                $variation->save();

                // Update the stock in the products table
                $product = Product::find($cartItem->id);
                $product->stock -= $cartItem->pivot->quantity;
                $product->save();
            }
        }

        // Clear the user's cart after checkout
        $user->cart()->detach();

        return redirect()->route('user.orderComplete', $order->id)->with('success', 'Your order has been placed successfully.');
    }

    public function showOrderComplete($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('user.orderConfirmation', compact('order'));
    }

    public function getSelectedCartItems(Request $request)
    {
        $items = $request->input('items');
        $user = Auth::user();
        $selectedItems = [];

        foreach ($items as $item) {
            $cartItem = $user->cart()->where('product_id', $item['item_id'])->where('color', $item['color'])->where('size', $item['size'])->first();
            if ($cartItem) {
                $selectedItems[] = $cartItem;
            }
        }

        $retailPrice = collect($selectedItems)->sum(function($item) {
            return $item->price * $item->pivot->quantity;
        });

        $estimatedPrice = collect($selectedItems)->sum(function($item) {
            return $item->getDiscountedPrice() * $item->pivot->quantity;
        });

        $promotions = $retailPrice - $estimatedPrice;
        $totalQuantity = collect($selectedItems)->sum('pivot.quantity');

        $selectedItemsImages = collect($selectedItems)->map(function($item) {
            return $item->images->first() ? asset('storage/public/images/' . $item->images->first()->image_path) : null;
        });

        return response()->json([
            'retail_price' => $retailPrice,
            'promotions' => $promotions,
            'estimated_price' => $estimatedPrice,
            'saved' => $promotions,
            'total_quantity' => $totalQuantity,
            'selected_items_images' => $selectedItemsImages
        ]);
    }
}

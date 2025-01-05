<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\User;
use App\Models\ProductImage;
use App\Models\Stock;
use App\Models\Role;
use App\Models\StockHistory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $products = $query ? Product::where('product_name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get() : Product::all();
        return view('admin.dashboard', compact('products'));
    }

    public function createProduct()
    {
        $mainCategories = MainCategory::all();
        $subCategories = SubCategory::all();
        $subSubCategories = SubSubCategory::all();
        return view('admin.create-product', compact('mainCategories', 'subCategories', 'subSubCategories'));
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'product_name' => 'required|min:3|max:255|unique:products',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0|max:100',
            'brand' => 'required|string|max:255',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'variations' => 'required|array',
            'variations.*.color' => 'required',
            'variations.*.size' => 'required',
            'variations.*.stock' => 'required',
        ]);

        $product = new Product();
        $product->product_name = $request->product_name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->discount = $request->discount;
        $product->brand = $request->brand;
        $product->main_category_id = $request->main_category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->sub_sub_category_id = $request->sub_sub_category_id;
        $product->save();

        foreach ($request->variations as $variation) {
            ProductVariation::create([
                'product_id' => $product->id,
                'color' => $variation['color'],
                'size' => $variation['size'],
                'stock' => $variation['stock'],
            ]);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/images', $imageName);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imageName,
                ]);
            }
        }

        return redirect()->route('admin.manage-products')->with('success', 'Product created successfully.');
    }

    public function manageProducts(Request $request)
    {
        $query = $request->input('query');
        $mainCategoryId = $request->input('main_category_id');
        $subCategoryId = $request->input('sub_category_id');
        $subSubCategoryId = $request->input('sub_sub_category_id');
        $productsQuery = Product::query();

        if ($query) {
            $productsQuery->where('product_name', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%");
        }

        if ($mainCategoryId) {
            $productsQuery->where('main_category_id', $mainCategoryId);
        }

        if ($subCategoryId) {
            $productsQuery->where('sub_category_id', $subCategoryId);
        }

        if ($subSubCategoryId) {
            $productsQuery->where('sub_sub_category_id', $subSubCategoryId);
        }

        // Paginate the results
        $products = $productsQuery->paginate(10);
        $mainCategories = MainCategory::all();
        $subCategories = SubCategory::all();
        $subSubCategories = SubSubCategory::all();
        return view('admin.manage-products', compact('products', 'mainCategories', 'subCategories', 'subSubCategories'));
    }

    public function searchProducts(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('product_name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->get();
        return view('admin.manage-products', compact('products'));
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $mainCategories = MainCategory::all();
        $subCategories = SubCategory::all();
        $subSubCategories = SubSubCategory::all();
        return view('admin.edit-product', compact('product', 'mainCategories', 'subCategories', 'subSubCategories'));
    }

    public function updateProduct(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|min:3|unique:products,product_name,' . $id,
            'price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100', // Make discount optional
            'brand' => 'required|string|max:255',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'same:description', // Ensure the description remains the same
            'variations' => 'required|array',
            'variations.*.color' => 'required',
            'variations.*.size' => 'required',
            'variations.*.stock' => 'required',
        ]);

        $product = Product::findOrFail($id);
        $product->product_name = $request->product_name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->discount = $request->discount;
        $product->brand = $request->brand;
        $product->main_category_id = $request->main_category_id;
        $product->sub_category_id = $request->sub_category_id;
        $product->sub_sub_category_id = $request->sub_sub_category_id;
        $product->save();

        // Update variations
        foreach ($request->variations as $variation) {
            $existingVariation = ProductVariation::where('product_id', $product->id)
                ->where('color', $variation['color'])
                ->where('size', $variation['size'])
                ->first();

            if ($existingVariation) {
                $existingVariation->update([
                    'stock' => $variation['stock'],
                ]);
            } else {
                ProductVariation::create([
                    'product_id' => $product->id,
                    'color' => $variation['color'],
                    'size' => $variation['size'],
                    'stock' => $variation['stock'],
                ]);
            }
        }

        if ($request->hasFile('images')) {
            foreach ($product->images as $image) {
                Storage::delete('public/images/' . $image->image_path);
                $image->delete();
            }
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->storeAs('public/images', $imageName);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imageName,
                ]);
            }
        }

        return redirect()->route('admin.manage-products')->with('success', 'Product updated successfully.');
    }

    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);
        foreach ($product->images as $image) {
            Storage::delete('public/images/' . $image->image_path);
            $image->delete();
        }
        $product->delete();
        return redirect()->route('admin.manage-products')->with('success', 'Product deleted successfully.');
    }

    public function manageMainCategories()
    {
        $mainCategories = MainCategory::all();
        return view('admin.manageMainCategories', compact('mainCategories'));
    }

    public function manageSubCategories()
    {
        $subCategories = SubCategory::all();
        $mainCategories = MainCategory::all();
        return view('admin.manageSubCategories', compact('subCategories', 'mainCategories'));
    }

    public function manageSubSubCategories()
    {
        $subSubCategories = SubSubCategory::all();
        $subCategories = SubCategory::all();
        return view('admin.manageSubSubCategories', compact('subSubCategories', 'subCategories'));
    }

    public function createMainCategory()
    {
        return view('admin.create-main-category');
    }

    public function createSubCategory()
    {
        $mainCategories = MainCategory::all();
        return view('admin.create-sub-category', compact('mainCategories'));
    }

    public function createSubSubCategory()
    {
        $subCategories = SubCategory::all();
        return view('admin.create-sub-sub-category', compact('subCategories'));
    }

    public function storeMainCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:main_categories,name',
            'description' => 'nullable|string',
        ]);
        MainCategory::create($request->all());
        return redirect()->route('admin.manage-main-categories')->with('success', 'Main Category created successfully.');
    }

    public function storeSubCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:sub_categories,name',
            'main_category_id' => 'required|exists:main_categories,id',
        ]);
        SubCategory::create($request->all());
        return redirect()->route('admin.manage-sub-categories')->with('success', 'Sub Category created successfully.');
    }

    public function storeSubSubCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:sub_sub_categories,name',
            'sub_category_id' => 'required|exists:sub_categories,id',
        ]);
        SubSubCategory::create($request->all());
        return redirect()->route('admin.manage-sub-sub-categories')->with('success', 'Sub-Sub Category created successfully.');
    }

    public function editMainCategory($id)
    {
        $mainCategory = MainCategory::findOrFail($id);
        return view('admin.edit-main-category', compact('mainCategory'));
    }

    public function updateMainCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:main_categories,name,' . $id,
            'description' => 'nullable|string',
        ]);
        $mainCategory = MainCategory::findOrFail($id);
        $mainCategory->update($request->all());
        return redirect()->route('admin.manage-main-categories')->with('success', 'Main Category updated successfully.');
    }

    public function deleteMainCategory($id)
    {
        $mainCategory = MainCategory::findOrFail($id);
        $mainCategory->delete();
        return redirect()->route('admin.manage-main-categories')->with('success', 'Main Category deleted successfully.');
    }

    public function editSubCategory($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $mainCategories = MainCategory::all();
        return view('admin.edit-sub-category', compact('subCategory', 'mainCategories'));
    }

    public function updateSubCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:sub_categories,name,' . $id,
            'main_category_id' => 'required|exists:main_categories,id',
        ]);
        $subCategory = SubCategory::findOrFail($id);
        $subCategory->update($request->all());
        return redirect()->route('admin.manage-sub-categories')->with('success', 'Sub Category updated successfully.');
    }

    public function deleteSubCategory($id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $subCategory->delete();
        return redirect()->route('admin.manage-sub-categories')->with('success', 'Sub Category deleted successfully.');
    }

    public function editSubSubCategory($id)
    {
        $subSubCategory = SubSubCategory::findOrFail($id);
        $subCategories = SubCategory::all();
        return view('admin.edit-sub-sub-category', compact('subSubCategory', 'subCategories'));
    }

    public function updateSubSubCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:sub_sub_categories,name,' . $id,
            'sub_category_id' => 'required|exists:sub_categories,id',
        ]);
        $subSubCategory = SubSubCategory::findOrFail($id);
        $subSubCategory->update($request->all());
        return redirect()->route('admin.manage-sub-sub-categories')->with('success', 'Sub-Sub Category updated successfully.');
    }

    public function deleteSubSubCategory($id)
    {
        $subSubCategory = SubSubCategory::findOrFail($id);
        $subSubCategory->delete();
        return redirect()->route('admin.manage-sub-sub-categories')->with('success', 'Sub-Sub Category deleted successfully.');
    }

    public function stockHistory()
    {
        $stockHistory = StockHistory::with('product')->orderBy('created_at', 'desc')->get();
        return view('admin.stock-history', compact('stockHistory'));
    }

    public function manageOrders()
    {
        $orders = Order::with('items.product')->get();
        return view('admin.manage-orders', compact('orders'));
    }

    public function pendingOrders()
    {
        $orders = Order::with('items.product')->where('status', 'pending')->get();
        return view('admin.pending-orders', compact('orders'));
    }

    public function orderHistory()
    {
        $orders = Order::with('items.product')->get();
        return view('admin.order-history', compact('orders'));
    }

    public function editOrder($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        return view('admin.edit-order', compact('order'));
    }

    public function updateOrder(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'post_zip_code' => 'required|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'payment_method' => 'required|string|max:255',
            'status' => 'required|string|max:255', // Add this line
        ]);

        $order = Order::findOrFail($id);
        $order->first_name = $request->first_name;
        $order->last_name = $request->last_name;
        $order->phone_number = $request->phone_number;
        $order->state = $request->state;
        $order->city = $request->city;
        $order->post_zip_code = $request->post_zip_code;
        $order->address_line_1 = $request->address_line_1;
        $order->address_line_2 = $request->address_line_2;
        $order->payment_method = $request->payment_method;
        $order->status = $request->status; // Add this line
        $order->save();

        return redirect()->route('admin.manage-orders')->with('success', 'Order updated successfully.');
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|max:255',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->route('admin.manage-orders')->with('success', 'Order status updated successfully.');
    }

    public function deleteOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('admin.manage-orders')->with('success', 'Order deleted successfully.');
    }

    public function users()
    {
        $users = User::all();
        $roles = Role::all(); // Fetch all roles
        return view('admin.users', compact('users', 'roles'));
    }

    public function createUser(Request $request)
    {
        return view('admin.create-user');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'nullable',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|exists:roles,role_name', // Update validation for role
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->middle_name = $request->middle_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if ($request->hasFile('avatar')) {
            $imageName = time() . '.' . $request->avatar->getClientOriginalExtension();
            $request->avatar->storeAs('public/avatars', $imageName);
            $user->avatar = $imageName;
        }

        $user->save();

        // Assign the role to the user
        $role = Role::where('role_name', $request->role)->first();
        if ($role) {
            $user->roles()->attach($role->id);
        }

        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'nullable',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|exists:roles,role_name', // Update validation for role
        ]);

        $user = User::findOrFail($id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->middle_name = $request->middle_name;
        $user->email = $request->email;
        $user->save();

        // Update the role of the user
        $role = Role::where('role_name', $request->role)->first();
        if ($role) {
            $user->roles()->sync([$role->id]);
        }

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully.');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function updateUserPassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);
        $user = Auth::user();
        if (Hash::check($request->current_password, $user->password)) {
            $user->password = Hash::make($request->new_password);
            $user->save();
            return redirect()->route('admin.profile')->with('success', 'Password updated successfully.');
        } else {
            return redirect()->route('admin.profile')->with('error', 'Current password is incorrect.');
        }
    }

    public function addStock()
    {
        $products = Product::all();
        return view('admin.add-stock', compact('products'));
    }

    public function storeStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $product = Product::findOrFail($request->product_id);
        $stock = Stock::where('product_id', $request->product_id)->first();
        if ($stock) {
            $oldStock = $stock->quantity;
            $stock->quantity += $request->quantity;
            $stock->save();
        } else {
            $oldStock = 0;
            Stock::create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }
        // Update the stock in the products table
        $product->stock += $request->quantity;
        $product->save();
        // Record stock history
        StockHistory::create([
            'product_id' => $request->product_id,
            'old_stock' => $oldStock,
            'current_stock' => $product->stock,
            'added_stock' => $request->quantity,
            'action' => 'added',
        ]);
        return redirect()->route('admin.manage-stock')->with('success', 'Stock added successfully.');
    }

    public function manageStock()
    {
        $stocks = Stock::with('product')->get();
        $products = Product::all(); // Add this line to pass the products to the view
        return view('admin.manage-stock', compact('stocks', 'products'));
    }

    public function editStock($id)
    {
        $stock = Stock::findOrFail($id);
        $products = Product::all();
        return view('admin.edit-stock', compact('stock', 'products'));
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $stock = Stock::findOrFail($id);
        $product = Product::findOrFail($stock->product_id);
        // Calculate the difference in stock quantity
        $quantityDifference = $request->quantity - $stock->quantity;
        // Update the stock in the stocks table
        $stock->product_id = $request->product_id;
        $stock->quantity = $request->quantity;
        $stock->save();
        // Update the stock in the products table
        $product->stock += $quantityDifference;
        $product->save();
        // Record stock history
        StockHistory::create([
            'product_id' => $request->product_id,
            'old_stock' => $stock->quantity - $quantityDifference,
            'current_stock' => $product->stock,
            'added_stock' => $quantityDifference,
            'action' => $quantityDifference > 0 ? 'added' : 'removed',
        ]);
        return redirect()->route('admin.manage-stock')->with('success', 'Stock updated successfully.');
    }

    public function deleteStock($id)
    {
        $stock = Stock::findOrFail($id);
        $stock->delete();
        return redirect()->route('admin.manage-stock')->with('success', 'Stock deleted successfully.');
    }

    // New methods for Admin Profile Management
    public function showProfile()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
        ]);
        $user = Auth::user();
        $user->first_name = $request->first_name;
        $user->email = $request->email;
        $user->save();
        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully.');
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $user = Auth::user();
        if ($user->avatar) {
            Storage::delete('public/avatars/' . $user->avatar);
        }
        $imageName = time() . '.' . $request->avatar->getClientOriginalExtension();
        $request->avatar->storeAs('public/avatars', $imageName);
        $user->avatar = $imageName;
        $user->save();
        return redirect()->route('admin.profile')->with('success', 'Profile picture updated successfully.');
    }

    public function showSubCategories($mainCategoryId)
    {
        $subCategories = SubCategory::where('main_category_id', $mainCategoryId)->get();
        return response()->json($subCategories);
    }

    public function showSubSubCategories($subCategoryId)
    {
        $subSubCategories = SubSubCategory::where('sub_category_id', $subCategoryId)->get();
        return response()->json($subSubCategories);
    }

    // New methods for managing variations
    public function manageVariations()
    {
        $variations = ProductVariation::with('product')->get();
        return view('admin.manage-variations', compact('variations'));
    }

    public function editVariation($id)
    {
        $variation = ProductVariation::findOrFail($id);
        return view('admin.edit-variation', compact('variation'));
    }

    public function updateVariation(Request $request, $id)
    {
        $request->validate([
            'color' => 'required',
            'size' => 'required',
            'stock' => 'required|integer|min:0',
        ]);

        $variation = ProductVariation::findOrFail($id);
        $variation->color = $request->color;
        $variation->size = $request->size;
        $variation->stock = $request->stock;
        $variation->save();

        return redirect()->route('admin.manage-variations')->with('success', 'Variation updated successfully.');
    }

    public function deleteVariation($id)
    {
        $variation = ProductVariation::findOrFail($id);
        $variation->delete();
        return redirect()->route('admin.manage-variations')->with('success', 'Variation deleted successfully.');
    }
}

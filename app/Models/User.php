<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'middle_name',
        'name',
        'email',
        'password',
        'avatar', // Add avatar field
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->avatar)) {
                $user->avatar = 'storage/avatars/default-avatar.png';
            }
        });
    }

    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function wishlist()
    {
        return $this->belongsToMany(Product::class, 'user_wishlist')->withPivot('size')->withTimestamps();
    }

    public function cart()
    {
        return $this->belongsToMany(Product::class, 'user_cart')->withPivot('quantity', 'size', 'color')->withTimestamps();
    }

    public function addToWishlist($productId, $size)
    {
        $this->wishlist()->attach($productId, ['size' => $size]);
    }

    public function addToCart($productId, $quantity = 1, $size, $color)
    {
        $cartItem = $this->cart()->where('product_id', $productId)->where('size', $size)->where('color', $color)->first();
        if ($cartItem) {
            $cartItem->pivot->quantity += $quantity;
            $cartItem->pivot->save();
        } else {
            $this->cart()->attach($productId, ['quantity' => $quantity, 'size' => $size, 'color' => $color]);
        }
    }
}

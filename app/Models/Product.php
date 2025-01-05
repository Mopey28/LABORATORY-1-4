<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'description',
        'price',
        'stock',
        'discount',
        'brand',
        'main_category_id',
        'sub_category_id',
        'sub_sub_category_id',
        'supplier_id',
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function mainCategory()
    {
        return $this->belongsTo(MainCategory::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function subSubCategory()
    {
        return $this->belongsTo(SubSubCategory::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'user_wishlist')->withPivot('size')->withTimestamps();
    }

    public function cartedBy()
    {
        return $this->belongsToMany(User::class, 'user_cart')->withPivot('quantity', 'size')->withTimestamps();
    }

    public function getDiscountedPrice()
    {
        return $this->price - ($this->price * ($this->discount / 100));
    }

    public function cart()
    {
        return $this->belongsToMany(User::class, 'user_cart')->withPivot('quantity', 'size')->withTimestamps();
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }
}


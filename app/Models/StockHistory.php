<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockHistory extends Model
{
    use HasFactory;

    protected $table = 'stock_history'; // Ensure the table name is correct
    protected $fillable = ['product_id', 'old_stock', 'current_stock', 'added_stock', 'action'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

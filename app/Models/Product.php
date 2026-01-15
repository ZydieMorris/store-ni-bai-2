<?php

namespace App\Models;
use App\Models\CartItem;
use App\Models\orderItem;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
    'product_name',
    'price',
    'product_category_id',
    'image',
    'stock_available',
];

public function getImageAttribute($value)
{
    return $value ? asset($value) : null;
}

 public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

}

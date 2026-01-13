<?php

namespace App\Models;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'product_id',
        'product_quantity',
        'unit_price',
        'user_id',
    ];

  public function product() {
    return $this->belongsTo(Product::class, 'product_id');
}

}

<?php

namespace App\Models;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_name',
    ];

    /**
     * Define the relationship: a category has many products
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'product_category_id');
    }
}

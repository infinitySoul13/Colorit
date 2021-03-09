<?php

namespace App;

use ElForastero\Transliterate\Map;
use ElForastero\Transliterate\Transliterator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'title',
        'price',
        'quantity',
        'description',
        'category',
        'src',
        'rating',
        'size',
        'color'
    ];

    protected $casts = [
        'src' => 'array',
        'size' => 'array',
        'color' => 'array',
    ];

    public static function getLatestProducts()
    {
        $categories = Product::all()->unique('category');
        $products = collect();

        foreach ($categories as $category) {
            $cat_products = Product::where("quantity",">","0")->where('category', $category->category)->take(30)->get();
            foreach ($cat_products as $cat) {
                $products->push($cat);
            }
        }

        return $products;
    }

}

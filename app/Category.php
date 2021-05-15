<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'title'
    ];

    protected $casts = [
        'id' => 'integer',
    ];

    protected $appends = [
        'products_count'
    ];

    public function getProductsCountAttribute()
    {
        return Product::where('category', $this->title)->count();
    }

}

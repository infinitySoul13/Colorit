<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'title', 'words', 'img'
    ];

    protected $casts = [
        'id' => 'integer',
        'words' => 'array',
    ];

    protected $appends = [
        'products_count'
    ];

    public function getProductsCountAttribute()
    {
        return Product::where('category', $this->title)->count();
    }

}

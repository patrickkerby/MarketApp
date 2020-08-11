<?php

namespace App;

use App\Categories;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $guarded = [];

    public function path()
    {
        return route('products.show', $this);
    }

    public function categories()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function product_quantities()
    {
        return $this->hasMany(product_quantities::class, 'product_id');
    }

}

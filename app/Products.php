<?php

namespace App;

use App\categories;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Products extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];

    public function path()
    {
        return route('products.show', $this);
    }

    public function categories()
    {
        return $this->belongsTo(categories::class, 'category_id');
    }

    public function product_quantities()
    {
        return $this->hasMany(product_quantities::class, 'product_id');
    }

}

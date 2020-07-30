<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class market_days extends Model
{
    public function market()
    {
        
        return $this->belongsTo('App\Markets', 'market_id');
    }

    public function product_quantities()
    {
        
        return $this->hasMany('App\Product_Quantities');
    }

    public function products()
    {
        return $this->hasManyThrough('App\Products', 'App\Product_Quantities');
    }
}

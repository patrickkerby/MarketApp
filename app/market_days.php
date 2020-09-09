<?php

namespace App;
use App\Product_Quantities;

use Illuminate\Database\Eloquent\Model;

class market_days extends Model
{

    protected $guarded = [];

    public function market()
    {        
        return $this->belongsTo('App\Markets', 'market_id');
    }

    public function product_quantities()
    {
        return $this->hasMany('App\Product_Quantities', 'market_day_id');

    }

    public function products()
    {
        return $this->hasManyThrough('App\Products', 'App\Product_Quantities', 'product_id', 'id');
    }

    public function path()
    {
        return route('market_days.show', $this);
    }
}

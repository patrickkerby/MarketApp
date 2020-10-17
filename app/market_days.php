<?php

namespace App;
use App\product_quantities;

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
        return $this->hasMany('App\product_quantities', 'market_day_id');

    }

    public function products()
    {
        return $this->hasManyThrough('App\Products', 'App\product_quantities', 'product_id', 'id');
    }

    public function path()
    {
        return route('market_days.show', $this);
    }
}

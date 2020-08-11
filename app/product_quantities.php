<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product_quantities extends Model
{
    protected $guarded = [];

    public function market_days()
    {        
        return $this->belongsTo('App\market_days', 'market_day_id');
    }

    public function products()
    {        
        return $this->belongsTo('App\Products', 'product_id');
    }

}


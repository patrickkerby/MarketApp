<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Markets extends Model
{
    use SoftDeletes;
    
    protected $guarded = [];

    public function path()
    {
        return route('markets.show', $this);
    }
    
    public function marketDays()
    {
        return $this->hasMany(market_days::class, 'market_id');
    }
}


<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Markets extends Model
{
    protected $guarded = [];

    public function path()
    {
        return route('markets.show', $this);
    }
}


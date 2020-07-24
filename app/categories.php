<?php

namespace App;

use App\Products;
use Illuminate\Database\Eloquent\Model;

class categories extends Model
{
    public function products()
    {
        return $this->hasMany(Products::class);
    }
}

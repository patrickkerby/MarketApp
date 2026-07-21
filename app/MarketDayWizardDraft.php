<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketDayWizardDraft extends Model
{
    protected $fillable = [
        'user_id',
        'markets',
        'products',
        'product_quantities',
    ];

    protected $casts = [
        'markets' => 'array',
        'products' => 'array',
        'product_quantities' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

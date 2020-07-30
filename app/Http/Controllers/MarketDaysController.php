<?php

namespace App\Http\Controllers;

use App\market_days;
use App\Markets;
use App\Products;
use Illuminate\Http\Request;

class MarketDaysController extends Controller
{
    public function index(Market_Days $market_day)
    {
        // $market_days = market_days::with('market')->get()->sortBy('state');

        $market_days = market_days::all()->groupBy('state');
        // $students = Student::all()->groupBy('grad_year');
        
        return view('market_days.index', compact('market_days'));
    }

    public function show(Market_Days $market_day)
    {
        $markets = Markets::find($market_day->market_id);

        return view('market_days.show', [
            'market_day' => $market_day,
            'markets' => $markets
            ]);
    }

    public function create()
    {
        $markets = Markets::latest()->get();
        $markets = $markets->sortBy('$markets');
        $markets->values()->all();

        $products = Products::latest()->get();
        $products = $products->sortBy('$products');
        $products->values()->all();

        return view('market_days.create', [
            'markets' => $markets,
            'products' => $products
        ]);
    }

    public function setup()
    {
        $markets = Markets::latest()->get();
        $markets = $markets->sortBy('$markets');
        $markets->values()->all();

        $products = Products::latest()->get();
        $products = $products->sortBy('$products');
        $products->values()->all();

        return view('market_days.setup', [
            'markets' => $markets,
            'products' => $products
        ]);
    }

    // public function store()
    // {
    //     market_days::create($this->validateMarketDay());

    //     return request()->post();
    // }

    // protected function validateMarketDay()
    // {
    //     return request()->validate([
    //         'market_id' => 'required'
    //     ]);
    // }

    // states are saved to the database as numbers, use these when referencing in objects
    const state = [
        'draft', // 0
        'ready_to_pack', // 1
        'packed', // 2
        'returned', // 3
        'completed' // 4
    ];
}

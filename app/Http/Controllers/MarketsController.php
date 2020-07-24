<?php

namespace App\Http\Controllers;

use App\Markets;
use Illuminate\Http\Request;

class MarketsController extends Controller
{
    public function index()
    {
        $markets = Markets::latest()->get()->sortBy('sort_order');
        return view('markets.index', ['markets' => $markets]);
    }

    public function show(Markets $market)
    {
        return view('markets.show', ['market' => $market]);
    }

    public function create()
    {
        return view('markets.create');
    }

    public function store()
    {
        Markets::create($this->validateMarket());

        return redirect('/markets');
    }

    public function edit(Markets $market)
    {
        return view('markets.edit', compact('market'));
    }

    public function update(Markets $market)
    {

        $market->update($this->validateMarket());

        return redirect('/markets');
    }

    protected function validateMarket()
    {
        return request()->validate([
            'name' => 'required',
            'sort_order' => 'required'
        ]);
    }
}

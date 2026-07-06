<?php

namespace App\Http\Controllers;

use App\Markets;
use Illuminate\Http\Request;

class MarketsController extends Controller
{
    public function index()
    {
        $markets = Markets::latest()->get()->sortBy('sort_order');
        $archivedMarkets = Markets::onlyTrashed()->latest()->get()->sortBy('sort_order');
        return view('markets.index', ['markets' => $markets, 'archivedMarkets' => $archivedMarkets]);
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

    public function destroy(Markets $market)
    {
        $market->delete(); // Soft delete
        return redirect('/markets')->with('success', 'Market archived successfully');
    }
    
    public function restore($id)
    {
        $market = Markets::onlyTrashed()->findOrFail($id);
        $market->restore();
        return redirect('/markets')->with('success', 'Market restored successfully');
    }

    protected function validateMarket()
    {
        return request()->validate([
            'name' => 'required',
            'sort_order' => 'required',
            'city' => 'nullable',
            'postal_code' => 'nullable',
            'province' => 'nullable',
            'street_address' => 'nullable',
            'typical_employees' => 'nullable|integer|min:0',
            'typical_hours' => 'nullable|numeric|min:0',
            'avg_wage' => 'nullable|numeric|min:0',
            'annual_stall_fee' => 'nullable|numeric|min:0',
            'annual_other_fees' => 'nullable|numeric|min:0'
        ]);
    }
}

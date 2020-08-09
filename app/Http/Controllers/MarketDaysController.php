<?php

namespace App\Http\Controllers;

use App\market_days;
use App\Markets;
use App\product_quantities;
use App\Products;
use Illuminate\Http\Request;

class MarketDaysController extends Controller
{
    public function index(Market_Days $market_day)
    {
        $market_days = market_days::all()->groupBy('state');        
        return view('market_days.index', compact('market_days'));
    }

    public function show(Market_Days $market_day)
    {
        $markets = Markets::find($market_day->market_id);

        $product_quantity_items = $market_day->product_quantities()->get();    

        return view('market_days.show', [
            'market_day' => $market_day,
            'markets' => $markets,
            'product_quantities' => $product_quantity_items
        ]);
    }

    /**
     * Show the step 1 Form for creating a new Market Day.
     *
     * @return \Illuminate\Http\Response
     */
    public function createStep1(Request $request)
    {

        $markets = Markets::latest()->get();
        $markets = $markets->sortBy('$markets');
        $markets->values()->all();

        $products = Products::latest()->get();
        $products = $products->sortBy('$products');
        $products->values()->all();

        $categorized_products = Products::with('categories')->get()->groupBy('categories.name');        
        $keys = $categorized_products->keys();

        $markets_session = $request->session()->get('markets');
        $products_session = $request->session()->get('products');

        $data = $request->session()->all();

        // $request->session()->flush();


        return view('market_days.create-setup', [
            'markets' => $markets,
            'products' => $products,
            'categorized_products' => $categorized_products,
            'keys' => $keys,
            'markets_session' => $markets_session,
            'products_session' => $products_session,
            'data' => $data
        ]);
    }


    // Post Request to store step1 info in session, then redirect to the product quantities step     
    public function postCreateStep1(Request $request)
    {

        $request->session()->forget('markets');
        $request->session()->forget('products');

        $markets = $request->market;
        $products = $request->product;

        $request->session()->put('markets', $markets);
        $request->session()->put('products', $products);

        return redirect('/market_days/create');
    }


    // Show the step 2 Form for creating a new Market Day.
    public function createStep2(Request $request)
    {
        $data = $request->session()->all();
        $markets_session = $request->session()->get('markets');
        $products_session = $request->session()->get('products');
        $markets = $request->session()->get('markets');
        $product_quantities = $request->session()->get('product_quantities');
        $products = Products::find($products_session);

        if($products) {
            $products = $products->sortBy('name');
        }

        return view('market_days.create', [
            'markets_session' => $markets_session,
            'products_session' => $products_session,
            'markets' => $markets,
            'products' => $products,
            'product_quantities' => $product_quantities,
            'data' => $data
        ]);    
    }

    //Post Request to store step2 info in session as DRAFT, or post to db, or cancel
    public function store(Request $request)
    {
        $product_quantities = $request->product_quantities;
        $markets = $request->market;

        switch ($request->input('action')) {

            case 'save':

                $request->session()->put('markets', $markets);
                $request->session()->put('product_quantities', $product_quantities);
               
                return redirect('/market_days/create');
                
            break;

            case 'cancel':

                $request->session()->flush();
                return redirect('/market_days/create-setup');

            break;

            case 'publish':

                foreach($markets as $item) {
                    
                    if($item['name']) {
                        $market_day = new market_days();
            
                        $market_day->market_id = $item['market_id'];
                        $market_day->date = $item['date'];
                        $market_day->admin_notes = $item['admin_notes'];
                        $market_day->state = 1;
            
                        $market_day->save();
                    
                        foreach($product_quantities as $qty) {                
                            if($qty['market_id'] == $item['market_id']) {

                                $product_quantity = new product_quantities();
            
                                $product_quantity->product_id = $qty['product_id'];
                                $product_quantity->packed = $qty['packed'];
            
                                $product_quantity->market_days()->associate($market_day);
                    
                                $product_quantity->save();

                            }
        
                        }
                    
                    
                    }


                }

                
        
                $request->session()->flush();

                return redirect('/market_days');

            break;
    
        }
    }

    // states are saved to the database as numbers, use these when referencing in objects
    const state = [
        'draft', // 0
        'ready_to_pack', // 1
        'packed', // 2
        'returned', // 3
        'completed' // 4
    ];
}

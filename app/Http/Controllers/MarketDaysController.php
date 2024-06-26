<?php

namespace App\Http\Controllers;

use App\market_days;
use App\Markets;
use App\product_quantities;
use App\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
// use GNAHotelSolutions\Weather\Weather;
use PhpParser\Node\Expr\Isset_;
use Yajra\Datatables\Datatables;


class MarketDaysController extends Controller
{
    public function index(Market_Days $market_day)
    {
        $market_days = market_days::all()->sortBy('state');
        $market_days = $market_days->groupBy('state');
        
        return view('market_days.index', compact('market_days'));
    }

    public function completedindex(Markets $markets)
    {
        $markets = Markets::all()->sortBy('name');
        return view('market_days.completed-index', compact('markets'));
    }

    public function getdata(Request $request)
    {
        $completed_markets = market_days::select('id', 'market_id', 'date', 'actual_revenue')->where('state', 4);
        
        if ($request->ajax()) {
            return DataTables::of($completed_markets) 
                ->addColumn('name', function(Market_Days $market_day) {
                    return $market_day->market->name;
                })  
                ->addColumn('action', function($completed_markets) {
                    $market_day_id = $completed_markets->id;
                    $market_day_url = "<a href=\"/market_days/".$market_day_id."/edit\">Details</a>";
                    return $market_day_url;
                })
                ->editColumn('date', function ($completed_markets) {
                    $date = $completed_markets->date;
                    $formatted_date = \Carbon\Carbon::parse($date)->format('F j, Y');
                    
                    return $formatted_date; // human readable format
                })
                ->editColumn('actual_revenue', function ($request) {
                    
                    $actual_revenue = $request->actual_revenue;
                    $actual_revenue_formatted = "$".$actual_revenue;

                    return $actual_revenue_formatted; // human readable format
                })
                ->filter(function ($instance) use ($request) {
                    
                    $year = $request->get('year');
                    $month = $request->get('month');
                    $market = $request->get('market');
                    
                    if ( $market && $month) {
                        $instance
                        ->where('date', 'like', ''.$year .'%')
                        ->where('date', 'like', '%'.$month .'%')
                        ->where('market_id', 'like', $market);
                    }
                    elseif ($market && !$month) {
                        $instance
                        ->where('date', 'like', ''.$year .'%')
                        ->where('market_id', 'like', $market.'%');
                    }
                    elseif ( $month ) {
                        $instance
                        ->where('date', 'like', ''.$year .'%')
                        ->where('date', 'like', '%'.$month .'%');
                    }  
                    else {
                        $instance
                        ->where('date', 'like', ''.$year .'%');
                    }
                })
                ->make(true);          
        }
        else {
            return DataTables::of($completed_markets)
            ->addColumn('name', function(Market_Days $market_day) {
                return $market_day->market->name;
            })
            ->make(true);
        }
        
        return view('market_days.completed-index');
    }

    public function show(Market_Days $market_day)
    {
        $markets = $market_day->market();

        $product_quantity_items = $market_day->product_quantities()->get();  
  
        $products = $market_day->products()->get();

        if($market_day->admin_notes || $market_day->packing_notes || $market_day->market_notes) {
            $has_notes = true;            
        }
        else {
            $has_notes = false; 
        }

        switch ($market_day->state) {

            case '0':
                $market_day->state = 'Draft';
                break;
            case '1':
                $market_day->state = 'Ready To Pack';
                break;
            case '2':
                $market_day->state = 'Packed';
                break;
            case '3':
                $market_day->state = 'Returned';
                break;
            case '4':
                $market_day->state = 'Completed';
                break;
            }
        
        return view('market_days.show', [
            'market_day' => $market_day,
            'markets' => $markets,
            'product_quantities' => $product_quantity_items,
            'products' => $products,
            'has_notes' => $has_notes
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

    public function edit(Market_Days $market_day, Request $request)
    {

        $markets = $market_day->market();

        $product_quantity_items = $market_day->product_quantities()->get();  
  
        $products = $market_day->products()->get();

        if($market_day->admin_notes || $market_day->packing_notes || $market_day->market_notes) {
            $has_notes = true;            
        }
        else {
            $has_notes = false; 
        }
        
        switch ($market_day->state) {

            case '0':
                $market_day->state = 'Draft';
                break;
            case '1':
                $market_day->state = 'Ready To Pack';
                break;
            case '2':
                $market_day->state = 'Packed';
                break;
            case '3':
                $market_day->state = 'Returned';
                break;
            case '4':
                $market_day->state = 'Completed';
                break;
            }

        return view('market_days.edit', [
            'market_day' => $market_day,
            'markets' => $markets,
            'product_quantities' => $product_quantity_items,
            'products' => $products,
            'has_notes' => $has_notes
        ]);

    }

    public function update(Market_Days $market_day, Request $request)
    {
        //get all the data to save from the edit request
        $products_returned = $request->returned;
        $products_packed = $request->packed;
        $product_quantity_items = $market_day->product_quantities()->get();  
        $state_change = $request->state;
        $employee = $request->employee;
        $actual_revenue = $request->actual_revenue;
        $admin_notes = $request->admin_notes;
        $packing_notes = $request->packing_notes;
        $market_notes = $request->market_notes;
        $date = $request->date;
        
        // $weather = new Weather();
        // $currentWeather = json_decode($weather->get('edmonton,ca'));

        if (isset($products_packed)) {
            foreach($products_packed as $key => $qty) {   

                $existing_product_quantity = $market_day->product_quantities()->find($key);  

                $existing_product_quantity->packed = $qty;
                $existing_product_quantity->save();            
            }
        }

        if (isset($products_returned)) {
            foreach($products_returned as $key => $qty) {   

                $existing_product_quantity = $market_day->product_quantities()->find($key);  
                
                $existing_product_quantity->returned = $qty;
                $existing_product_quantity->save();
            }
        }
        
        //Create estimated revenue 
        $market_day->estimated_revenue = 0;

        foreach($product_quantity_items as $item) {
            $market_day->estimated_revenue += $item->products->price * ($item->packed - $item->returned);                     
        }

        // //Let's add the weather
        // if (isset($currentWeather)) {
        //     $market_day->weather = $currentWeather->main->temp;
        //     $market_day->wind = $currentWeather->wind->speed;
        // }

        //Grab any notes
        if($admin_notes) {
            $market_day->admin_notes = $admin_notes;
        }
        if($packing_notes) {
            $market_day->packing_notes = $packing_notes;
        }
        if($market_notes) {
            $market_day->market_notes = $market_notes;
        }
        if($employee) {
            $market_day->employee = $employee;
        }
        if($date) {
            $market_day->date = $date;
        }
        if($actual_revenue) {
            $market_day->actual_revenue = $actual_revenue;
        }

        //Auto save the state change
        $market_day->state = $state_change;
        
        //update the market day with everything above
        $market_day->save();
        if($market_day->state == 4) {
            return redirect('market_days/'.$market_day->id);
        }
        else {
            return redirect()->back();
        }
        $market_day->update($this->validateMarket_Days());
    }

    protected function validateMarket_Days()
    {
        return request()->validate([
            'date' => 'required',
            'employee' =>'nullable',
            'state' => 'required',
            'admin_notes' => 'nullable',
            'packing_notes' => 'nullable',
            'market_notes' => 'nullable',
            'actual_revenue' => 'nullable'
        ]);
    }

    // states are saved to the database as numbers, use these when referencing in objects
    // const state = [
    //     'draft', // 0
    //     'ready_to_pack', // 1
    //     'packed', // 2
    //     'returned', // 3
    //     'completed' // 4
    // ];

    public function destroy(Market_Days $market_day)
    {

        $market_day->delete();

        return redirect('/market_days');
    }

}

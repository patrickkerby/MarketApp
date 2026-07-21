<?php

namespace App\Http\Controllers;

use App\market_days;
use App\MarketDayWizardDraft;
use App\Markets;
use App\product_quantities;
use App\Products;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
// use GNAHotelSolutions\Weather\Weather;
use PhpParser\Node\Expr\Isset_;
use Yajra\Datatables\Datatables;


class MarketDaysController extends Controller
{
    private $defaultAnalyticsYear;

    public function index()
    {
        $market_days = market_days::with('market')
            ->where('state', '!=', 4)
            ->orderByDesc('date')
            ->get()
            ->groupBy('state');

        $wizardDraft = $this->getWizardDraft();

        return view('market_days.index', compact('market_days', 'wizardDraft'));
    }

    public function completedindex(Markets $markets)
    {
        // Include archived markets for filtering historical data
        $markets = Markets::withTrashed()->get()->sortBy('name');
        
        // Get distinct years from completed market days
        $years = market_days::where('state', 4)
            ->selectRaw('DISTINCT YEAR(date) as year')
            ->orderByRaw('year DESC')
            ->pluck('year')
            ->filter(function($year) {
                return $year > 1000 && $year <= date('Y') + 1; // Filter out bad data
            });
        
        $defaultYear = $years->first() ?? now()->year;

        return view('market_days.completed-index', compact('markets', 'years', 'defaultYear'));
    }

    public function getdata(Request $request)
    {
        $completed_markets = market_days::query()
            ->select('market_days.id', 'market_days.date', 'market_days.actual_revenue', 'markets.name')
            ->join('markets', 'market_days.market_id', '=', 'markets.id')
            ->where('market_days.state', 4);

        return DataTables::of($completed_markets)
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('action', function ($row) {
                return '<a href="/market_days/' . $row->id . '/edit">Details</a>';
            })
            ->editColumn('date', function ($row) {
                return \Carbon\Carbon::parse($row->date)->format('F j, Y');
            })
            ->editColumn('actual_revenue', function ($row) {
                return '$' . $row->actual_revenue;
            })
            ->filter(function ($query) use ($request) {
                $year = $request->get('year');
                if (!$year) {
                    $year = market_days::where('state', 4)
                        ->selectRaw('MAX(YEAR(date)) as year')
                        ->value('year');
                }

                if ($year) {
                    $query->where('market_days.date', '>=', $year . '-01-01')
                        ->where('market_days.date', '<=', $year . '-12-31');
                }

                if ($month = $request->get('month')) {
                    $query->whereMonth('market_days.date', (int) trim($month, '-'));
                }

                if ($market = $request->get('market')) {
                    $query->where('market_days.market_id', $market);
                }
            })
            ->rawColumns(['action'])
            ->make(true);
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
        $this->hydrateWizardSession($request);

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
        $wizardDraft = $this->getWizardDraft();

        $data = $request->session()->all();

        return view('market_days.create-setup', [
            'markets' => $markets,
            'products' => $products,
            'categorized_products' => $categorized_products,
            'keys' => $keys,
            'markets_session' => $markets_session,
            'products_session' => $products_session,
            'wizardDraft' => $wizardDraft,
            'data' => $data
        ]);
    }


    // Post Request to store step1 info in session, then redirect to the product quantities step     
    public function postCreateStep1(Request $request)
    {
        $markets = $this->normalizeMarketsSession($request->market ?? []);
        $products = $request->product ?? [];
        $productQuantities = $this->getWizardDraft()?->product_quantities
            ?? $request->session()->get('product_quantities', []);

        $request->session()->put('markets', $markets);
        $request->session()->put('products', $products);
        $request->session()->put('product_quantities', $productQuantities);

        $this->persistWizardDraft($markets, $products, $productQuantities);

        return redirect('/market_days/create');
    }

    public function discardWizardDraft(Request $request)
    {
        $this->clearWizardDraft();
        $request->session()->flush();

        return redirect('/market_days/create-setup')->with('success', 'Saved setup discarded.');
    }


    // Show the step 2 Form for creating a new Market Day.
    public function createStep2(Request $request)
    {
        $this->hydrateWizardSession($request);

        $data = $request->session()->all();
        $markets_session = $request->session()->get('markets');
        $products_session = $request->session()->get('products');
        $markets = $this->normalizeMarketsSession($request->session()->get('markets', []));
        if ($markets) {
            $request->session()->put('markets', $markets);
        }
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
            case 'save_and_edit':
                $this->saveWizardDraftToSessionAndDatabase($request, $markets ?? [], $product_quantities);

                if ($request->input('action') === 'save_and_edit') {
                    return redirect('/market_days/create-setup')->with('success', 'Draft saved.');
                }

                return redirect('/market_days/create')->with('success', 'Draft saved');

            case 'cancel':
                $this->clearWizardDraft();
                $request->session()->flush();
                return redirect('/market_days/create-setup');

            case 'publish':

                foreach ($markets as $item) {
                    if (empty($item['name'])) {
                        continue;
                    }

                    $date = $this->normalizeMarketDayDate($item['date'] ?? null);
                    if (!$date) {
                        return redirect()->back()->withInput()->withErrors([
                            'market.date' => 'Please enter a valid date for ' . $item['name'] . '.',
                        ]);
                    }

                    $market_day = new market_days();
            
                    $market_day->market_id = $item['market_id'];
                    $market_day->date = $date;
                    $market_day->admin_notes = $item['admin_notes'];
                    $market_day->state = 1;
            
                    $market_day->save();
                    
                    foreach ($product_quantities as $qty) {                
                        if ($qty['market_id'] == $item['market_id']) {

                            $product_quantity = new product_quantities();
            
                            $product_quantity->product_id = $qty['product_id'];
                            $product_quantity->packed = $qty['packed'];
            
                            $product_quantity->market_days()->associate($market_day);
                    
                            $product_quantity->save();

                        }
                    }
                }                
        
                $this->clearWizardDraft();
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
            $market_day->date = $this->normalizeMarketDayDate($date) ?? $date;
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
    }

    protected function validateMarket_Days()
    {
        return request()->validate([
            'date' => 'required|date_format:Y-m-d',
            'employee' =>'nullable',
            'state' => 'required',
            'admin_notes' => 'nullable',
            'packing_notes' => 'nullable',
            'market_notes' => 'nullable',
            'actual_revenue' => 'nullable'
        ]);
    }

    private function normalizeMarketDayDate($date): ?string
    {
        if ($date === null || $date === '') {
            return null;
        }

        $date = trim((string) $date);

        // Fix corrupted dates like 202607-02-03 (extra month digits after year)
        if (preg_match('/^(\d{4})\d{2}-(\d{2})-(\d{2})$/', $date, $matches)) {
            $date = $matches[1] . '-' . $matches[2] . '-' . $matches[3];
        }

        try {
            return Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function normalizeMarketsSession(array $markets): array
    {
        foreach ($markets as $key => $market) {
            if (!empty($market['date'])) {
                $normalized = $this->normalizeMarketDayDate($market['date']);
                if ($normalized) {
                    $markets[$key]['date'] = $normalized;
                }
            }
        }

        return $markets;
    }

    private function getWizardDraft(): ?MarketDayWizardDraft
    {
        if (!auth()->check()) {
            return null;
        }

        return MarketDayWizardDraft::where('user_id', auth()->id())->first();
    }

    private function hydrateWizardSession(Request $request): void
    {
        if ($request->session()->has('markets') && $request->session()->has('products')) {
            return;
        }

        $draft = $this->getWizardDraft();
        if (!$draft) {
            return;
        }

        $request->session()->put('markets', $this->normalizeMarketsSession($draft->markets ?? []));
        $request->session()->put('products', $draft->products ?? []);
        $request->session()->put('product_quantities', $draft->product_quantities ?? []);
    }

    private function persistWizardDraft(array $markets, array $products, ?array $productQuantities = null): void
    {
        if (!auth()->check()) {
            return;
        }

        $draft = $this->getWizardDraft();

        MarketDayWizardDraft::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'markets' => $this->normalizeMarketsSession($markets),
                'products' => array_values($products),
                'product_quantities' => $productQuantities
                    ?? ($draft ? ($draft->product_quantities ?? []) : []),
            ]
        );
    }

    private function saveWizardDraftToSessionAndDatabase(Request $request, array $markets, ?array $productQuantities): void
    {
        $normalizedMarkets = $this->normalizeMarketsSession($markets);
        $products = $request->session()->get('products', []);

        $request->session()->put('markets', $normalizedMarkets);
        if ($productQuantities !== null) {
            $request->session()->put('product_quantities', $productQuantities);
        }

        $this->persistWizardDraft(
            $normalizedMarkets,
            $products,
            $productQuantities ?? $request->session()->get('product_quantities', [])
        );
    }

    private function clearWizardDraft(): void
    {
        if (!auth()->check()) {
            return;
        }

        MarketDayWizardDraft::where('user_id', auth()->id())->delete();
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

    public function getOverviewAnalytics(Request $request)
    {
        $marketGroups = $this->marketGroupsForRequest($request);

        return response()->json([
            'summary' => $this->buildAnalyticsSummary($request, $marketGroups),
            'markets' => $this->buildMarketPerformance($request, $marketGroups),
            'products' => $this->buildProductAnalytics($request),
        ]);
    }

    public function getAnalyticsSummary(Request $request)
    {
        return response()->json($this->buildAnalyticsSummary($request));
    }

    public function getMarketPerformance(Request $request)
    {
        return response()->json($this->buildMarketPerformance($request));
    }

    public function getProductAnalytics(Request $request)
    {
        return response()->json($this->buildProductAnalytics($request));
    }

    /**
     * Get monthly trend data for line chart
     */
    public function getMonthlyTrends(Request $request)
    {
        $year = $request->get('year');
        $marketId = $request->get('market');
        
        // Get available years for multi-year comparison
        $availableYears = market_days::where('state', 4)
            ->selectRaw('DISTINCT YEAR(date) as year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
        
        $monthlyData = [];
        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 
            5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug',
            9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec'
        ];
        
        // If a year is selected, show that year's monthly data
        // If no year, show the last 2 years for comparison
        $yearsToShow = $year ? [$year] : array_slice($availableYears, 0, 2);
        
        foreach ($yearsToShow as $y) {
            $yearData = [];
            
            for ($month = 1; $month <= 12; $month++) {
                $query = market_days::where('state', 4)
                    ->whereYear('date', $y)
                    ->whereMonth('date', $month);
                
                if ($marketId) {
                    $query->where('market_id', $marketId);
                }
                
                $revenue = $query->sum('actual_revenue') ?: 0;
                $count = $query->count();
                
                $yearData[] = [
                    'month' => $month,
                    'month_name' => $monthNames[$month],
                    'revenue' => round($revenue, 2),
                    'market_days' => $count,
                    'avg_per_day' => $count > 0 ? round($revenue / $count, 2) : 0
                ];
            }
            
            $monthlyData[$y] = $yearData;
        }
        
        return response()->json([
            'monthly_data' => $monthlyData,
            'available_years' => $availableYears
        ]);
    }

    /**
     * Get year-over-year comparison data
     */
    public function getYearOverYearData(Request $request)
    {
        $marketId = $request->get('market');
        
        // Get all completed years
        $years = market_days::where('state', 4)
            ->selectRaw('DISTINCT YEAR(date) as year')
            ->orderBy('year', 'asc')
            ->pluck('year')
            ->toArray();
        
        $yearlyData = [];
        
        foreach ($years as $year) {
            $query = market_days::where('state', 4)
                ->whereYear('date', $year);
            
            if ($marketId) {
                $query->where('market_id', $marketId);
            }
            
            $marketDays = $query->get();
            
            $totalRevenue = $marketDays->sum('actual_revenue') ?: 0;
            $count = $marketDays->count();
            
            $yearlyData[] = [
                'year' => $year,
                'total_revenue' => round($totalRevenue, 2),
                'market_days' => $count,
                'avg_per_day' => $count > 0 ? round($totalRevenue / $count, 2) : 0
            ];
        }
        
        // Calculate year-over-year changes
        for ($i = 1; $i < count($yearlyData); $i++) {
            $prevRevenue = $yearlyData[$i - 1]['total_revenue'];
            $currentRevenue = $yearlyData[$i]['total_revenue'];
            
            if ($prevRevenue > 0) {
                $yearlyData[$i]['yoy_change'] = round(
                    (($currentRevenue - $prevRevenue) / $prevRevenue) * 100,
                    1
                );
            } else {
                $yearlyData[$i]['yoy_change'] = $currentRevenue > 0 ? 100 : 0;
            }
        }
        
        return response()->json([
            'yearly_data' => $yearlyData
        ]);
    }

    /**
     * Get profit margin and profitability analysis by market
     */
    public function getProfitabilityAnalysis(Request $request)
    {
        $year = $request->get('year') ?: now()->year;
        
        // Get all markets with completed market days
        $markets = \App\Markets::withTrashed()->get();
        
        $profitabilityData = [];
        
        foreach ($markets as $market) {
            $marketDays = market_days::where('state', 4)
                ->where('market_id', $market->id)
                ->whereYear('date', $year)
                ->get();
            
            if ($marketDays->count() === 0) continue;
            
            $totalRevenue = $marketDays->sum('actual_revenue') ?: 0;
            $daysCount = $marketDays->count();
            
            // Calculate expenses
            $laborCostPerDay = 0;
            if ($market->typical_employees && $market->typical_hours && $market->avg_wage) {
                $laborCostPerDay = $market->typical_employees * $market->typical_hours * $market->avg_wage;
            }
            
            $stallCostPerDay = $market->annual_stall_fee ? ($market->annual_stall_fee / max($daysCount, 1)) : 0;
            $otherCostPerDay = $market->annual_other_fees ? ($market->annual_other_fees / max($daysCount, 1)) : 0;
            
            $totalLaborCost = $laborCostPerDay * $daysCount;
            $totalStallCost = $market->annual_stall_fee ?: 0;
            $totalOtherCost = $market->annual_other_fees ?: 0;
            $totalExpenses = $totalLaborCost + $totalStallCost + $totalOtherCost;
            
            $netProfit = $totalRevenue - $totalExpenses;
            $profitMargin = $totalRevenue > 0 ? ($netProfit / $totalRevenue) * 100 : 0;
            
            $hasOperationalData = ($market->typical_employees || $market->annual_stall_fee || $market->annual_other_fees);
            
            $profitabilityData[] = [
                'market_id' => $market->id,
                'market_name' => $market->name,
                'is_archived' => $market->deleted_at !== null,
                'has_operational_data' => $hasOperationalData,
                'total_revenue' => round($totalRevenue, 2),
                'market_days' => $daysCount,
                'avg_per_day' => round($totalRevenue / $daysCount, 2),
                'labor_cost' => round($totalLaborCost, 2),
                'stall_cost' => round($totalStallCost, 2),
                'other_cost' => round($totalOtherCost, 2),
                'total_expenses' => round($totalExpenses, 2),
                'net_profit' => round($netProfit, 2),
                'profit_margin' => round($profitMargin, 1),
                'revenue_per_labor_dollar' => $totalLaborCost > 0 ? round($totalRevenue / $totalLaborCost, 2) : null
            ];
        }
        
        // Sort by profit margin (descending)
        usort($profitabilityData, function($a, $b) {
            return $b['profit_margin'] <=> $a['profit_margin'];
        });
        
        // Summary stats
        $marketsWithData = array_filter($profitabilityData, fn($m) => $m['has_operational_data']);
        $marketsWithoutData = array_filter($profitabilityData, fn($m) => !$m['has_operational_data']);
        
        return response()->json([
            'markets' => $profitabilityData,
            'summary' => [
                'total_markets' => count($profitabilityData),
                'markets_with_cost_data' => count($marketsWithData),
                'markets_missing_cost_data' => count($marketsWithoutData),
                'total_revenue' => round(array_sum(array_column($profitabilityData, 'total_revenue')), 2),
                'total_expenses' => round(array_sum(array_column($profitabilityData, 'total_expenses')), 2),
                'total_net_profit' => round(array_sum(array_column($profitabilityData, 'net_profit')), 2),
            ]
        ]);
    }

    private function analyticsYearForRequest(Request $request)
    {
        if ($year = $request->get('year')) {
            return (int) $year;
        }

        if ($this->defaultAnalyticsYear === null) {
            $this->defaultAnalyticsYear = (int) (market_days::where('state', 4)
                ->selectRaw('MAX(YEAR(date)) as year')
                ->value('year') ?? now()->year);
        }

        return $this->defaultAnalyticsYear;
    }

    private function applyDateFilters($query, Request $request)
    {
        $year = $request->get('year');
        if ($year) {
            $query->where('date', '>=', $year . '-01-01')
                ->where('date', '<=', $year . '-12-31');
        } else {
            $defaultYear = $this->analyticsYearForRequest($request);
            $query->where('date', '>=', $defaultYear . '-01-01')
                ->where('date', '<=', $defaultYear . '-12-31');
        }

        if ($month = $request->get('month')) {
            $query->whereMonth('date', (int) trim($month, '-'));
        }

        if ($marketId = $request->get('market')) {
            $query->where('market_id', $marketId);
        }

        return $query;
    }

    private function completedMarketDaysQuery(Request $request)
    {
        return $this->applyDateFilters(market_days::query()->where('state', 4), $request);
    }

    private function marketDayCountsForExpenseYear($expenseYear)
    {
        return market_days::where('state', 4)
            ->whereYear('date', $expenseYear)
            ->groupBy('market_id')
            ->selectRaw('market_id, COUNT(*) as day_count')
            ->pluck('day_count', 'market_id');
    }

    private function calculateExpensesForMarketGroups($marketGroups, $expenseYear)
    {
        if ($marketGroups->isEmpty()) {
            return 0;
        }

        $marketDayCountsByMarket = $this->marketDayCountsForExpenseYear($expenseYear);
        $markets = Markets::withTrashed()
            ->whereIn('id', $marketGroups->pluck('market_id'))
            ->get()
            ->keyBy('id');

        $totalExpenses = 0;

        foreach ($marketGroups as $group) {
            $market = $markets->get($group->market_id);
            if (!$market) {
                continue;
            }

            $laborCostPerDay = 0;
            if ($market->typical_employees && $market->typical_hours && $market->avg_wage) {
                $laborCostPerDay = $market->typical_employees * $market->typical_hours * $market->avg_wage;
            }

            $totalMarketDaysThisYear = $marketDayCountsByMarket->get($group->market_id, 0);
            $stallCostPerDay = 0;
            $otherCostPerDay = 0;

            if ($totalMarketDaysThisYear > 0) {
                if ($market->annual_stall_fee) {
                    $stallCostPerDay = $market->annual_stall_fee / $totalMarketDaysThisYear;
                }
                if ($market->annual_other_fees) {
                    $otherCostPerDay = $market->annual_other_fees / $totalMarketDaysThisYear;
                }
            }

            $totalExpenses += ($laborCostPerDay + $stallCostPerDay + $otherCostPerDay) * $group->day_count;
        }

        return $totalExpenses;
    }

    private function marketGroupsForRequest(Request $request)
    {
        return $this->completedMarketDaysQuery($request)
            ->selectRaw('market_id, COUNT(*) as day_count, SUM(actual_revenue) as total_revenue, SUM(estimated_revenue) as estimated_revenue')
            ->groupBy('market_id')
            ->get();
    }

    private function buildAnalyticsSummary(Request $request, $marketGroups = null)
    {
        $year = $request->get('year') ?: $this->analyticsYearForRequest($request);
        $month = $request->get('month');
        $monthNum = $month ? (int) trim($month, '-') : null;
        $marketId = $request->get('market');

        $stats = $this->completedMarketDaysQuery($request)
            ->selectRaw('COUNT(*) as market_count, COALESCE(SUM(actual_revenue), 0) as total_revenue, COALESCE(AVG(actual_revenue), 0) as avg_revenue, COALESCE(SUM(estimated_revenue), 0) as estimated_revenue')
            ->first();

        $marketGroups = $marketGroups ?? $this->marketGroupsForRequest($request);
        $totalRevenue = (float) $stats->total_revenue;
        $totalExpenses = $this->calculateExpensesForMarketGroups($marketGroups, $year);

        $summary = [
            'total_revenue' => $totalRevenue,
            'avg_revenue' => round($stats->avg_revenue, 2),
            'market_count' => (int) $stats->market_count,
            'estimated_revenue' => (float) $stats->estimated_revenue,
            'total_expenses' => round($totalExpenses, 2),
            'net_sales' => round($totalRevenue - $totalExpenses, 2),
            'expense_percent' => $totalRevenue > 0 ? round(($totalExpenses / $totalRevenue) * 100, 2) : 0,
        ];

        if ($summary['estimated_revenue'] > 0) {
            $summary['variance_amount'] = $summary['total_revenue'] - $summary['estimated_revenue'];
            $summary['variance_percent'] = round(
                ($summary['variance_amount'] / $summary['estimated_revenue']) * 100,
                2
            );
        } else {
            $summary['variance_amount'] = 0;
            $summary['variance_percent'] = 0;
        }

        if ($year) {
            $prevQuery = market_days::where('state', 4)->whereYear('date', $year - 1);

            if ($monthNum) {
                $prevQuery->whereMonth('date', $monthNum);
            }
            if ($marketId) {
                $prevQuery->where('market_id', $marketId);
            }

            $prevStats = (clone $prevQuery)
                ->selectRaw('COUNT(*) as market_count, COALESCE(SUM(actual_revenue), 0) as total_revenue, COALESCE(AVG(actual_revenue), 0) as avg_revenue')
                ->first();

            $prevRevenue = (float) $prevStats->total_revenue;
            $prevAvg = (float) $prevStats->avg_revenue;

            $summary['yoy_revenue_change'] = $prevRevenue > 0
                ? round((($summary['total_revenue'] - $prevRevenue) / $prevRevenue) * 100, 1)
                : 0;
            $summary['yoy_avg_change'] = $prevAvg > 0
                ? round((($summary['avg_revenue'] - $prevAvg) / $prevAvg) * 100, 1)
                : 0;
            $summary['yoy_count_change'] = $summary['market_count'] - (int) $prevStats->market_count;
        }

        return $summary;
    }

    private function buildMarketPerformance(Request $request, $marketGroups = null)
    {
        $year = $request->get('year') ?: $this->analyticsYearForRequest($request);
        $marketGroups = $marketGroups ?? $this->marketGroupsForRequest($request);
        $marketDayCountsByMarket = $this->marketDayCountsForExpenseYear($year);
        $markets = Markets::withTrashed()
            ->whereIn('id', $marketGroups->pluck('market_id'))
            ->get()
            ->keyBy('id');

        $marketPerformance = $marketGroups->map(function ($group) use ($markets, $marketDayCountsByMarket) {
            $market = $markets->get($group->market_id);
            $totalRevenue = (float) $group->total_revenue;
            $marketCount = (int) $group->day_count;
            $totalExpenses = 0;

            if ($market) {
                $laborCostPerDay = 0;
                if ($market->typical_employees && $market->typical_hours && $market->avg_wage) {
                    $laborCostPerDay = $market->typical_employees * $market->typical_hours * $market->avg_wage;
                }

                $totalMarketDaysThisYear = $marketDayCountsByMarket->get($group->market_id, 0);
                $stallCostPerDay = 0;
                $otherCostPerDay = 0;

                if ($totalMarketDaysThisYear > 0) {
                    if ($market->annual_stall_fee) {
                        $stallCostPerDay = $market->annual_stall_fee / $totalMarketDaysThisYear;
                    }
                    if ($market->annual_other_fees) {
                        $otherCostPerDay = $market->annual_other_fees / $totalMarketDaysThisYear;
                    }
                }

                $totalExpenses = ($laborCostPerDay + $stallCostPerDay + $otherCostPerDay) * $marketCount;
            }

            return [
                'market_name' => $market ? $market->name : 'Unknown',
                'total_revenue' => $totalRevenue,
                'market_count' => $marketCount,
                'avg_revenue' => $marketCount > 0 ? round($totalRevenue / $marketCount, 2) : 0,
                'total_expenses' => round($totalExpenses, 2),
                'net_sales' => round($totalRevenue - $totalExpenses, 2),
                'estimated_revenue' => (float) $group->estimated_revenue,
                'variance' => round($totalRevenue - (float) $group->estimated_revenue, 2),
            ];
        })->sortByDesc('total_revenue')->values();

        $grandTotal = $marketPerformance->sum('total_revenue');
        $marketPerformance = $marketPerformance->map(function ($market) use ($grandTotal) {
            $market['percent_of_total'] = $grandTotal > 0 ? round(($market['total_revenue'] / $grandTotal) * 100, 2) : 0;
            return $market;
        });

        return [
            'markets' => $marketPerformance,
            'grand_total' => $grandTotal,
        ];
    }

    private function buildProductAnalytics(Request $request)
    {
        $year = $request->get('year') ?: $this->analyticsYearForRequest($request);
        $month = $request->get('month');
        $marketId = $request->get('market');

        $productData = \DB::table('product_quantities')
            ->join('products', 'product_quantities.product_id', '=', 'products.id')
            ->join('market_days', 'product_quantities.market_day_id', '=', 'market_days.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('market_days.state', 4)
            ->where('market_days.date', '>=', $year . '-01-01')
            ->where('market_days.date', '<=', $year . '-12-31')
            ->when($month, function ($query) use ($month) {
                $query->whereMonth('market_days.date', (int) trim($month, '-'));
            })
            ->when($marketId, function ($query) use ($marketId) {
                $query->where('market_days.market_id', $marketId);
            })
            ->whereNull('products.deleted_at')
            ->select(
                'products.id',
                'products.name',
                'products.price',
                'categories.name as category_name',
                \DB::raw('SUM(product_quantities.packed - COALESCE(product_quantities.returned, 0)) as total_quantity'),
                \DB::raw('SUM((product_quantities.packed - COALESCE(product_quantities.returned, 0)) * products.price) as total_revenue'),
                \DB::raw('COUNT(DISTINCT product_quantities.market_day_id) as market_day_count'),
                \DB::raw('COUNT(DISTINCT market_days.market_id) as market_count')
            )
            ->groupBy('products.id', 'products.name', 'products.price', 'categories.name')
            ->havingRaw('SUM(product_quantities.packed - COALESCE(product_quantities.returned, 0)) > 0')
            ->orderByDesc('total_revenue')
            ->get();

        $products = $productData->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category_name,
                'unit_price' => round($product->price, 2),
                'total_quantity' => round($product->total_quantity, 2),
                'total_revenue' => round($product->total_revenue, 2),
                'avg_price' => $product->total_quantity > 0 ? round($product->total_revenue / $product->total_quantity, 2) : 0,
                'market_day_count' => $product->market_day_count,
                'market_count' => $product->market_count,
            ];
        });

        $grandTotal = $products->sum('total_revenue');

        $products = $products->map(function ($product) use ($grandTotal) {
            $product['percent_of_total'] = $grandTotal > 0 ? round(($product['total_revenue'] / $grandTotal) * 100, 2) : 0;
            return $product;
        });

        $categoriesData = $products->groupBy('category')->map(function ($categoryProducts, $categoryName) use ($grandTotal) {
            $categoryRevenue = $categoryProducts->sum('total_revenue');
            $categoryQuantity = $categoryProducts->sum('total_quantity');

            return [
                'category' => $categoryName,
                'total_quantity' => round($categoryQuantity, 2),
                'total_revenue' => round($categoryRevenue, 2),
                'product_count' => $categoryProducts->count(),
                'market_count' => $categoryProducts->max('market_count'),
                'market_day_count' => $categoryProducts->max('market_day_count'),
                'percent_of_total' => $grandTotal > 0 ? round(($categoryRevenue / $grandTotal) * 100, 2) : 0,
                'products' => $categoryProducts->values()->all(),
            ];
        })->sortByDesc('total_revenue')->values();

        return [
            'products' => $products,
            'categories' => $categoriesData,
            'grand_total' => $grandTotal,
            'total_products' => $products->count(),
        ];
    }

}

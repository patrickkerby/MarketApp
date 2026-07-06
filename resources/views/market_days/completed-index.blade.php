@extends('layout')

@section('content')

@section('class', 'show completed')

<div class="analytics-container">
    <!-- Top Filter Bar -->
    <div class="filter-bar">
        <div class="filter-bar-content">
            <!-- <div class="filter-bar-title">
                <i class="fas fa-filter"></i>
                Filters
            </div> -->
            
            <div class="filter-group">
                <label>
                    <i class="fas fa-calendar filter-icon"></i>
                    Year
                </label>
                <select id='year' class="form-control">
                    <option value="">All Years</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}" {{ $year == $defaultYear ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label>
                    <i class="fas fa-calendar-day filter-icon"></i>
                    Month
                </label>
                <select id='month' class="form-control">
                    <option value="">All Months</option>
                    <option value="-01-">January</option>
                    <option value="-02-">February</option>
                    <option value="-03-">March</option>
                    <option value="-04-">April</option>
                    <option value="-05-">May</option>
                    <option value="-06-">June</option>
                    <option value="-07-">July</option>
                    <option value="-08-">August</option>
                    <option value="-09-">September</option>
                    <option value="-10-">October</option>
                    <option value="-11-">November</option>
                    <option value="-12-">December</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>
                    <i class="fas fa-store filter-icon"></i>
                    Market
                </label>
                <select id='market' class="form-control">
                    <option value="">All Markets</option>
                    @foreach ($markets as $market)
                        <option value="{{ $market->id }}">{{ $market->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <main class="main-content">        
        <!-- Summary Cards (Always Visible) -->
        <div class="analytics-summary" id="analyticsSummary">
            <div class="summary-card">
                <div class="summary-card-header">
                    <h3>Total Revenue</h3>
                </div>
                <div class="value" id="totalRevenue">$0</div>
                <div class="comparison neutral" id="revenueComparison">Loading...</div>
            </div>
            
            <div class="summary-card">
                <div class="summary-card-header">
                    <h3>Net Sales</h3>
                </div>
                <div class="value" id="netSales">$0</div>
                <div class="comparison neutral" id="expensePercent">0% expenses</div>
            </div>
            
            <div class="summary-card">
                <div class="summary-card-header">
                    <h3>Avg Per Day</h3>
                </div>
                <div class="value" id="avgRevenue">$0</div>
                <div class="comparison neutral" id="avgComparison">Loading...</div>
            </div>
            
            <div class="summary-card">
                <div class="summary-card-header">
                    <h3>Market Days</h3>
                </div>
                <div class="value" id="marketCount">0</div>
                <div class="comparison neutral" id="countComparison">Loading...</div>
            </div>
            
            <div class="summary-card">
                <div class="summary-card-header">
                    <h3>Variance</h3>
                </div>
                <div class="value" id="variance">0%</div>
                <div class="comparison neutral">Est vs Actual</div>
            </div>
        </div>
        
        <!-- Tabs Navigation -->
        <div class="tabs-navigation">
            <button class="tab-button active" data-tab="overview">
                <i class="fas fa-chart-pie"></i> Overview
            </button>
            <button class="tab-button" data-tab="trends">
                <i class="fas fa-chart-line"></i> Trends
            </button>
            <button class="tab-button" data-tab="profitability">
                <i class="fas fa-dollar-sign"></i> Profitability
            </button>
            <button class="tab-button" data-tab="markets">
                <i class="fas fa-store"></i> Markets
            </button>
            <button class="tab-button" data-tab="products">
                <i class="fas fa-shopping-basket"></i> Products
            </button>
            <button class="tab-button" data-tab="details">
                <i class="fas fa-table"></i> All Market Days
            </button>
        </div>
        
        <!-- Tab Content: Overview -->
        <div class="tab-content active" id="tab-overview">
            <div class="chart-grid">
                <div class="chart-box">
                    <h4>Revenue by Market</h4>
                    <canvas id="marketRevenueChart" style="max-height: 350px;"></canvas>
                </div>
                
                <div class="chart-box">
                    <h4>Top 10 Categories</h4>
                    <canvas id="productRevenueChart" style="max-height: 350px;"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Tab Content: Trends -->
        <div class="tab-content" id="tab-trends">
            <div class="section">
                <div class="section-header">
                    <div>
                        <h3 class="section-title">Monthly Revenue Trends</h3>
                        <p class="section-subtitle">Track seasonal patterns and compare performance across months</p>
                    </div>
                </div>
                
                <div class="chart-box">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <h4>Monthly Revenue</h4>
                        <div class="trend-legend" id="trendLegend"></div>
                    </div>
                    <div class="chart-container">
                        <canvas id="monthlyTrendChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="section" style="margin-top: 30px;">
                <div class="section-header">
                    <div>
                        <h3 class="section-title">Year-over-Year Comparison</h3>
                        <p class="section-subtitle">Annual revenue growth and performance history</p>
                    </div>
                </div>
                
                <div class="chart-box">
                    <h4>Annual Revenue & Growth</h4>
                    <div class="chart-container">
                        <canvas id="yearOverYearChart"></canvas>
                    </div>
                </div>
                
                <div class="table-scroll" style="margin-top: 25px;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th style="text-align: right;">Total Revenue</th>
                                <th style="text-align: right;">Market Days</th>
                                <th style="text-align: right;">Avg/Day</th>
                                <th style="text-align: right;">YoY Change</th>
                            </tr>
                        </thead>
                        <tbody id="yearlyTableBody">
                            <tr><td colspan="5" style="text-align: center;">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Tab Content: Profitability -->
        <div class="tab-content" id="tab-profitability">
            <div class="section">
                <div class="section-header">
                    <div>
                        <h3 class="section-title">Market Profitability Analysis</h3>
                        <p class="section-subtitle">Compare profit margins and operational efficiency across markets</p>
                    </div>
                    <div id="profitabilityYearSelect">
                        <select id="profitYear" class="form-control" style="width: auto;">
                            @foreach($years as $year)
                                <option value="{{ $year }}" {{ $year == $defaultYear ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div id="missingDataAlert" class="alert alert-warning hidden" style="margin-bottom: 20px;">
                    <i class="fas fa-exclamation-triangle"></i>
                    <span id="missingDataText"></span>
                    <a href="/markets" style="margin-left: 10px;">Edit Markets →</a>
                </div>
                
                <div class="profitability-summary" id="profitabilitySummary">
                    <div class="summary-card small">
                        <h4>Total Revenue</h4>
                        <div class="value" id="profitTotalRevenue">$0</div>
                    </div>
                    <div class="summary-card small">
                        <h4>Total Expenses</h4>
                        <div class="value" id="profitTotalExpenses">$0</div>
                    </div>
                    <div class="summary-card small">
                        <h4>Net Profit</h4>
                        <div class="value" id="profitNetProfit">$0</div>
                    </div>
                </div>
                
                <div class="chart-box" style="margin-top: 25px;">
                    <h4>Profit Margin by Market</h4>
                    <div class="chart-container">
                        <canvas id="profitMarginChart"></canvas>
                    </div>
                </div>
                
                <div class="table-scroll" style="margin-top: 25px;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Market</th>
                                <th style="text-align: right;">Revenue</th>
                                <th style="text-align: right;">Labor</th>
                                <th style="text-align: right;">Stall Fees</th>
                                <th style="text-align: right;">Other</th>
                                <th style="text-align: right;">Total Expenses</th>
                                <th style="text-align: right;">Net Profit</th>
                                <th style="text-align: right;">Margin %</th>
                            </tr>
                        </thead>
                        <tbody id="profitabilityTableBody">
                            <tr><td colspan="8" style="text-align: center;">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Tab Content: Markets -->
        <div class="tab-content" id="tab-markets">
            <div class="section">
                <div class="section-header">
                    <div>
                        <h3 class="section-title">
                            Market Performance Analysis
                        </h3>
                        <p class="section-subtitle">Compare revenue and profitability across markets</p>
                    </div>
                </div>
                
                <div class="chart-container">
                    <canvas id="marketRevenueChartFull"></canvas>
                </div>
                
                <div class="table-scroll">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Market</th>
                                <th style="text-align: right;">Revenue</th>
                                <th style="text-align: right;">Net Sales</th>
                                <th style="text-align: right;">Expenses</th>
                                <th style="text-align: right;">Days</th>
                                <th style="text-align: right;">Avg/Day</th>
                                <th style="text-align: right;">% of Total</th>
                            </tr>
                        </thead>
                        <tbody id="marketTableBody">
                            <tr><td colspan="7" style="text-align: center;">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Tab Content: Products -->
        <div class="tab-content" id="tab-products">
            <div class="section">
                <div class="section-header">
                    <div>
                        <h3 class="section-title">
                            Product Performance Analysis
                        </h3>
                        <p class="section-subtitle">Analyze sales by product and category</p>
                    </div>
                    <div class="view-toggle">
                        <button class="view-toggle-btn active" id="categoryViewBtn">
                            <i class="fas fa-layer-group"></i>
                            By Category
                        </button>
                        <button class="view-toggle-btn" id="productViewBtn">
                            <i class="fas fa-list-ul"></i>
                            By Product
                        </button>
                    </div>
                </div>
                
                <div class="chart-container">
                    <canvas id="productRevenueChartFull"></canvas>
                </div>
                
                <!-- Category View (Default) -->
                <div class="table-scroll" id="categoryTableView">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th style="text-align: right;">Qty Sold</th>
                                <th style="text-align: right;">Revenue</th>
                                <th style="text-align: center;">Products</th>
                                <th style="text-align: center;">Markets</th>
                                <th style="text-align: center;">Days</th>
                                <th style="text-align: right;">% Total</th>
                            </tr>
                        </thead>
                        <tbody id="categoryTableBody">
                            <tr><td colspan="7" style="text-align: center;">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Product View -->
                <div class="table-scroll hidden" id="productTableView">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th style="text-align: right;">Price</th>
                                <th style="text-align: right;">Qty Sold</th>
                                <th style="text-align: right;">Revenue</th>
                                <th style="text-align: center;">Markets</th>
                                <th style="text-align: center;">Days</th>
                                <th style="text-align: right;">% Total</th>
                            </tr>
                        </thead>
                        <tbody id="allProductsTableBody">
                            <tr><td colspan="8" style="text-align: center;">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Tab Content: Details -->
        <div class="tab-content" id="tab-details">
            <div class="section">
                <div class="section-header">
                    <div>
                        <h3 class="section-title">
                            All Completed Market Days
                        </h3>
                        <p class="section-subtitle">Detailed list of all market day transactions</p>
                    </div>
                </div>
                
                <table class="table completed_markets data-table" id="completed_markets" data-page-length='100'>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Market</th>
                            <th>Revenue</th>
                            <th>Action</th>
                        </tr>
                    </thead>           
                </table>
            </div>
        </div>
        
        <a href="/market_days/" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Back to all Market Days
        </a>
    </main>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Tab Switching — lazy-load data when a tab is first opened
    const loadedTabs = { overview: false };

    function loadTabData(tabId) {
        if (loadedTabs[tabId]) return;
        loadedTabs[tabId] = true;

        switch (tabId) {
            case 'overview':
                loadOverview();
                break;
            case 'trends':
                loadMonthlyTrends();
                loadYearOverYear();
                break;
            case 'profitability':
                loadProfitabilityAnalysis();
                break;
            case 'markets':
                loadMarketPerformance();
                break;
            case 'products':
                loadProductPerformance();
                break;
            case 'details':
                initCompletedMarketsTable();
                break;
        }
    }

    document.querySelectorAll('.tab-button').forEach(button => {
        button.addEventListener('click', function() {
            document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            this.classList.add('active');
            const tabId = this.getAttribute('data-tab');
            document.getElementById('tab-' + tabId).classList.add('active');
            loadTabData(tabId);
        });
    });

    function getFilters() {
        return {
            year: $('#year').val() || '{{ $defaultYear }}',
            month: $('#month').val(),
            market: $('#market').val()
        };
    }

    function formatDollars(value) {
        var num = parseFloat(value);
        if (isNaN(num)) return '$0';
        return '$' + Math.round(num).toLocaleString('en-US');
    }

    function renderAnalyticsSummary(data) {
        $('#totalRevenue').text(formatDollars(data.total_revenue));
        $('#netSales').text(formatDollars(data.net_sales));
        $('#expensePercent').text((data.expense_percent ?? 0) + '% expenses');
        $('#avgRevenue').text(formatDollars(data.avg_revenue));
        $('#marketCount').text(data.market_count ?? 0);

        let varianceClass = (data.variance_percent ?? 0) >= 0 ? 'positive' : 'negative';
        $('#variance').text((data.variance_percent ?? 0) + '%').attr('class', 'value');

        if (data.yoy_revenue_change !== undefined) {
            let revenueClass = data.yoy_revenue_change >= 0 ? 'positive' : 'negative';
            let revenueIcon = data.yoy_revenue_change >= 0 ? '<i class="fas fa-arrow-up"></i>' : '<i class="fas fa-arrow-down"></i>';
            $('#revenueComparison').html(revenueIcon + ' ' + Math.abs(data.yoy_revenue_change) + '% vs prev year')
                .attr('class', 'comparison ' + revenueClass);

            let avgClass = data.yoy_avg_change >= 0 ? 'positive' : 'negative';
            let avgIcon = data.yoy_avg_change >= 0 ? '<i class="fas fa-arrow-up"></i>' : '<i class="fas fa-arrow-down"></i>';
            $('#avgComparison').html(avgIcon + ' ' + Math.abs(data.yoy_avg_change) + '% vs prev year')
                .attr('class', 'comparison ' + avgClass);

            let countClass = data.yoy_count_change >= 0 ? 'positive' : 'negative';
            let countIcon = data.yoy_count_change >= 0 ? '<i class="fas fa-arrow-up"></i>' : '<i class="fas fa-arrow-down"></i>';
            let countText = Math.abs(data.yoy_count_change) + ' vs prev year';
            if (data.yoy_count_change === 0) {
                countText = 'Same as prev year';
                countClass = 'neutral';
                countIcon = '';
            }
            $('#countComparison').html(data.yoy_count_change !== 0 ? countIcon + ' ' + countText : countText)
                .attr('class', 'comparison ' + countClass);
        } else {
            $('#revenueComparison').html('Select year for comparison').attr('class', 'comparison neutral');
            $('#avgComparison').html('Select year for comparison').attr('class', 'comparison neutral');
            $('#countComparison').html('Select year for comparison').attr('class', 'comparison neutral');
        }
    }

    function loadOverview() {
        $.ajax({
            url: "{{ route('market_days.analytics.overview') }}",
            dataType: 'json',
            data: getFilters(),
            success: function(data) {
                renderAnalyticsSummary(data.summary);
                renderMarketPerformanceData(data.markets);
                renderProductPerformanceData(data.products);
            },
            error: function(xhr) {
                console.error('Failed to load overview', xhr.responseText?.substring(0, 200));
                $('#totalRevenue, #netSales, #avgRevenue').text('—');
                $('#marketCount').text('—');
                $('#expensePercent, #revenueComparison, #avgComparison, #countComparison').text('Failed to load').attr('class', 'comparison neutral');
            }
        });
    }

    // Function to load analytics summary
    function loadAnalyticsSummary() {
        $.ajax({
            url: "{{ route('market_days.analytics.summary') }}",
            dataType: 'json',
            data: getFilters(),
            success: function(data) {
                renderAnalyticsSummary(data);
            },
            error: function(xhr) {
                console.error('Failed to load analytics summary', xhr.responseText?.substring(0, 200));
                $('#totalRevenue, #netSales, #avgRevenue').text('—');
                $('#marketCount').text('—');
                $('#expensePercent, #revenueComparison, #avgComparison, #countComparison').text('Failed to load').attr('class', 'comparison neutral');
            }
        });
    }

    // Global variables to store chart instances
    let marketChart = null;
    let marketChartFull = null;
    let productChart = null;
    let productChartFull = null;

    function renderMarketPerformanceData(data) {
        let tableHtml = '';
        data.markets.forEach(function(market) {
            tableHtml += '<tr>';
            tableHtml += '<td>' + market.market_name + '</td>';
            tableHtml += '<td style="text-align: right;">$' + Math.round(parseFloat(market.total_revenue)).toLocaleString('en-US') + '</td>';
            tableHtml += '<td style="text-align: right;">$' + Math.round(parseFloat(market.net_sales)).toLocaleString('en-US') + '</td>';
            tableHtml += '<td style="text-align: right;">$' + Math.round(parseFloat(market.total_expenses)).toLocaleString('en-US') + '</td>';
            tableHtml += '<td style="text-align: right;">' + market.market_count + '</td>';
            tableHtml += '<td style="text-align: right;">$' + Math.round(parseFloat(market.avg_revenue)).toLocaleString('en-US') + '</td>';
            tableHtml += '<td style="text-align: right;">' + market.percent_of_total + '%</td>';
            tableHtml += '</tr>';
        });
        $('#marketTableBody').html(tableHtml);

        const labels = data.markets.map(m => m.market_name);
        const revenues = data.markets.map(m => m.total_revenue);

        if (marketChart) {
            marketChart.destroy();
        }

        const ctx = document.getElementById('marketRevenueChart');
        if (ctx) {
            marketChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue',
                        data: revenues,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }

        if (marketChartFull) {
            marketChartFull.destroy();
        }

        const ctxFull = document.getElementById('marketRevenueChartFull');
        if (ctxFull) {
            marketChartFull = new Chart(ctxFull, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue',
                        data: revenues,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Function to load market performance
    function loadMarketPerformance() {
        $.ajax({
            url: "{{ route('market_days.analytics.market-performance') }}",
            dataType: 'json',
            data: getFilters(),
            success: function(data) {
                renderMarketPerformanceData(data);
            },
            error: function() {
                console.error('Failed to load market performance');
            }
        });
    }

    // Store current product data
    let currentProductData = null;
    let currentViewMode = 'category'; // or 'product'
    
    function renderProductPerformanceData(data) {
        currentProductData = data;

        if (currentViewMode === 'category') {
            populateCategoryView();
        } else {
            populateProductView();
        }

        const topCategories = data.categories.slice(0, 10);
        const labels = topCategories.map(c => c.category);
        const revenues = topCategories.map(c => c.total_revenue);

        if (productChart) {
            productChart.destroy();
        }

        const ctx = document.getElementById('productRevenueChart');
        if (ctx) {
            productChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue',
                        data: revenues,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 2,
                        borderRadius: 6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Function to load product performance
    function loadProductPerformance() {
        $.ajax({
            url: "{{ route('market_days.analytics.product-performance') }}",
            dataType: 'json',
            data: getFilters(),
            success: function(data) {
                renderProductPerformanceData(data);
            },
            error: function() {
                console.error('Failed to load product performance');
                $('#allProductsTableBody').html('<tr><td colspan="8" style="text-align: center; color: red;">Failed to load data</td></tr>');
                $('#categoryTableBody').html('<tr><td colspan="7" style="text-align: center; color: red;">Failed to load data</td></tr>');
            }
        });
    }
    
    // Populate category view with expandable rows
    function populateCategoryView() {
        if (!currentProductData) return;
        
        let categoryTableHtml = '';
        const categories = currentProductData.categories;
        
        categories.forEach(function(category, idx) {
            // Category row (expandable)
            categoryTableHtml += '<tr class="expandable-row" data-category-idx="' + idx + '">';
            categoryTableHtml += '<td>';
            categoryTableHtml += '<i class="fas fa-caret-right expand-icon"></i> ';
            categoryTableHtml += '<strong>' + category.category + '</strong>';
            categoryTableHtml += '</td>';
            categoryTableHtml += '<td style="text-align: right;">' + parseFloat(category.total_quantity).toFixed(2) + '</td>';
            categoryTableHtml += '<td style="text-align: right;">$' + Math.round(parseFloat(category.total_revenue)).toLocaleString('en-US') + '</td>';
            categoryTableHtml += '<td style="text-align: center;">' + category.product_count + '</td>';
            categoryTableHtml += '<td style="text-align: center;">' + category.market_count + '</td>';
            categoryTableHtml += '<td style="text-align: center;">' + category.market_day_count + '</td>';
            categoryTableHtml += '<td style="text-align: right;">' + category.percent_of_total + '%</td>';
            categoryTableHtml += '</tr>';
            
            // Nested product rows (hidden by default)
            category.products.forEach(function(product) {
                categoryTableHtml += '<tr class="nested-row hidden" data-parent-category="' + idx + '">';
                categoryTableHtml += '<td>' + product.name + '</td>';
                categoryTableHtml += '<td style="text-align: right;">' + parseFloat(product.total_quantity).toFixed(2) + '</td>';
                categoryTableHtml += '<td style="text-align: right;">$' + Math.round(parseFloat(product.total_revenue)).toLocaleString('en-US') + '</td>';
                categoryTableHtml += '<td style="text-align: center;">-</td>';
                categoryTableHtml += '<td style="text-align: center;">' + product.market_count + '</td>';
                categoryTableHtml += '<td style="text-align: center;">' + product.market_day_count + '</td>';
                categoryTableHtml += '<td style="text-align: right;">' + product.percent_of_total + '%</td>';
                categoryTableHtml += '</tr>';
            });
        });
        
        $('#categoryTableBody').html(categoryTableHtml || '<tr><td colspan="7" style="text-align: center;">No category data available</td></tr>');
        
        // Add click handlers for expandable rows
        $('.expandable-row').off('click').on('click', function() {
            const categoryIdx = $(this).data('category-idx');
            const icon = $(this).find('.expand-icon');
            const nestedRows = $('tr[data-parent-category="' + categoryIdx + '"]');
            
            icon.toggleClass('expanded');
            nestedRows.toggleClass('hidden');
        });
        
        // Update full chart with category data
        updateProductChart(categories.slice(0, 15), 'category');
    }
    
    // Populate product view
    function populateProductView() {
        if (!currentProductData) return;
        
        let allTableHtml = '';
        currentProductData.products.forEach(function(product) {
            allTableHtml += '<tr>';
            allTableHtml += '<td>' + product.name + '</td>';
            allTableHtml += '<td>' + product.category + '</td>';
            allTableHtml += '<td style="text-align: right;">$' + parseFloat(product.unit_price).toFixed(2) + '</td>';
            allTableHtml += '<td style="text-align: right;">' + parseFloat(product.total_quantity).toFixed(2) + '</td>';
            allTableHtml += '<td style="text-align: right;">$' + Math.round(parseFloat(product.total_revenue)).toLocaleString('en-US') + '</td>';
            allTableHtml += '<td style="text-align: center;">' + product.market_count + '</td>';
            allTableHtml += '<td style="text-align: center;">' + product.market_day_count + '</td>';
            allTableHtml += '<td style="text-align: right;">' + product.percent_of_total + '%</td>';
            allTableHtml += '</tr>';
        });
        $('#allProductsTableBody').html(allTableHtml || '<tr><td colspan="8" style="text-align: center;">No product data available</td></tr>');
        
        // Update full chart with product data
        updateProductChart(currentProductData.products.slice(0, 15), 'product');
    }
    
    // Update the full product chart
    function updateProductChart(data, type) {
        let labels, revenues;
        
        if (type === 'category') {
            labels = data.map(item => item.category);
            revenues = data.map(item => item.total_revenue);
        } else {
            labels = data.map(item => item.name);
            revenues = data.map(item => item.total_revenue);
        }
        
        if (productChartFull) {
            productChartFull.destroy();
        }
        
        const ctxFull = document.getElementById('productRevenueChartFull');
        productChartFull = new Chart(ctxFull, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Revenue',
                    data: revenues,
                    backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Toggle view mode
    function setViewMode(mode) {
        currentViewMode = mode;
        
        if (mode === 'category') {
            $('#categoryViewBtn').addClass('active');
            $('#productViewBtn').removeClass('active');
            $('#categoryTableView').removeClass('hidden');
            $('#productTableView').addClass('hidden');
            populateCategoryView();
        } else {
            $('#productViewBtn').addClass('active');
            $('#categoryViewBtn').removeClass('active');
            $('#productTableView').removeClass('hidden');
            $('#categoryTableView').addClass('hidden');
            populateProductView();
        }
    }

    // Chart instances for trends/profitability
    let monthlyTrendChart = null;
    let yearOverYearChart = null;
    let profitMarginChart = null;

    // Color palette for multi-year comparison
    const yearColors = [
        { bg: 'rgba(59, 130, 246, 0.2)', border: 'rgba(59, 130, 246, 1)' },
        { bg: 'rgba(16, 185, 129, 0.2)', border: 'rgba(16, 185, 129, 1)' },
        { bg: 'rgba(245, 158, 11, 0.2)', border: 'rgba(245, 158, 11, 1)' },
        { bg: 'rgba(236, 72, 153, 0.2)', border: 'rgba(236, 72, 153, 1)' },
        { bg: 'rgba(139, 92, 246, 0.2)', border: 'rgba(139, 92, 246, 1)' }
    ];

    // Load monthly trends data
    function loadMonthlyTrends() {
        $.ajax({
            url: "{{ route('market_days.analytics.monthly-trends') }}",
            data: {
                year: $('#year').val(),
                market: $('#market').val()
            },
            success: function(data) {
                renderMonthlyTrendChart(data.monthly_data);
            }
        });
    }

    // Render monthly trend line chart
    function renderMonthlyTrendChart(monthlyData) {
        const ctx = document.getElementById('monthlyTrendChart');
        if (!ctx) return;
        
        if (monthlyTrendChart) {
            monthlyTrendChart.destroy();
        }
        
        const monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const datasets = [];
        let colorIndex = 0;
        
        // Build legend HTML
        let legendHtml = '';
        
        for (const year in monthlyData) {
            const color = yearColors[colorIndex % yearColors.length];
            const yearData = monthlyData[year].map(m => m.revenue);
            
            datasets.push({
                label: year,
                data: yearData,
                borderColor: color.border,
                backgroundColor: color.bg,
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointHoverRadius: 6
            });
            
            legendHtml += `<span style="display: inline-flex; align-items: center; margin-left: 15px;">
                <span style="width: 12px; height: 12px; background: ${color.border}; border-radius: 2px; margin-right: 5px;"></span>
                ${year}
            </span>`;
            
            colorIndex++;
        }
        
        $('#trendLegend').html(legendHtml);
        
        monthlyTrendChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthLabels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': $' + Math.round(context.parsed.y).toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // Load year-over-year data
    function loadYearOverYear() {
        $.ajax({
            url: "{{ route('market_days.analytics.year-over-year') }}",
            data: {
                market: $('#market').val()
            },
            success: function(data) {
                renderYearOverYearChart(data.yearly_data);
                populateYearlyTable(data.yearly_data);
            }
        });
    }

    // Render year-over-year chart
    function renderYearOverYearChart(yearlyData) {
        const ctx = document.getElementById('yearOverYearChart');
        if (!ctx) return;
        
        if (yearOverYearChart) {
            yearOverYearChart.destroy();
        }
        
        const labels = yearlyData.map(d => d.year);
        const revenues = yearlyData.map(d => d.total_revenue);
        const yoyChanges = yearlyData.map(d => d.yoy_change || 0);
        
        yearOverYearChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Total Revenue',
                        data: revenues,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        borderRadius: 6,
                        yAxisID: 'y'
                    },
                    {
                        label: 'YoY Change %',
                        data: yoyChanges,
                        type: 'line',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        backgroundColor: 'rgba(16, 185, 129, 0.2)',
                        borderWidth: 3,
                        pointRadius: 6,
                        pointBackgroundColor: 'rgba(16, 185, 129, 1)',
                        yAxisID: 'y1',
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.dataset.yAxisID === 'y1') {
                                    return 'YoY Change: ' + context.parsed.y + '%';
                                }
                                return 'Revenue: $' + Math.round(context.parsed.y).toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        position: 'left',
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    }

    // Populate yearly comparison table
    function populateYearlyTable(yearlyData) {
        let html = '';
        
        yearlyData.forEach(function(row) {
            let changeClass = '';
            let changeIcon = '';
            
            if (row.yoy_change !== undefined) {
                changeClass = row.yoy_change >= 0 ? 'positive' : 'negative';
                changeIcon = row.yoy_change >= 0 ? '<i class="fas fa-arrow-up"></i>' : '<i class="fas fa-arrow-down"></i>';
            }
            
            html += `<tr>
                <td><strong>${row.year}</strong></td>
                <td style="text-align: right;">$${Math.round(row.total_revenue).toLocaleString()}</td>
                <td style="text-align: right;">${row.market_days}</td>
                <td style="text-align: right;">$${Math.round(row.avg_per_day).toLocaleString()}</td>
                <td style="text-align: right;" class="${changeClass}">
                    ${row.yoy_change !== undefined ? changeIcon + ' ' + row.yoy_change + '%' : '—'}
                </td>
            </tr>`;
        });
        
        $('#yearlyTableBody').html(html || '<tr><td colspan="5" style="text-align: center;">No data available</td></tr>');
    }

    // Load profitability analysis
    function loadProfitabilityAnalysis() {
        const year = $('#profitYear').val() || $('#year').val() || new Date().getFullYear();
        
        $.ajax({
            url: "{{ route('market_days.analytics.profitability') }}",
            data: {
                year: year
            },
            success: function(data) {
                renderProfitabilityData(data);
            }
        });
    }

    // Render profitability data
    function renderProfitabilityData(data) {
        // Update summary cards
        $('#profitTotalRevenue').text('$' + Math.round(data.summary.total_revenue).toLocaleString());
        $('#profitTotalExpenses').text('$' + Math.round(data.summary.total_expenses).toLocaleString());
        $('#profitNetProfit').text('$' + Math.round(data.summary.total_net_profit).toLocaleString());
        
        // Show warning if markets are missing cost data
        if (data.summary.markets_missing_cost_data > 0) {
            $('#missingDataText').text(
                data.summary.markets_missing_cost_data + ' of ' + data.summary.total_markets + 
                ' markets are missing operational cost data. Profit calculations may be incomplete.'
            );
            $('#missingDataAlert').removeClass('hidden');
        } else {
            $('#missingDataAlert').addClass('hidden');
        }
        
        // Render chart
        renderProfitMarginChart(data.markets);
        
        // Populate table
        populateProfitabilityTable(data.markets);
    }

    // Render profit margin chart
    function renderProfitMarginChart(markets) {
        const ctx = document.getElementById('profitMarginChart');
        if (!ctx) return;
        
        if (profitMarginChart) {
            profitMarginChart.destroy();
        }
        
        const labels = markets.map(m => m.market_name);
        const margins = markets.map(m => m.profit_margin);
        
        // Color based on margin value
        const backgroundColors = margins.map(m => {
            if (m >= 50) return 'rgba(16, 185, 129, 0.8)';
            if (m >= 25) return 'rgba(59, 130, 246, 0.8)';
            if (m >= 0) return 'rgba(245, 158, 11, 0.8)';
            return 'rgba(239, 68, 68, 0.8)';
        });
        
        profitMarginChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Profit Margin %',
                    data: margins,
                    backgroundColor: backgroundColors,
                    borderWidth: 0,
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Profit Margin: ' + context.parsed.x + '%';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    }

    // Populate profitability table
    function populateProfitabilityTable(markets) {
        let html = '';
        
        markets.forEach(function(market) {
            let marginClass = '';
            if (market.profit_margin >= 50) marginClass = 'positive';
            else if (market.profit_margin < 0) marginClass = 'negative';
            
            let nameDisplay = market.market_name;
            if (market.is_archived) nameDisplay += ' <span style="color: #9ca3af; font-size: 0.85em;">(archived)</span>';
            if (!market.has_operational_data) nameDisplay += ' <span style="color: #f59e0b; font-size: 0.85em;"><i class="fas fa-exclamation-circle"></i></span>';
            
            html += `<tr>
                <td>${nameDisplay}</td>
                <td style="text-align: right;">$${Math.round(market.total_revenue).toLocaleString()}</td>
                <td style="text-align: right;">$${Math.round(market.labor_cost).toLocaleString()}</td>
                <td style="text-align: right;">$${Math.round(market.stall_cost).toLocaleString()}</td>
                <td style="text-align: right;">$${Math.round(market.other_cost).toLocaleString()}</td>
                <td style="text-align: right;">$${Math.round(market.total_expenses).toLocaleString()}</td>
                <td style="text-align: right;" class="${market.net_profit >= 0 ? 'positive' : 'negative'}">
                    $${Math.round(market.net_profit).toLocaleString()}
                </td>
                <td style="text-align: right;" class="${marginClass}">
                    ${market.profit_margin}%
                </td>
            </tr>`;
        });
        
        $('#profitabilityTableBody').html(html || '<tr><td colspan="8" style="text-align: center;">No data available</td></tr>');
    }

    var completedMarketsTable = null;

    function initCompletedMarketsTable() {
        if (completedMarketsTable) {
            completedMarketsTable.draw();
            return;
        }

        $.fn.dataTable.ext.errMode = 'none';

        completedMarketsTable = $('#completed_markets').DataTable({
            "processing": true,
            "serverSide": true,
            "searching": false,
            "order": [ 0, 'desc' ],
            "paging": false,
            "stripeClasses": [ '' ],
            "ajax": {
                url: "{{ route('completed-index.getdata') }}",
                data: function (d) {
                    d.year = $('#year').val() || '{{ $defaultYear }}';
                    d.month = $('#month').val();
                    d.market = $('#market').val();
                }
            },
            "columns": [
                { "data": "date", "searchable": false },
                { "data": "name" },
                { "data": "actual_revenue", "searchable": false },
                { "data": "action", "orderable": false },
            ],
        });
    }

    $(document).ready(function(){
        function refreshOverviewTabs() {
            loadedTabs.overview = false;
            loadedTabs.markets = false;
            loadedTabs.products = false;
            loadedTabs.details = false;
            loadOverview();
            loadedTabs.overview = true;
            loadedTabs.markets = true;
            loadedTabs.products = true;
        }

        // Only load overview tab data on initial page load
        loadTabData('overview');

        // Toggle view buttons
        $('#categoryViewBtn').click(function() {
            setViewMode('category');
        });

        $('#productViewBtn').click(function() {
            setViewMode('product');
        });

        // Profitability year selector
        $('#profitYear').change(function() {
            loadProfitabilityAnalysis();
        });

        $('#year').change(function(){
            if (completedMarketsTable) completedMarketsTable.draw();
            refreshOverviewTabs();
            loadMonthlyTrends();
        });

        $('#month').change(function(){
            if (completedMarketsTable) completedMarketsTable.draw();
            refreshOverviewTabs();
        });

        $('#market').change(function(){
            if (completedMarketsTable) completedMarketsTable.draw();
            refreshOverviewTabs();
            loadMonthlyTrends();
            loadYearOverYear();
        });
    });
</script>
<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
@endpush

@endsection

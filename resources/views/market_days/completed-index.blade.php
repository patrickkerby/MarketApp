@extends('layout')

@section('content')

@section('class', 'show completed')

    <div class="col-11 col-sm-8 col-lg-8">
      <header class="row justify-content-center">
        <h1><span>Riverbend Gardens</span> Market Days</h1>
        <img class="logo" src="{{ asset('images/logo.svg') }}" alt="Riverbend Gardens flower logo" />
      </header>

      @can ('view_completed_market_days')
        <h1>TEST</h1>
      @endcan
      <div class="row no-gutters">
        <h2 class="col-12">
          Completed Market Days
        </h2>

  
        {{-- <ul class="card-list">
          @foreach ($market_days as $item)
            <li class="col-sm-12 card">
              <a href="/market_days/{{ $item->id }}/edit">
                <strong>{{ \Carbon\Carbon::parse($item->date)->format('F j, Y')}}</strong>
                {{ $item->market->name }}
                <span class="actual_revenue"> - ${{ $item->actual_revenue }}</span>
                <i class="fas fa-chevron-right"></i>
              </a>
            </li>  
          @endforeach              
        </ul> --}}

  
              <div class="form-group">
                  <label><strong>Year:</strong></label>
                  <select id='year' class="form-control" style="width: 100px">
                      <option value="" default>All</option>
                      <option value="2023">2023</option>
                      <option value="2022">2022</option>
                      <option value="2021">2021</option>
                      <option value="2020">2020</option>
                      <option value="2019">2019</option>
                      <option value="2018">2018</option>
                      <option value="2017">2017</option>
                      <option value="2016">2016</option>
                      <option value="2015">2015</option>
                  </select>
              </div>
              <div class="form-group">
                <label><strong>Month:</strong></label>
                <select id='month' class="form-control" style="width: 150px">
                    <option value="" default>All</option>
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
            <div class="form-group">
              <label><strong>Market:</strong></label>
              <select id='market' class="form-control" style="width: 200px">
                <option value="" default>All</option>
                  @foreach ($markets as $market )
                    <option value="{{ $market->id }}">{{ $market->name }}</option>
                  @endforeach
              </select>
          </div>

        <table class="table completed_markets" id="completed_markets" data-page-length='100'>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Name</th>
                    <th>Revenue</th>
                    <th>Action</th>

                </tr>
            </thead>           
        </table>
        
        
        <script>
          $(document).ready(function(){
            $.noConflict();
            var table = $('#completed_markets').DataTable({
              "processing": true,
              "serverSide": true,
              "searching": false,
              "order": [ 0, 'desc' ],
              "paging": false,
              "stripeClasses": [ 'card' ],
              "ajax": {
                url: "{{ route('completed-index.getdata') }}",
                data: function (d) {
                  d.year = $('#year').val(),
                  d.month = $('#month').val(),
                  d.market = $('#market').val(),
                  d.search = $('input[type="search"]').val()
                }
              },
              "columns": [
                { "data": "date", "searchable": "false" },
                { "data": "name" },
                { "data": "actual_revenue", "searchable": "false" },
                { "data": "action"},
              ],
            });

            $('#year').change(function(){
                table.draw();
            });

            $('#month').change(function(){
                table.draw();
            });

            $('#market').change(function(){
                table.draw();
            });

          });
        </script>
      </div>

      

       {{-- @can ('view_completed_market_days')
       <div class="row no-gutters">
        <h2 class="col-12">Completed</h2>
        <ul class="card-list">
          @foreach($items as $item)
          <li class="col-sm-12 card">
            <a href="/market_days/{{ $item->id }}/edit">
              <strong>{{ \Carbon\Carbon::parse($item->date)->format('F j, Y')}}</strong>
              {{ $item->market->name }}
              <i class="fas fa-chevron-right"></i>
            </a>
          </li>  
          @endforeach
        </ul>
      </div>
      @endcan --}}

      <div>
        <a href="/market_days/" class="button">Back to all Market Days</a>
      </div>
      <footer></footer>
    </div>


    @endsection

    
    
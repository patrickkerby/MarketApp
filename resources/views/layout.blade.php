<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

        <title>Riverbend Gardens Market App</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://use.typekit.net/thb0pbt.css">
        <script src="https://kit.fontawesome.com/0e629dcd9e.js" crossorigin="anonymous"></script>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">


    </head>
    <body class="@yield('class')">

      <nav class="navbar navbar-default">
        <!-- Collapsed Hamburger -->

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
            <i class="fas fa-chevron-right"></i>  
            <span class="sr-only">Toggle Navigation</span>                    
          </button>
          <!-- Left Side Of Navbar -->
          <ul class="nav navbar-nav">
            <li class="market_days {{ Request::path() === 'market_days' ? 'current_page_item' : '' }}"><i class="fas fa-map-marker-alt"></i><a href="/market_days">Market Days</a></li>
            <li class="markets {{ Request::path() === 'markets' ? 'current_page_item' : '' }}"><i class="fas fa-sun"></i><a href="/markets">Markets</a></li>
            <li class="products {{ Request::path() === 'products' ? 'current_page_item' : '' }}"><i class="fas fa-shopping-basket"></i><a href="/products">Products</a></li>
            {{-- <li class="{{ Request::path() === 'categories' ? 'current_page_item' : '' }}"><a href="/categories">Categories</a></li> --}}
          </ul>
          <a class="hamburg" href="#">
            <i class="fas fa-bars"></i>
          </a>
         

          {{-- <!-- Right Side Of Navbar -->
          <ul class="navbar-right">
              <!-- Authentication Links -->
              @if (Auth::guest())
                  <li><a href="{{ url('/login') }}">Login</a></li>
              @else
                  <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                          {{ Auth::user()->name }} <span class="caret"></span>
                      </a>

                      <ul class="dropdown-menu" role="menu">
                          <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                      </ul>
                  </li>
              @endif
          </ul> --}}
        </div>
      </nav>

      <div class="container-fluid">
        @if (Route::has('login'))
          <div class="top-right links">
            @auth
              <a href="{{ url('/home') }}">Home</a>
            @else
              <a href="{{ route('login') }}">Login</a>

              @if (Route::has('register'))
                  <a href="{{ route('register') }}">Register</a>
              @endif
            @endauth
          </div>
        @endif
      
        <div class="content row justify-content-center">
          @yield('content')
        </div>
      </div>

      <!-- JavaScripts -->
      
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="{{ elixir('/js/app.js') }}"></script>
    </body>
</html>

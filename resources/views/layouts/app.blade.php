<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">

      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <title>Riverbend Gardens Market App</title>

      <!-- Scripts -->
      <script src="{{ asset('js/app.js') }}" defer></script>

      <!-- Fonts -->
      <link rel="stylesheet" href="https://use.typekit.net/thb0pbt.css">
      <script src="https://kit.fontawesome.com/0e629dcd9e.js" crossorigin="anonymous"></script>

      <!-- Styles -->
      <link href="{{ asset('css/app.css') }}" rel="stylesheet">
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
        <a class="hamburg" data-toggle="collapse" href="#additionalNav" role="button" aria-expanded="false" aria-controls="additionalNav">
          <i class="fas fa-bars"></i>
        </a>

        <div id="additionalNav" class="collapse">
          @if (Route::has('login'))
            @auth
            <p>Hi there, <strong>{{ Auth::user()->name }}!</strong></p>

            <a href="/market_days/completed">See Completed Markets</a>
            @else
              <a href="{{ route('login') }}">Login</a>

              @if (Route::has('register'))
                <a href="{{ route('register') }}">Register</a>
              @endif
            @endauth
          @endif

          <!-- Authentication Links -->
          @guest
          @else
            <a href="{{ route('logout') }}"
            onclick="event.preventDefault();
              document.getElementById('logout-form').submit();">
              {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
          @endguest
        </div>
      </div>
    </nav>
                
  <div class="content row justify-content-center">
    @yield('content')
  </div>
  
  </body>
</html>
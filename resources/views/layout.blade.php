<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Riverbend Gardens Market App</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
                padding-bottom: 6rem;
            }
            #page {
              display: flex;
              flex-direction: column;
              padding-bottom: 6rem;
            }
            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            section {
              margin: 3rem 0;
            }
            .title {
                font-size: 84px;
            }
            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
            .m-b-md {
                margin-bottom: 30px;
            }

            .button {
              text-decoration: none;
              display: inline-flex;
              padding: 0.5rem 2rem;
              border-radius: 2rem;
              background: #636b6f;
              color: #fff;
              font-weight: bold;
              margin: 0 0.5rem;
            }

            .edit {
              display: inline-flex;
              align-self: flex-end;
              font-size: 0.85rem;
              margin: 1.25rem 0;
              position: absolute;
              top: 1rem;
              right: 1rem;
            }

            form {
              display: flex;
              flex-direction: column;
              /* position: relative; */
            }
            textarea {
              margin-bottom: 2rem;
              width: 100%;
            }

        </style>
    </head>
    <body>
      <nav>
        <ul>
          <li class="{{ Request::path() === 'markets' ? 'current_page_item' : '' }}"><a href="/markets">Markets</a></li>
          <li class="{{ Request::path() === 'products' ? 'current_page_item' : '' }}"><a href="/products">Products</a></li>
          <li class="{{ Request::path() === 'categories' ? 'current_page_item' : '' }}"><a href="/categories">Categories</a></li>
          <li class="{{ Request::path() === 'market_days' ? 'current_page_item' : '' }}"><a href="/market_days">Market Days</a></li>
        </ul>
      </nav>
      <div class="flex-center">
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
      
        <div class="content">
          @yield('content')
        </div>
      </div>
    </body>
</html>

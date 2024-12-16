<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Appeal for Unblock')</title>
    <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/app.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="{{ url('js/app.js') }}" defer></script>


    
</head>
<body>
<main>
    <header class="top-nav">
        <div class="logo">
            <a href="{{ Auth::check() ? url('/feed') : url('/home') }}">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" />
            </a>
        </div>
        
        

        <div class="nav-items">
            
        @if (Auth::check())
            
                <a class="btn btn-danger" id="sign-out-appeal" href="{{ url('/logout') }}" title="Sign-Out">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            @else
                <a class="btn btn-solve" id="sign-in" href="{{ url('/login') }}" title="Sign-In">
                    <i class="fas fa-sign-in-alt"></i>
                </a>
            @endif
        </div>
    </header>

        <section id="content">
            @yield('content')
        </section>

    <footer class="footer">
        
        <p>@AskUni</p>
    </footer>
    </main>
</body>
</html>
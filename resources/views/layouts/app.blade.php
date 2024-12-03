<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">

    <!-- Styles -->
    <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/app.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


    <script type="text/javascript" src="{{ url('js/app.js') }}" defer></script>
    @if (Auth::check())
    <script type="text/javascript" src="{{ url('js/notifications.js') }}" defer></script>
    @endif
    <script type="text/javascript" src="{{ url('js/search-users.js') }}" defer></script>
    <script type="text/javascript" src="{{ url('js/search-questions.js') }}" defer></script>
</head>
<body>
    <main>
    <header class="top-nav">
        <div class="logo">
            <a href="{{ Auth::check() ? url('/feed') : url('/home') }}">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" />
            </a>
        </div>
        
        <form action="{{ route('questions.search') }}" method="GET" id="search-bar">
            <input type="text" name="query" id="search-input" placeholder="Search...">
            <label for="exact-match" class="exact-match-label">
                <input type="checkbox" name="exact_match" id="exact-match">
                <i class="fa-solid fa-spell-check" title="Exact Match Search"></i>
            </label>
            <button type="submit" id="search-button">
                <i class="fa fa-search"></i>
            </button>
        </form>

        <div class="nav-items">
            
        @if (Auth::check())
            <div class="explore-tags">
                <a href="#">Explore Tags</a>
            </div>

        

            <div class="add-question">
                <a href="{{ route('questions.create') }}">
                    <button>Add Question</button>
            </div>

            <div class="notifications">
                <a href="#" id="notification-bell">
                    <i id="notification-icon" class="fa-regular fa-bell"></i>
                </a>
                <div class="notifications-dropdown" id="notifications-dropdown">
                    <button id="mark-all-as-read" class="btn btn-primary btn-sm">Mark All as Read</button>
                    <ul id="notifications-list">

                    </ul>
                </div>
            </div>

                <div class="profile">
                    <a href="{{ route('profile', Auth::user()->id) }}">
                        <i class="fa fa-user"></i>
                    </a>
                </div>
                <a href="{{ url('/logout') }}">
                    <button id="log">Logout </button>

                </a>
            @else
                <a href="{{ url('/login') }}">
                    <button id="log">Login </button>
                </a>
            @endif
        </div>
    </header>

        <!-- Seção de Conteúdo -->
        <section id="content">
            @yield('content')
        </section>

    <footer class="footer">
        <p>@AskUni</p>
    </footer>
    </main>
</body>
</html>

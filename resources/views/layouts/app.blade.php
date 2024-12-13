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

    <!-- Choices.js -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">

    <script type="text/javascript" src="{{ url('js/app.js') }}" defer></script>
    @if (Auth::check())
    <script type="text/javascript" src="{{ url('js/notifications.js') }}" defer></script>
    @endif
    <script type="text/javascript" src="{{ url('js/search-users.js') }}" defer></script>
    <script type="text/javascript" src="{{ url('js/search-questions.js') }}" defer></script>
    <script type="text/javascript" src="{{ url('js/tags.js') }}" defer></script>
    <script type="text/javascript" src="{{ url('js/like-dislike.js') }}" defer></script>    

    <script type="text/javascript" src="{{ url('js/follow-unfollow.js') }}" defer></script>
    <script type="text/javascript" src="{{ url('js/modal.js') }}" defer></script>
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
                <a href="{{ route('tags.index') }}">Explore Tags</a>
            </div>

        

            <div class="add-question">
                <a href="{{ route('questions.create') }}">
                    <button>Add Question</button>
            </div>

            <div class="support">
                    <a href="{{ route('support-questions.create') }} " id="support" title="Support">
                        <i class="fa fa-headset"></i>
                    </a>
            </div>

            <div class="notifications">
                <a href="#" id="notification-bell" title="Notifications">
                    <i id="notification-icon" class="fa-regular fa-bell"></i>
                </a>
                <div class="notifications-dropdown" id="notifications-dropdown">
                    <button id="mark-all-as-read" class="btn btn-primary btn-sm">Mark All as Read</button>
                    <ul id="notifications-list">

                    </ul>
                </div>
            </div>

                

                <div class="profile">
                    <a href="{{ route('profile', Auth::user()->id) }} " id="user" title="Profile">
                        <i class="fa fa-user"></i>
                    </a>
                </div>
                <a class="btn btn-danger" id="sign-out" href="{{ url('/logout') }}" title="Sign-Out">
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
        <a href="{{ route('faq.index') }}">FAQ</a>
        <a href="{{ route('aboutUs.index') }}">About Us</a>
        <p>@AskUni</p>
    </footer>
    </main>
    
</body>
</html>

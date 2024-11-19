<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ url('css/milligram.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/app.css') }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <script type="text/javascript" src="{{ url('js/app.js') }}" defer></script>
</head>
<body>
    <main>
    <header class="top-nav">
        <div class="logo">
            <a href="{{ url('/home') }}">
                <img src="logo.png" alt="Logo" />
            </a>
        </div>
        
        <div class="search">
            <input type="text" placeholder="Pesquisar...">
        </div>

        <div class="nav-items">
            <div class="explore-tags">
                <a href="#">Explore Tags</a>
            </div>

            <div class="add-question">
                <a href="{{ route('questions.create') }}">
                    <button>Adicionar Pergunta</button>
            </div>

            <div class="notifications">
                <a href="#">
                    <i class="fa fa-bell"></i>
                </a>
            </div>

            <div class="profile">
                <a href="{{ url('/users/' . Auth::user()->id) }}">
                    <i class="fa fa-user"></i>
                </a>
            </div>

            @if (Auth::check())
                <a class="button" href="{{ url('/logout') }}">Logout</a> 
                <span>{{ Auth::user()->name }}</span>
            @endif
        </div>
    </header>

        <!-- Seção de Conteúdo -->
        <section id="content">
            @yield('content')
        </section>
    </main>
</body>
</html>

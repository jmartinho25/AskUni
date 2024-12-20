@extends('layouts.app')

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<form id="loginForm" method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}

    <label for="email">E-mail
        <div class="tooltip">
            <i class="fas fa-info-circle"></i>
            <span class="tooltip-text">Format: @fe.up.pt</span>
        </div>
    </label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="example@fe.up.pt" required autofocus>
    @if ($errors->has('email'))
        <span class="error">
          {{ $errors->first('email') }}
        </span>
    @endif

    <label for="password">Password</label>
    <input id="password" type="password" name="password" placeholder="********" required>
    @if ($errors->has('password'))
        <span class="alert-error">
            {{ $errors->first('password') }}
        </span>
    @endif

    <label>
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
    </label>

    <button type="submit">
        Login
    </button>
    <a class="button button-outline" href="{{ route('register') }}">Register</a>
    <a class="button button-outline" href="{{ route('password.request') }}">Forgot Your Password?</a>
</form>
@endsection
@extends('layouts.app')

@section('content')

<form id="registerForm" method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}

    <label for="username">Username
      <div class="tooltip">
        <i class="fas fa-info-circle"></i>
        <span class="tooltip-text">Maximum 20 characters</span>
      </div>
    </label>
    <input id="username" type="text" name="username" value="{{ old('username') }}"  required autofocus>
    @if ($errors->has('username'))
      <span class="error">
          {{ $errors->first('username') }}
      </span>
    @endif

    <label for="name">Name
      <div class="tooltip">
        <i class="fas fa-info-circle"></i>
        <span class="tooltip-text">Maximum 50 characters</span>
      </div>
    </label>
    <input id="name" type="text" name="name" value="{{ old('name') }}"  required>
    @if ($errors->has('name'))
      <span class="error">
          {{ $errors->first('name') }}
      </span>
    @endif

    <label for="email">E-Mail Address
      <div class="tooltip">
        <i class="fas fa-info-circle"></i>
        <span class="tooltip-text">Format: @fe.up.pt <br> Maximum 250 characters</span>
      </div>
    </label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="example@fe.up.pt" required>
    @if ($errors->has('email'))
      <span class="error">
          {{ $errors->first('email') }}
      </span>
    @endif

    <label for="password">Password
      <div class="tooltip">
        <i class="fas fa-info-circle"></i>
        <span class="tooltip-text">Minimum 8 characters</span>
      </div>
    </label>
    <input id="password" type="password" name="password" placeholder="********" required>
    @if ($errors->has('password'))
      <span class="error">
          {{ $errors->first('password') }}
      </span>
    @endif

    <label for="password-confirm">Confirm Password</label>
    <input id="password-confirm" type="password" name="password_confirmation" placeholder="********" required>

    <button type="submit">
      Register
    </button>
    <a class="button button-outline" href="{{ route('login') }}">Login</a>
    <a class="button button-outline" href="{{ route('password.request') }}">Forgot Your Password?</a>
</form>
@endsection
@extends('layouts.app')

@section('content')
<div class="container feedback-container">
    <section class="feedback-content text-center">
        <img src="{{ asset('static/' . (session('status') == 'Success!' ? 'success' : 'fail') . '.png') }}" alt="{{ session('status') }}" class="feedback-image">
        <h3>{{ session('status') }}</h3>
        <h4>{{ session('message') }}</h4>
        @if(session('details'))
            <div class="feedback-details">
                @foreach(session('details') as $detail)
                    <h5>{{ $detail }}</h5>
                @endforeach
            </div>
        @endif
        @if(session('status') == 'Success!')
            <button class="btn btn-primary mt-3" onclick="window.location.href='{{ route('login') }}'">
                Back to Login
            </button>
        @else
            <button class="btn btn-primary mt-3" onclick="window.location.href='{{ route('send.email.form') }}'">
                Try Again
            </button>
        @endif
    </section>
</div>
@endsection
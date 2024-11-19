@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $user->name }}'s Profile</h1>
    <p>Username: {{ $user->username }}</p>
    <p>Email: {{ $user->email }}</p>
    <p>Description: {{ $user->description }}</p>
    <p>Score: {{ $user->score }}</p>

    <div>
        @php
            $profilePicture = $user->photo ? $user->photo : 'profilePictures/default.png';
        @endphp
        <img src="{{ asset($profilePicture) }}" alt="{{ $user->name }}'s Profile Picture">
    </div>

    @if (auth()->check() && auth()->user()->id === $user->id)
    <a class="button" href="{{ route('edit-profile') }}" class="btn btn-primary">Edit Profile</a>
    @endif

    <h2>Answers</h2>
    @foreach ($answers as $answer)
        <div>
            <p>{{ $answer->content }}</p>
            <p>Date: {{ $answer->date }}</p>
        </div>
    @endforeach

    <h2>Questions</h2>
    @foreach ($questions as $question)
        <div>
            <h3>{{ $question->title }}</h3>
            <p>{{ $question->content }}</p>
            <p>Date: {{ $question->date }}</p>
        </div>
    @endforeach
</div>
@endsection
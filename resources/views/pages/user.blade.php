@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

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

    <h2>Questions</h2>
    @if ($questions->isEmpty())
        <p>No questions available.</p>
    @else
    @foreach ($questions as $question)
        <div>
            <h3>{{ $question->title }}</h3>
            <p>{{ $question->post->content }}</p>
            <p>Date: {{ $question->post->date }}</p>
            <a href="{{ route('questions.show', $question->posts_id) }}" class="btn btn-secondary">Read More</a>
        </div>
    @endforeach
    @endif

    <h2>Answers</h2>
    @if ($answers->isEmpty())
        <p>No answers available.</p>
    @else
    @foreach ($answers as $answer)
        <div>
            <p>{{ $answer->content }}</p>
            <p>Date: {{ $answer->date }}</p>

            @if (auth()->check() && auth()->user()->id === $answer->users_id)
                <a href="{{ route('answers.edit', $answer->id) }}" class="btn btn-secondary">Edit Answer</a>
                <form action="{{ route('answers.destroy', $answer->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Answer</button>
                </form>
            @endif

        </div>
    @endforeach
    @endif
</div>
@endsection
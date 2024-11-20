@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h1>{{ $question->title }}</h1>

    <p>{{ $question->post->content }}</p>

    <p>Created by: <a href="{{ route('profile', $question->post->user->id) }}">{{ $question->post->user->name }}</a></p>

    <p>Date:
        @if($question->post->date instanceof \Carbon\Carbon)
            {{ $question->post->date->format('d/m/Y H:i') }}
        @else
            {{ $question->post->date }}
        @endif
    </p>

    <a class="button" href="{{ route('home') }}" class="btn btn-secondary mb-3">Back to Home Page</a>

    <a class= "button" href="{{ route('answers.create', $question) }}" class="btn btn-primary mb-3">Add Answer</a>

    @can('update', $question)
        <a href="{{ route('questions.edit', $question) }}" class="btn btn-primary">Edit Question</a>
    @endcan

    @can('delete', $question)
        <form action="{{ route('questions.destroy', $question) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Question</button>
        </form>
    @endcan

    </div>
    @endsection

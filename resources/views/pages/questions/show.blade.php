@extends('layouts.app')

@section('content')
@if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif
<div class="container">
    

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

    <p>
        @foreach ($question->tags as $tag)
             <span class="tag">#{{ $tag->name }}</span>
        @endforeach
    </p>

    <a class="button" href="{{ route('home') }}" class="btn btn-secondary mb-3">Back to Home Page</a>

    <a class="button" href="{{ route('answers.create', $question) }}" class="btn btn-primary mb-3">Add Answer</a>

    @can('update', $question)
        <a class="button" href="{{ route('questions.edit', $question) }}" class="btn btn-primary">Edit Question</a>
    @endcan

    @can('delete', $question)
        <form action="{{ route('questions.destroy', $question) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete Question</button>
        </form>
    @endcan

    <h2>Answers</h2>
    <div class="all-questions">
    @if ($question->answers->isEmpty())
        <p>No answers available.</p>
    @else
    @foreach ($question->answers as $answer)
        <div class="answer-card">
            <p>{{ $answer->post->content }}</p>
            <p>Answered by: <a href="{{ route('profile', $answer->post->user->id) }}">{{ $answer->post->user->name }}</a></p>
            <p>Date: {{ $answer->post->date }}</p>

            @if (!$answer->comments->isEmpty())
            <h3>Comments</h3>
            <ul class="question-card">
                        @foreach ($answer->comments as $comment)
                            
                                <p>{{ $comment->content }}</p>
                                <p>Commented by: <a href="{{ route('profile', $comment->user->id) }}">{{ $comment->user->name }}</a></p>
                                <p>Date: {{ $comment->date }}</p>
                            
                        @endforeach
            </ul>
            @endif
        </div>
    @endforeach
    </div>
    @endif

    <h2>Comments</h2>
    <div class="all-questions">
    @if ($question->comments->isEmpty())
        <p>No comments available.</p>
    @else
    @foreach ($question->comments as $comment)
        <div class="question-card">
            <p>{{ $comment->content }}</p>
            <p>Commented by: <a href="{{ route('profile', $comment->user->id) }}">{{ $comment->user->name }}</a></p>
            <p>Date: {{ $comment->date }}</p>
        </div>
    @endforeach
    @endif
    </div>
    </div>
    @endsection

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

    <p>Created by: 
        @if ($question->post->user)
            @if ($question->post->user->trashed())
                <span>Deleted User</span>
            @else
                <a href="{{ route('profile', $question->post->user->id) }}">{{ $question->post->user->name }}</a>
            @endif
        @else
            <span>Deleted User</span>
        @endif
    </p>

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

    <a class="button" href="{{ route('home') }}" id="btn-edit">
        <i class="fas fa-home"></i>
    </a>

    @if (Auth::check())
    <a class="button" href="{{ route('answers.create', $question) }}" id="btn-edit">
        <i class="fas fa-reply"></i>
    </a>
    <a class="button" href="{{ route('comments.create', ['question', $question->posts_id]) }}" id="btn-edit">
        <i class="fas fa-comment"></i>
    </a>
    @endif

    @can('update', $question)
        <a class="button" href="{{ route('questions.edit', $question) }}" id="btn-edit">
            <i class="fas fa-pencil-alt"></i>
        </a>
    @endcan

    @can('delete', $question)
        <form action="{{ route('questions.destroy', $question) }}" method="POST" class="delete-form">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Question?')">
                <i class="fas fa-trash-alt"></i>
            </button>
        </form>
    @endcan

    <h2>Answers</h2>
    <div class="all-questions">
    @if ($question->answers->isEmpty())
        <p>No answers available.</p>
    @else
    @foreach ($question->answers as $answer)
        <div class="answer-card" id="answer-{{ $answer->posts_id }}">
            <p>{{ $answer->post->content }}</p>
            <p>Answered by: 
                @if ($answer->post->user)
                    @if ($answer->post->user->trashed())
                        <span>Deleted User</span>
                    @else
                        <a href="{{ route('profile', $answer->post->user->id) }}">{{ $answer->post->user->name }}</a>
                    @endif
                @else
                    <span>Deleted User</span>
                @endif
            </p>
            <p>Date: {{ $answer->post->date }}</p>

            @can('update', $answer)
                <a class="button" href="{{ route('answers.edit', $answer) }}" id="btn-edit">
                    <i class="fas fa-pencil-alt"></i>
                </a>
            @endcan

            @can('delete', $answer)
                <form action="{{ route('answers.destroy', $answer) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Answer?')">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            @endcan

            @if (Auth::check())
            <a class="button" href="{{ route('comments.create', ['answer', $answer->posts_id]) }}" id="btn-edit">
                <i class="fas fa-comment"></i>
            </a>
            @endif

            @if (!$answer->comments->isEmpty())
                <h3>Comments</h3>
                    @foreach ($answer->comments as $comment)
                    <ul class="question-card">
                        <p>{{ $comment->content }}</p>
                        <p>Commented by: 
                            @if ($comment->user)
                                @if ($comment->user->trashed())
                                    <span>Deleted User</span>
                                @else
                                    <a href="{{ route('profile', $comment->user->id) }}">{{ $comment->user->name }}</a>
                                @endif
                            @else
                                <span>Deleted User</span>
                            @endif
                        </p>
                        <p>Date: {{ $comment->date }}</p>
                        </ul>
                    @endforeach
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
                    <p>Commented by: 
                        @if ($comment->user)
                            @if ($comment->user->trashed())
                                <span>Deleted User</span>
                            @else
                                <a href="{{ route('profile', $comment->user->id) }}">{{ $comment->user->name }}</a>
                            @endif
                        @else
                            <span>Deleted User</span>
                        @endif
                    </p>
                    <p>Date: {{ $comment->date }}</p>
                </div>
            @endforeach
        @endif
    </div>
    </div>
    @endsection

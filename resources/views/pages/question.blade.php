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
    <p>Date: {{$question->post->date}}</p>
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
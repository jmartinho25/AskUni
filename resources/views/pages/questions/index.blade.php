@extends('layouts.app')

@section('content')
@if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
    <div class="container">
        <h2>Questions List</h2>




        <div class="all-questions">
        @if (!empty($questions))
            @foreach ($questions as $question)
                <div class="question-card">
                    <h3>{{ $question->title }}</h3>
                    <a href="{{ route('profile', $question->post->user->id) }}" class="question-user-name">{{ $question->post->user->name }}</a>
                    <a class="read_more" href="{{ route('questions.show', $question->posts_id) }}">Read More</a>
                </div>
            @endforeach
        @else
            <p>No questions available.</p>
        @endif
        </div>

        @if ($questions->total() > $questions->perPage())
        <div class="pagination">
            {{ $questions->links() }}
        </div>
        @endif
    </div>
@endsection

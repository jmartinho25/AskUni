@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Welcome to the Home Page</h1>
    <p>This is the home page of your application.</p>

    <h2>Trending Questions</h2>
    <div class="trending-questions">
    @if (!empty($trendingQuestions))
        @foreach ($trendingQuestions as $question)
            <div class="question-card">
                <h3>{{ $question->title }}</h3>
                <p>{{ $question->description }}</p>
                <a href="{{ route('questions.show', $question->posts_id) }}">Read More</a>
            </div>
        @endforeach
    @else
        <p>No trending questions available.</p>
    @endif
    </div>

    <h2>All Questions</h2>
    <div class="all-questions">
        @if (!empty($allQuestions))
            @foreach ($allQuestions as $question)
                <div class="question-card">
                    <h3>{{ $question->title }}</h3>
                    <a href="{{ route('questions.show', $question->posts_id) }}">Read More</a>
                </div>
            @endforeach
        @else
            <p>No questions available.</p>
        @endif
    </div>
</div>
@endsection
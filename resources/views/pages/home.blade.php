@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="container">

    <h2>Trending Questions</h2>
    <div class="trending-questions">
    @if (!empty($trendingQuestions))
        @foreach ($trendingQuestions as $question)
            <div class="question-card">
                <h3>{{ $question->title }}</h3>
                <p>{{ $question->description }}</p>
                <a class="read_more" href="{{ route('questions.show', $question->posts_id) }}">Read More</a>
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
                    <a class="read_more" href="{{ route('questions.show', $question->posts_id) }}">Read More</a>
                </div>
            @endforeach
        @else
            <p>No questions available.</p>
        @endif
    </div>

    @if ($allQuestions->total() > $allQuestions->perPage())
    <div class="pagination">
        {{ $allQuestions->links() }}
    </div>
    @endif

</div>
@endsection
@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
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

    <h2>Questions Relevant to Your Interests</h2>
    @if($relevantQuestions->isEmpty())
        <p>No relevant questions available.</p>
    @else
    <div class="relevant-questions">
            @foreach ($relevantQuestions as $question)
                <div class="question-card">
                    <h3>{{ $question->title }}</h3>
                    <div class="tags">
                        @foreach ($question->tags as $tag)
                            <a href="{{ route('tags.show', $tag->name) }}"> <span class="tag">#{{ $tag->name }}</span> </a>
                        @endforeach
                    </div>
                    <a class="read_more" href="{{ route('questions.show', $question->posts_id) }}">Read More</a>
                </div>
            @endforeach
    </div>
    @if ($relevantQuestions->total() > $relevantQuestions->perPage())
    <div class="pagination">
            {{ $relevantQuestions->links() }}
    </div>
    @endif
    @endif

    <h3 class="view-all-questions">
        <a href="{{ route('questions.index') }}" class="btn btn-primary">View All Questions</a>
    </h3>

</div>
@endsection
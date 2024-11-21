@extends('layouts.app')

@section('content')
<div class="container">
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
                            <span class="tag">#{{ $tag->name }}</span>
                        @endforeach
                    </div>
                    <a href="{{ route('questions.show', $question->posts_id) }}">Read More</a>
                </div>
            @endforeach
    </div>

        <div class="pagination">
            {{ $relevantQuestions->links() }}
        </div>
    @endif

    <h3 class="view-all-questions">
        <a href="{{ route('questions.index') }}" class="btn btn-primary">View All Questions</a>
    </h3>

</div>
@endsection
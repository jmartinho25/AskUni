@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Search Results</h1>
    <div id="results-container" class="all-questions">
        @if($results->isEmpty())
            <p>No results found for "{{ $query }}".</p>
        @else
                @foreach($results as $result)
                    <div class="question-card">
                        <h3><a href="{{ route('questions.show', $result->posts_id) }}" class="result-title">{{ $result->title }}</a></h3>
                        <a href="{{ route('profile', $result->post->user->id) }}" class="result-username">{{ $result->post->user->username }}</a>
                        <small class="result-date">Published on: {{ $result->post->date }}</small>
                    </div>
                @endforeach
        @endif
    </div>

    <div id="pagination-container">
        {{ $results->appends(['query' => $query, 'exact_match' => request()->input('exact_match')])->links() }}
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Search Results</h1>
    <form method="GET" action="{{ route('questions.search') }}">
        <input type="hidden" name="query" value="{{ request()->input('query') }}">
        <input type="hidden" name="exact_match" value="{{ request()->input('exact_match') }}">
        <label for="order">Order By:</label>
        <select name="order" id="order">
            <option value="relevance" {{ request()->input('order') == 'relevance' ? 'selected' : '' }}>Relevance</option>
            <option value="date_desc" {{ request()->input('order') == 'date_desc' ? 'selected' : '' }}>Newest</option>
            <option value="date_asc" {{ request()->input('order') == 'date_asc' ? 'selected' : '' }}>Oldest</option>
        </select>
    </form>

    <div id="results-container" class="all-questions">
        @if($results->isEmpty())
            <p>No results found for "{{ $query }}".</p>
        @else
            @foreach($results as $result)
                <div class="question-card">
                    <h3><a href="{{ route('questions.show', $result->posts_id) }}" class="result-title">{{ $result->title }}</a></h3>
                    @if($result->post->user)
                        <a href="{{ route('profile', $result->post->user->id) }}" class="question-user-name">{{ $result->post->user->name }}</a>     
                    @else
                        <span class="question-user-name">Deleted User</span>
                    @endif
                    <small class="result-date">Published on: {{ $result->post->date }}</small>
                </div>
            @endforeach
        @endif
    </div>

    <div id="pagination-container">
        {{ $results->appends(['query' => $query, 'exact_match' => request()->input('exact_match'), 'order' => request()->input('order')])->links() }}
    </div>  
</div>
@endsection
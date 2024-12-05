@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Questions Tagged with "{{ $tag->name }}"</h1>
    <div class="sort-buttons">
        <form id="sort-form" action="{{ route('tags.show', ['name' => $tag->name]) }}" method="GET">
            <label for="sort">Sort by:</label>
            <select name="sort" id="sort" class="form-control" data-tag-name="{{ $tag->name }}">
                <option value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Newest</option>
                <option value="popularity" {{ $sort == 'popularity' ? 'selected' : '' }}>Popularity</option>
            </select>
        </form>
    </div>
    @if($questions->isEmpty())
        <p>No questions found for this tag.</p>
    @else
        <div class="all-questions">
            @foreach($questions as $question)
                <div class="question-card">
                    <h3><a href="{{ route('questions.show', $question->posts_id) }}" class="result-title">{{ $question->title }}</a></h3>
                    @if($question->post->user)
                        <a href="{{ route('profile', $question->post->user->id) }}" class="question-user-name">{{ $question->post->user->name }}</a>     
                    @else
                        <span class="question-user-name">Deleted User</span>
                    @endif
                    <small class="result-date">Published on: {{ $question->post->date }}</small>
                </div>
            @endforeach
        </div>
        <div class="pagination">
            {{ $questions->appends(['sort' => $sort])->links() }}
        </div>
    @endif
</div>
@endsection
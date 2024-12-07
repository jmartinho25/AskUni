@extends('layouts.app')

@section('content')
<div class="container">
    <div class="tag-layout">
        <div class="tag-info">
            @php
                $tagPicture = $tag->picture ? $tag->picture : 'img/tags/default.png';
            @endphp
            <img src="{{ asset($tagPicture) }}" alt="{{ $tag->name }}" class="tag-image">
            @auth
                <button id="follow-button-tags" class="btn btn-primary" data-tag-name="{{ $tag->name }}">
                    {{ $isFollowing ? 'Unfollow' : 'Follow' }}
                </button>
            @endauth
            <p>{{ $tag->about }}</p>
        </div>
        <div class="tag-questions">
            <h1>{{ $tag->name }}</h1>
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
    </div>
</div>
@endsection
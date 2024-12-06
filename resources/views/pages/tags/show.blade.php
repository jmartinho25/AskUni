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
    @auth
        <button id="follow-button" class="btn btn-primary" data-tag-name="{{ $tag->name }}">
            {{ $isFollowing ? 'Unfollow' : 'Follow' }}
        </button>
    @endauth
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
    </div>

    <div class="answers-section">
        <h2>Answers</h2>
        @if ($question->answers->isEmpty())
            <p>No answers available.</p>
        @else
            @foreach ($question->answers as $answer)
                <div class="answer-item">
                    <p>{{ $answer->post->content }}</p>
                    <p>Answered by: 
                        @if ($answer->post->user)
                            @if ($answer->post->user->trashed())
                                <span>Deleted User</span>
                            @else
                                <a href="{{ route('profile', $answer->post->user->id) }}">{{ $answer->post->user->name }}</a>
                            @endif
                        @else
                            <span>Deleted User</span>
                        @endif
                    </p>
                    <p>Date: {{ $answer->post->date }}</p>

                    <p>
                        @if (Auth::check() && $answer->post->isLikedBy(Auth::user()))
                            <form action="{{ route('like.destroy', $answer->posts_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-like">
                                    <i class="fas fa-thumbs-up"></i> {{ $answer->post->likesCount() }}
                                </button>
                            </form>
                        @else
                            @can('like', $answer->post)
                                <form action="{{ route('like.store', $answer->posts_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-like">
                                        <i class="far fa-thumbs-up"></i> {{ $answer->post->likesCount() }}
                                    </button>
                                </form>
                            @else
                                <form style="display:inline;">
                                    <button type="button" class="btn btn-like" disabled>
                                        <i class="far fa-thumbs-up"></i> {{ $answer->post->likesCount() }}
                                    </button>
                                </form>
                            @endcan
                        @endif

                        @if (Auth::check() && $answer->post->isDislikedBy(Auth::user()))
                            <form action="{{ route('dislike.destroy', $answer->posts_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-like">
                                    <i class="fas fa-thumbs-down"></i> {{ $answer->post->dislikesCount() }}
                                </button>
                            </form>
                        @else
                            @can('dislike', $answer->post)
                                <form action="{{ route('dislike.store', $answer->posts_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-like">
                                        <i class="far fa-thumbs-down"></i> {{ $answer->post->dislikesCount() }}
                                    </button>
                                </form>
                            @else
                                <form style="display:inline;">
                                    <button type="button" class="btn btn-like" disabled>
                                        <i class="far fa-thumbs-down"></i> {{ $answer->post->dislikesCount() }}
                                    </button>
                                </form>
                            @endcan
                        @endif
                    </p>

                    @can('update', $answer)
                        @if(Auth::user()->id === $answer->post->users_id)
                            <a class="button" href="{{ route('answers.edit', $answer) }}" id="btn-edit" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                        @endif
                    @endcan

                    @can('delete', $answer)
                        <form action="{{ route('answers.destroy', $answer) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Answer?')" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    @endcan

                    @if (Auth::check())
                        <a class="button" href="{{ route('comments.create', ['answer', $answer->posts_id]) }}" id="btn-edit" title="Comment">
                            <i class="fas fa-comment"></i>
                        </a>
                        <a class="button" href="{{ route('report.create', ['type' => 'post', 'id' => $answer->posts_id, 'redirect_url' => url()->current()]) }}" id="btn-danger" title="Report">
                            <i class="fas fa-exclamation-triangle"></i>
                        </a>
                    @endif

                    @if (!$answer->comments->isEmpty())
                        <h3>Comments</h3>
                        @foreach ($answer->comments as $comment)
                            <div class="comment-item">
                                <p>{{ $comment->content }}</p>
                                <p>Commented by: 
                                    @if ($comment->user)
                                        @if ($comment->user->trashed())
                                            <span>Deleted User</span>
                                        @else
                                            <a href="{{ route('profile', $comment->user->id) }}">{{ $comment->user->name }}</a>
                                        @endif
                                    @else
                                        <span>Deleted User</span>
                                    @endif
                                </p>
                                <p>Date: {{ $comment->date }}</p>
                                <div class="comment-actions">
                                    @can('update', $comment)
                                        @if(Auth::user()->id === $comment->users_id)
                                            <a class="button" href="{{ route('comments.edit', $comment) }}" id="btn-edit" title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        @endif
                                    @endcan
                                    @can('delete', $comment)
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Comment?')" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endcan
                                    @if (Auth::check())
                                        <a class="button" href="{{ route('report.create', ['type' => 'comment', 'id' => $comment->id, 'redirect_url' => url()->current()]) }}" id="btn-danger" title="Report">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    <div class="question-comments-section">
        <h2>Comments</h2>
        @if ($question->comments->isEmpty())
            <p>No comments available.</p>
        @else
            @foreach ($question->comments as $comment)
               
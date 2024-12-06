@extends('layouts.app')

@section('content')
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="container">
    <div class="question-section">
        <div class="question-item">
            <h1>{{ $question->title }}</h1>
            <p>{{ $question->post->content }}</p>

            @auth
                <button class="follow-btn btn {{ auth()->user()->followedQuestions()->where('questions_id', $question->posts_id)->exists() ? 'btn-warning' : 'btn-primary' }}" data-question-id="{{ $question->posts_id }}">
                    {{ auth()->user()->followedQuestions()->where('questions_id', $question->posts_id)->exists() ? 'Unfollow' : 'Follow' }}
                </button>
            @endauth

            <p>Created by: 
                @if ($question->post->user)
                    @if ($question->post->user->trashed())
                        <span>Deleted User</span>
                    @else
                        <a href="{{ route('profile', $question->post->user->id) }}">{{ $question->post->user->name }}</a>
                    @endif
                @else
                    <span>Deleted User</span>
                @endif
            </p>

            <p>Date:
                @if($question->post->date instanceof \Carbon\Carbon)
                    {{ $question->post->date->format('d/m/Y H:i') }}
                @else
                    {{ $question->post->date }}
                @endif
            </p>

    <p>
        @foreach ($question->tags as $tag)
            <a href="{{ route('tags.show', $tag->name) }}"> <span class="tag">#{{ $tag->name }}</span> </a>
        @endforeach
    </p>

            <p>
                @if (Auth::check() && $question->post->isLikedBy(Auth::user()))
                    <form action="{{ route('like.destroy', $question->posts_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-like">
                            <i class="fas fa-thumbs-up"></i> {{ $question->post->likesCount() }}
                        </button>
                    </form>
                @else
                    @can('like', $question->post)
                        <form action="{{ route('like.store', $question->posts_id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-like">
                                <i class="far fa-thumbs-up"></i> {{ $question->post->likesCount() }}
                            </button>
                        </form>
                    @else
                        <form style="display:inline;">
                            <button type="button" class="btn btn-like" disabled>
                                <i class="far fa-thumbs-up"></i> {{ $question->post->likesCount() }}
                            </button>
                        </form>
                    @endcan
                @endif

                @if (Auth::check() && $question->post->isDislikedBy(Auth::user()))
                    <form action="{{ route('dislike.destroy', $question->posts_id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-like">
                            <i class="fas fa-thumbs-down"></i> {{ $question->post->dislikesCount() }}
                        </button>
                    </form>
                @else
                    @can('dislike', $question->post)
                        <form action="{{ route('dislike.store', $question->posts_id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-like">
                                <i class="far fa-thumbs-down"></i> {{ $question->post->dislikesCount() }}
                            </button>
                        </form>
                    @else
                        <form style="display:inline;">
                            <button type="button" class="btn btn-like" disabled>
                                <i class="far fa-thumbs-down"></i> {{ $question->post->dislikesCount() }}
                            </button>
                        </form>
                    @endcan
                @endif
            </p>

            <a class="button" href="{{ route('home') }}" id="btn-edit" title="Home Page">
                <i class="fas fa-home"></i>
            </a>

            @if (Auth::check())
                <a class="button" href="{{ route('answers.create', $question) }}" id="btn-edit" title="Answer">
                    <i class="fas fa-reply"></i>
                </a>
                <a class="button" href="{{ route('comments.create', ['question', $question->posts_id]) }}" id="btn-edit" title="Comment">
                    <i class="fas fa-comment"></i>
                </a>
                <a class="button" href="{{ route('report.create', ['type' => 'post', 'id' => $question->posts_id, 'redirect_url' => url()->current()]) }}" id="btn-danger" title="Report">
                    <i class="fas fa-exclamation-triangle"></i> 
                </a>
            @endif

            @can('update', $question)
                @if(Auth::user()->id === $question->post->users_id)
                    <a class="button" href="{{ route('questions.edit', $question) }}" id="btn-edit" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                @endif
            @endcan
            @can ('admin', Auth::user())
                <a class="button" href="{{ route('questions.edit-tags', $question) }}" id="btn-edit" title="Edit Tags">
                    <i class="fas fa-tags"></i>
                </a>
            @endcan

            @can('delete', $question)
                <form action="{{ route('questions.destroy', $question) }}" method="POST" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Question?')" title="Delete">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            @endcan
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
                <div class="question-comment-item">
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
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#follow-btn').click(function() {
            var questionId = {{ $question->posts_id }};
            var isFollowing = $(this).hasClass('btn-warning');

            $.ajax({
                url: isFollowing ? '{{ route("questions.unfollow", ":id") }}'.replace(':id', questionId) : '{{ route("questions.follow", ":id") }}'.replace(':id', questionId),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('#follow-btn').toggleClass('btn-warning btn-primary');
                        $('#follow-btn').text(isFollowing ? 'Follow' : 'Unfollow');
                    }
                },
                error: function(xhr) {
                    alert('An error occurred. Please try again.');
                }
            });
        });
    });
</script>
@endsection
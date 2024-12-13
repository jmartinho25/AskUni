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

            <p>Created by:&nbsp;
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
            @auth
                <button class="follow-btn btn {{ auth()->user()->followedQuestions()->where('questions_id', $question->posts_id)->exists() ? 'btn-warning' : 'btn-primary' }}" data-question-id="{{ $question->posts_id }}">
                    {{ auth()->user()->followedQuestions()->where('questions_id', $question->posts_id)->exists() ? 'Unfollow' : 'Follow' }}
                </button>
            @endauth
            </p>

            <p id="likes">
                @if (Auth::check() && $question->post->isLikedBy(Auth::user()))
                    <button class="btn btn-like like-btn btn-like-active" data-post-id="{{ $question->posts_id }}">
                        <i class="fas fa-thumbs-up"></i> <span class="like-count">{{ $question->post->likesCount() }}</span>
                    </button>
                @else
                    @can('like', $question->post)
                    <button class="btn btn-like like-btn" data-post-id="{{ $question->posts_id }}">
                        <i class="far fa-thumbs-up"></i> <span class="like-count">{{ $question->post->likesCount() }}</span>
                    </button>
                    @else
                    <button class="btn btn-like like-btn" data-post-id="{{ $question->posts_id }}" disabled>
                        <i class="far fa-thumbs-up"></i> <span class="like-count">{{ $question->post->likesCount() }}</span>
                    </button>
                    @endcan
                @endif

                @if (Auth::check() && $question->post->isDislikedBy(Auth::user()))
                    <button class="btn btn-like dislike-btn btn-like-active" data-post-id="{{ $question->posts_id }}">
                        <i class="fas fa-thumbs-down"></i> <span class="dislike-count">{{ $question->post->dislikesCount() }}</span>
                    </button>
                @else
                    @can('dislike', $question->post)
                    <button class="btn btn-like dislike-btn" data-post-id="{{ $question->posts_id }}">
                        <i class="far fa-thumbs-down"></i> <span class="dislike-count">{{ $question->post->dislikesCount() }}</span>
                    </button>
                    @else
                    <button class="btn btn-like dislike-btn" data-post-id="{{ $question->posts_id }}" disabled>
                        <i class="far fa-thumbs-down"></i> <span class="dislike-count">{{ $question->post->dislikesCount() }}</span>
                    </button>
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
            
            @can('admin', Auth::user())
                @if(Auth::user()->id !== $question->post->users_id) <!-- Verifica se o usuário não é o autor -->
                    <a class="button" href="{{ route('questions.edit-tags', $question) }}" id="btn-edit" title="Edit Tags">
                        <i class="fas fa-tags"></i>
                    </a>
                @endif
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

            <div class="edit-history-container">
            @auth
            @if(auth()->user()->id === $question->post->users_id || auth()->user()->hasRole('admin'))
            <button class="modal-button"> <i class="fas fa-history"></i> Edit History </button>
            @endif
            @endauth
            @if($question->post->editHistories->isNotEmpty())
                <p> Last edited on: {{ $question->post->editHistories->sortByDesc('date')->first()->date }}</p>
            @endif
            </div>


            <div class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    @if($question->post->editHistories->isEmpty())
                        <p>No edit history available.</p>
                    @else
                        <div class="edit-history">
                            <div class="dates">
                                @php
                                    $groupedHistories = $question->post->editHistories->sortByDesc('date')->groupBy(function($history) {
                                        return $history->date;
                                    });
                                @endphp
                                @foreach($groupedHistories as $date => $histories)
                                    @foreach($histories as $history)
                                        <div class="date-item" data-history-id="{{ $history->id }}">
                                           <h3> {{ $date }} </h3>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                            <div class="edit-history-details">
                                <p>Select an edit history to view details.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <div class="answers-section">
        <h2>Answers</h2>
        @if ($question->answers->isEmpty())
            <p>No answers available.</p>
        @else
            @php
                $answers = $question->answers->sortByDesc(function ($answer) use ($question) {
                    return $answer->posts_id === $question->answers_id ? 1 : 0;
                });
            @endphp

            @foreach ($answers as $answer)
                <div id="answer-{{ $answer->posts_id }}" class="answer-item">
                

                    @if ($question->answers_id === $answer->posts_id)
                        <p class="correct-answer"><i class="fa-solid fa-check" style="color: #209770;"></i> Correct Answer</p>
                        @can('update', $question)
                            <form action="{{ route('answers.unmarkAsCorrect', ['question' => $question->posts_id, 'answer' => $answer->posts_id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning">Unmark as Correct</button>
                            </form>
                        @endcan
                    @else
                        @can('update', $question)
                            @if (is_null($question->answers_id))
                                <form action="{{ route('answers.markAsCorrect', ['question' => $question->posts_id, 'answer' => $answer->posts_id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success">Mark as Correct</button>
                                </form>
                            @endif
                        @endcan
                    @endif
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

                    <p id="likes">
                        @if (Auth::check() && $answer->post->isLikedBy(Auth::user()))
                            <button class="btn btn-like like-btn btn-like-active" data-post-id="{{ $answer->post->id }}">
                                <i class="fas fa-thumbs-up"></i> <span class="like-count">{{ $answer->post->likesCount() }}</span>
                            </button>
                        @else
                            @can('like', $answer->post)
                            <button class="btn btn-like like-btn" data-post-id="{{ $answer->post->id }}">
                                <i class="far fa-thumbs-up"></i> <span class="like-count">{{ $answer->post->likesCount() }}</span>
                            </button>
                            @else
                            <button class="btn btn-like like-btn" data-post-id="{{ $answer->post->id }}" disabled>
                                <i class="far fa-thumbs-up"></i> <span class="like-count">{{ $answer->post->likesCount() }}</span>
                            </button>
                            @endcan
                        @endif

                        @if (Auth::check() && $answer->post->isDislikedBy(Auth::user()))
                            <button class="btn btn-like dislike-btn btn-like-active" data-post-id="{{ $answer->post->id }}">
                                <i class="fas fa-thumbs-down"></i> <span class="dislike-count">{{ $answer->post->dislikesCount() }}</span>
                            </button>
                        @else
                            @can('dislike', $answer->post)
                            <button class="btn btn-like dislike-btn" data-post-id="{{ $answer->post->id }}">
                                <i class="far fa-thumbs-down"></i> <span class="dislike-count">{{ $answer->post->dislikesCount() }}</span>
                            </button>
                            @else
                            <button class="btn btn-like dislike-btn" data-post-id="{{ $answer->post->id }}" disabled>
                                <i class="far fa-thumbs-down"></i> <span class="dislike-count">{{ $answer->post->dislikesCount() }}</span>
                            </button>
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

                    <div class="edit-history-container">
                    @auth
                    @if(auth()->user()->id === $answer->post->users_id || auth()->user()->hasRole('admin'))
                    <button class="modal-button"> <i class="fas fa-history"></i> Edit History </button>
                    @endif
                    @endauth
                    @if($answer->post->editHistories->isNotEmpty())
                        <p> Last edited on: {{ $answer->post->editHistories->sortByDesc('date')->first()->date }}</p>
                    @endif
                    </div>
                    <div class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            @if($answer->post->editHistories->isEmpty())
                                <p>No edit history available.</p>
                            @else
                                <div class="edit-history">
                                    <div class="dates">
                                        @php
                                            $groupedHistories = $answer->post->editHistories->sortByDesc('date')->groupBy(function($history) {
                                                return $history->date;
                                            });
                                        @endphp
                                        @foreach($groupedHistories as $date => $histories)
                                            @foreach($histories as $history)
                                                <div class="date-item" data-history-id="{{ $history->id }}">
                                                <h3> {{ $date }} </h3>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    </div>
                                    <div class="edit-history-details">
                                        <p>Select an edit history to view details.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if (!$answer->comments->isEmpty())
                        <h3>Comments</h3>
                        @foreach ($answer->comments as $comment)
                            <div id="comment-{{ $comment->id }}" class="comment-item">
                                <p>{{ $comment->content }}</p>
                                <p>Commented by:&nbsp;
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
                                <div class="edit-history-container">
                                @auth
                                @if(auth()->user()->id === $comment->users_id || auth()->user()->hasRole('admin'))
                                <button class="modal-button"> <i class="fas fa-history"></i> Edit History </button>
                                @endif
                                @endauth
                                @if($comment->editHistories->isNotEmpty())
                                    <p> Last edited on: {{ $comment->editHistories->sortByDesc('date')->first()->date }}</p>
                                @endif
                                </div>
                                <div class="modal">
                                    <div class="modal-content">
                                        <span class="close">&times;</span>
                                        @if($comment->editHistories->isEmpty())
                                            <p>No edit history available.</p>
                                        @else
                                            <div class="edit-history">
                                                <div class="dates">
                                                    @php
                                                        $groupedHistories = $comment->editHistories->sortByDesc('date')->groupBy(function($history) {
                                                            return $history->date;
                                                        });
                                                    @endphp
                                                    @foreach($groupedHistories as $date => $histories)
                                                        @foreach($histories as $history)
                                                            <div class="date-item" data-history-id="{{ $history->id }}">
                                                            <h3> {{ $date }} </h3>
                                                            </div>
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                                <div class="edit-history-details">
                                                    <p>Select an edit history to view details.</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
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
                <div id="comment-{{ $comment->id }}" class="question-comment-item">

                    <p>{{ $comment->content }}</p>
                    <p>Commented by:&nbsp;
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
                    <div class="edit-history-container">
                    @auth
                    @if(auth()->user()->id === $comment->users_id || auth()->user()->hasRole('admin'))
                    <button class="modal-button"> <i class="fas fa-history"></i> Edit History </button>
                    @endif
                    @if($comment->editHistories->isNotEmpty())
                        <p> Last edited on: {{ $comment->editHistories->sortByDesc('date')->first()->date }}</p>
                    @endif
                    @endauth
                    </div>
                    <div class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            @if($comment->editHistories->isEmpty())
                                <p>No edit history available.</p>
                            @else
                                <div class="edit-history">
                                    <div class="dates">
                                        @php
                                            $groupedHistories = $comment->editHistories->sortByDesc('date')->groupBy(function($history) {
                                                return $history->date;
                                            });
                                        @endphp
                                        @foreach($groupedHistories as $date => $histories)
                                            @foreach($histories as $history)
                                                <div class="date-item" data-history-id="{{ $history->id }}">
                                                <h3> {{ $date }} </h3>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    </div>
                                    <div class="edit-history-details">
                                        <p>Select an edit history to view details.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection

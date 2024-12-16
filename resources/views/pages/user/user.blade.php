@php
    use Carbon\Carbon;
@endphp
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
    

    <div class="profile-container">
        <div class="profile-sidebar">
            @php
                $profilePicture = $user->photo ? $user->photo : 'profilePictures/default.png';
            @endphp
            <img src="{{ asset($profilePicture) }}" alt="{{ $user->name }}'s Profile Picture" class="profile-picture">
            <h1>{{ $user->name }}'s Profile</h1>
            <p>Username: {{ $user->username }}</p>
            <p>Email: {{ $user->email }}</p>
            @if ($user->description)
            <p>Description: {{ $user->description }}</p>
            @endif
            <p>Score: {{ $user->score }}</p>

            @if ($user->tags->isNotEmpty())
                <p>
                    @foreach ($user->tags as $tag)
                        <a href="{{ route('tags.show', $tag->name) }}"> <span class="tag">#{{ $tag->name }}</span> </a>
                    @endforeach
                </p>
            @endif

            @if (auth()->check() && (auth()->user()->id === $user->id || auth()->user()->roles->contains('name', 'admin')))
                <a class="button" href="{{ route('edit-profile', $user->id) }}" class="btn btn-primary">Edit Profile</a>
            @endcan
            @if (auth()->check() && auth()->user()->id === $user->id)
                <form action="{{ route('users.autoDestroy', $user->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="button delete-account" onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')" title="Delete Account">
                        <i class="fas fa-user-times"></i> Delete Account
                    </button>
                </form>
            @endif
            @if (auth()->check() && auth()->user()->roles->contains('name', 'moderator') && auth()->user()->id === $user->id)
                <a class="button" href="{{ route('admin.dashboard') }}" class="btn btn-warning">Moderator Dashboard</a>
            @endif
            @if (auth()->check() && auth()->user()->roles->contains('name', 'admin') && auth()->user()->id === $user->id)
                <a class="button" href="{{ route('admin.dashboard') }}" class="btn btn-warning">Admin Dashboard</a>
            @endif
            @if (auth()->check() && auth()->user()->id === $user->id)
                <a class="button" href="{{ route('my.support.questions') }}" class="btn btn-primary">My Support Questions</a>
            @endif
            @if (!$badges->isEmpty())
            <section id="badges" class="tab-panel">
                <h2>Achievements</h2>
                <div class="all-badges">
                    @foreach ($badges as $badge)
                    <div class="badge-card">
                        <img src="{{ asset($badge->icon) }}" alt="{{ $badge->name }}'s Badge" class="badge-picture">
                        <div class="badge-tooltip">
                            <h3>{{ $badge->name }}</h3>
                            <p>{{ $badge->description }}</p>
                            <p>{{ date('Y-m-d', strtotime($badge->pivot->date)) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif
                
        </div>

        <div class="profile-content">
            <div class="tabset">
                <input type="radio" name="tabset" id="tab1" aria-controls="questions" checked>
                @if (auth()->check() && auth()->user()->id === $user->id)
                <label for="tab1">My Questions</label>
                @else
                <label for="tab1">Questions</label>
                @endif

                <input type="radio" name="tabset" id="tab2" aria-controls="answers">
                @if (auth()->check() && auth()->user()->id === $user->id)
                <label for="tab2">My Answers</label>
                @else
                <label for="tab2">Answers</label>
                @endif

                <input type="radio" name="tabset" id="tab3" aria-controls="comments">
                @if (auth()->check() && auth()->user()->id === $user->id)
                <label for="tab3">My Comments</label>
                @else
                <label for="tab3">Comments</label>
                @endif

                <input type="radio" name="tabset" id="tab4" aria-controls="votes">
                @if (auth()->check() && auth()->user()->id === $user->id)
                <label for="tab4">My Votes</label>
                @endif
                
                <div class="tab-panels">
                    <section id="questions" class="tab-panel">
                        <h2>Questions</h2>
                        <div class="all-questions">
                        @if ($questions->isEmpty())
                            <p>No questions available.</p>
                        @else
                        @foreach ($questions as $question)
                            <div class="question-card">
                                <h3>{{ $question->title }}</h3>
                                <p>{{ $question->post->content }}</p>
                                <p>Date: {{ Carbon::parse($question->post->date)->diffForHumans() }}</p>
                                <a class="read_more" href="{{ route('questions.show', $question->posts_id) }}" class="btn btn-secondary">Read More</a>
                            </div>
                        @endforeach
                        @endif
                        </div>
                        <div class="pagination">
                            {{ $questions->appends(['answers_page' => $answers->currentPage(), 'comments_page' => $comments->currentPage(), 'liked_questions_page' => $likedQuestions->currentPage(), 'liked_answers_page' => $likedAnswers->currentPage(), 'disliked_questions_page' => $dislikedQuestions->currentPage(), 'disliked_answers_page' => $dislikedAnswers->currentPage()])->links() }}
                        </div>
                    </section>
                    <section id="answers" class="tab-panel">
                        <h2>Answers</h2>
                        <div class="all-questions">
                        @if ($answers->isEmpty())
                            <p>No answers available.</p>
                        @else
                        @foreach ($answers as $answer)
                            <div class="answer-card">
                                <p>{{ $answer->content }}</p>
                                <p>Date: {{ Carbon::parse($answer->date)->diffForHumans() }}</p>
                                <a class="button" href="{{ route('questions.show', $answer->answer->questions_id) }}#answer-{{ $answer->id }}" id="btn-edit" title="Details">
                                    <i class="fas fa-book-open"></i>
                                </a>

                                @if (auth()->check() && auth()->user()->id === $answer->users_id)
                                    <a class="button" href="{{ route('answers.edit', $answer->id) }}" id="btn-edit" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                @endif

                                @if (auth()->check() && (auth()->user()->roles->contains('name', 'admin') || auth()->user()->id === $user->id))
                                    <form action="{{ route('answers.destroy', $answer->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this Answer?')" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                        @endif
                        </div>
                        <div class="pagination">
                            {{ $answers->appends(['questions_page' => $questions->currentPage(), 'comments_page' => $comments->currentPage(), 'liked_questions_page' => $likedQuestions->currentPage(), 'liked_answers_page' => $likedAnswers->currentPage(), 'disliked_questions_page' => $dislikedQuestions->currentPage(), 'disliked_answers_page' => $dislikedAnswers->currentPage()])->links() }}
                        </div>
                    </section>
                    <section id="comments" class="tab-panel">
                    <h2>Comments</h2>
                    <div class="all-questions">
                    @if ($comments->isEmpty())
                        <p>No comments available.</p>
                    @else
                    @foreach ($comments as $comment)
                        <div class="answer-card">
                            <p>{{ $comment->content }}</p>
                            <p>Date: {{ Carbon::parse($comment->date)->diffForHumans() }}</p>
                            @if($comment->question!=null)
                                <a class="button" href="{{ route('questions.show', $comment->question->posts_id) }}#comment-{{ $comment->id }}" id="btn-edit" title="Details">
                                <i class="fas fa-book-open"></i>
                                </a>
                            @elseif($comment->answer!=null)
                                <a class="button" href="{{ route('questions.show', $comment->answer->questions_id) }}#comment-{{ $comment->id }}" id="btn-edit" title="Details">
                                <i class="fas fa-book-open"></i>
                                </a>
                            @endif
                            @can('update', $comment)
                                <a class="button" href="{{ route('comments.edit', $comment) }}" id="btn-edit" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
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
                        </div>
                    @endforeach
                    @endif
                    </div>
                    <div class="pagination">
                        {{ $comments->appends(['questions_page' => $questions->currentPage(), 'answers_page' => $answers->currentPage(), 'liked_questions_page' => $likedQuestions->currentPage(), 'liked_answers_page' => $likedAnswers->currentPage(), 'disliked_questions_page' => $dislikedQuestions->currentPage(), 'disliked_answers_page' => $dislikedAnswers->currentPage()])->links() }}
                    </div>
                    </section>
                    <section id="votes" class="tab-panel">
            <h2>My Votes</h2>
                <div class="all-questions">
                    <h3>Likes</h3>
                    <h4>Questions</h4>
                    @if ($likedQuestions->isEmpty())
                        <p>No liked questions available.</p>
                    @else
                        @foreach ($likedQuestions as $question)
                            <div class="question-card">
                                <h3>{{ $question->title }}</h3>
                                <p>{{ $question->post->content }}</p>
                                <p>Date: {{ Carbon::parse($question->post->date)->diffForHumans() }}</p>
                                <a class="read_more" href="{{ route('questions.show', $question->posts_id) }}" class="btn btn-secondary">Read More</a>
                            </div>
                        @endforeach
                    @endif
                    <div class="pagination">
                        {{ $likedQuestions->appends(['questions_page' => $questions->currentPage(), 'answers_page' => $answers->currentPage(), 'comments_page' => $comments->currentPage(), 'liked_answers_page' => $likedAnswers->currentPage(), 'disliked_questions_page' => $dislikedQuestions->currentPage(), 'disliked_answers_page' => $dislikedAnswers->currentPage()])->links() }}
                    </div>
                    <h4>Answers</h4>
                    @if ($likedAnswers->isEmpty())
                        <p>No liked answers available.</p>
                    @else
                        @foreach ($likedAnswers as $answer)
                            <div class="answer-card">
                                <p>{{ $answer->post->content }}</p>
                                <p>Date: {{ Carbon::parse($answer->post->date)->diffForHumans() }}</p>
                                <a class="button" href="{{ route('questions.show', $answer->questions_id) }}#answer-{{ $answer->posts_id }}" id="btn-edit" title="Details">
                                    <i class="fas fa-book-open"></i>
                                </a>
                            </div>
                        @endforeach
                    @endif
                    <div class="pagination">
                        {{ $likedAnswers->appends(['questions_page' => $questions->currentPage(), 'answers_page' => $answers->currentPage(), 'comments_page' => $comments->currentPage(), 'liked_questions_page' => $likedQuestions->currentPage(), 'disliked_questions_page' => $dislikedQuestions->currentPage(), 'disliked_answers_page' => $dislikedAnswers->currentPage()])->links() }}
                    </div>
                </div>
                <div class="all-questions">
                    <h3>Dislikes</h3>
                    <h4>Questions</h4>
                    @if ($dislikedQuestions->isEmpty())
                        <p>No disliked questions available.</p>
                    @else
                        @foreach ($dislikedQuestions as $question)
                            <div class="question-card">
                                <h3>{{ $question->title }}</h3>
                                <p>{{ $question->post->content }}</p>
                                <p>Date: {{ Carbon::parse($question->post->date)->diffForHumans() }}</p>
                                <a class="read_more" href="{{ route('questions.show', $question->posts_id) }}" class="btn btn-secondary">Read More</a>
                            </div>
                        @endforeach
                    @endif
                    <div class="pagination">
                        {{ $dislikedQuestions->appends(['questions_page' => $questions->currentPage(), 'answers_page' => $answers->currentPage(), 'comments_page' => $comments->currentPage(), 'liked_questions_page' => $likedQuestions->currentPage(), 'liked_answers_page' => $likedAnswers->currentPage(), 'disliked_answers_page' => $dislikedAnswers->currentPage()])->links() }}
                    </div>
                    <h4>Answers</h4>
                    @if ($dislikedAnswers->isEmpty())
                        <p>No disliked answers available.</p>
                    @else
                        @foreach ($dislikedAnswers as $answer)
                            <div class="answer-card">
                                <p>{{ $answer->post->content }}</p>
                                <p>Date: {{ Carbon::parse($answer->post->date)->diffForHumans() }}</p>
                                <a class="button" href="{{ route('questions.show', $answer->questions_id) }}#answer-{{ $answer->posts_id }}" id="btn-edit" title="Details">
                                    <i class="fas fa-book-open"></i>
                                </a>
                            </div>
                        @endforeach
                    @endif
                    <div class="pagination">
                        {{ $dislikedAnswers->appends(['questions_page' => $questions->currentPage(), 'answers_page' => $answers->currentPage(), 'comments_page' => $comments->currentPage(), 'liked_questions_page' => $likedQuestions->currentPage(), 'liked_answers_page' => $likedAnswers->currentPage(), 'disliked_questions_page' => $dislikedQuestions->currentPage()])->links() }}
                    </div>
                </div>
        </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
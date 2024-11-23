@extends('layouts.app')

@section('content')
@if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
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
            <p>Description: {{ $user->description }}</p>
            <p>Score: {{ $user->score }}</p>

            @can ('editUser', $user)
                <a class="button" href="{{ route('edit-profile') }}" class="btn btn-primary">Edit Profile</a>
            @endcan
            @if (auth()->check() && auth()->user()->roles->contains('name', 'admin'))
                <a class="button" href="{{ route('admin.dashboard') }}" class="btn btn-warning">Admin Dashboard</a>
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
                                <p>Date: {{ $question->post->date }}</p>
                                <a class="read_more" href="{{ route('questions.show', $question->posts_id) }}" class="btn btn-secondary">Read More</a>
                            </div>
                        @endforeach
                        @endif
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
                                <p>Date: {{ $answer->date }}</p>

                                @if (auth()->check() && auth()->user()->id === $answer->users_id)
                                    <a class="button" href="{{ route('answers.edit', $answer->id) }}" class="btn btn-danger">Edit Answer</a>
                                    <form action="{{ route('answers.destroy', $answer->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete Answer</button>
                                    </form>
                                @endif

                            </div>
                        @endforeach
                        @endif
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
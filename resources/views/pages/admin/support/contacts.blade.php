@extends('layouts.app')

@section('content')
<div class="container support-questions-section">
    <h2>Support Contacts</h2>
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
    @forelse($supportQuestions as $question)
        <div class="card support-question-item">
            <div class="card-header">
                <strong>{{ $question->user->name }}</strong> 
                <span class="text-muted">{{ \Carbon\Carbon::parse($question->date)->format('d/m/Y H:i') }}</span>
                <strong class="badge {{ $question->solved ? 'badge-solved' : 'badge-unsolved' }}">
                    {{ $question->solved ? 'Solved' : 'Unsolved' }}
                </strong>
            </div>
            <div class="card-body">
                <p>{{ $question->content }}</p>
            </div>
            @if(!$question->answers->isEmpty())
            <div class="answers-section">
                @foreach($question->answers as $answer)
                    <div class="answer-item">
                        <strong>Answer by {{ $answer->user->name }}:</strong>
                        <p>{{ $answer->content }}</p>
                    </div>
                @endforeach
            </div>
            @endif
            <p></p>
            <div class="answer-item">
                <form action="{{ route('support-questions.answer') }}" method="POST">
                    @csrf
                    <input type="hidden" name="support_question_id" value="{{ $question->id }}">
                    <div class="form-group">
                        <label for="content">Your Answer
                            <div class="tooltip">
                                <i class="fas fa-info-circle"></i>
                                <span class="tooltip-text">Maximum 1000 characters</span>
                            </div>
                        </label>
                        <textarea name="content" id="content" class="form-control" rows="2" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Submit Answer</button>
                </form>
            </div>
            @if(!$question->solved)
            <div class="solve-item mt-2">
                <form action="{{ route('support-questions.solve', $question->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to mark this question as solved?')">Mark as Solved</button>
                </form>
            @endif
        </div>
    @empty
        <div class="alert alert-info">No support contacts found.</div>
    @endforelse

    <div class="pagination-container">
        {{ $supportQuestions->links() }}
    </div>
</div>
@endsection
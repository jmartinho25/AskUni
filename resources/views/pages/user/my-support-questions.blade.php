
@extends('layouts.app')

@section('content')
<div class="container support-questions-section">
    <h2>My Support Questions</h2>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if ($supportQuestions->isEmpty())
        <h4> No questions available. </h4>
    @else
    @forelse($supportQuestions as $question)
        <div class="card support-question-item">
            <div class="card-header">
                <strong>{{ $question->user->name }}</strong> 
                <span class="text-muted">{{ \Carbon\Carbon::parse($question->date)->format('d/m/Y H:i') }}</span>
                <span class="badge {{ $question->solved ? 'badge-solved' : 'badge-unsolved' }}">
                    {{ $question->solved ? 'Solved' : 'Unsolved' }}
                </span>
            </div>
            <div class="card-body">
                <p>{{ $question->content }}</p>
                @if (!$question->solved && Auth::id() == $question->users_id)
                    <form action="{{ route('support-questions.solve', $question->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to mark this question as solved?')" title="Mark as Solved">
                            <i class="fas fa-check"></i> Mark as Solved
                        </button>
                    </form>
                @endif
            </div>
            @if (!$question->answers->isEmpty())
            <div class="answers-section">
                @foreach($question->answers as $answer)
                    <div class="answer-item">
                        <strong>Answer by {{ $answer->user->name }}:</strong>
                        <p>{{ $answer->content }}</p>
                    </div>
                @endforeach
            </div>
            @endif
        </div>
    @empty
        <div class="alert alert-info">No support contacts found.</div>
    @endforelse
    <div class="pagination-container">
        {{ $supportQuestions->links() }}
    </div>
    @endif
</div>

@endsection
@extends('layouts.app')

@section('content')
<div class="container support-questions-section">
    <h2>Support Contacts</h2>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @forelse($supportQuestions as $question)
        <div class="card support-question-item">
            <div class="card-header">
                <strong>{{ $question->user->name }}</strong> 
                <strong class="text-muted">{{ \Carbon\Carbon::parse($question->date)->format('d/m/Y H:i') }}</strong>
                <strong class="badge {{ $question->solved ? 'badge-solved' : 'badge-unsolved' }}">
                    {{ $question->solved ? 'Solved' : 'Unsolved' }}
                </strong>
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
            <div class="answers-section">
                @foreach($question->answers as $answer)
                    <div class="answer-item">
                        <strong>Answer by {{ $answer->user->name }}:</strong>
                        <p>{{ $answer->content }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="alert alert-info">No support contacts found.</div>
    @endforelse
</div>
@endsection
@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Answer to: {{ $question->title }}</h2>

        <form action="{{ route('answers.store', $question->posts_id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="content">Answer Content</label>
                <textarea id="content" name="content" class="form-control" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Answer</button>
        </form>
        
        <a href="{{ route('questions.show', $question->posts_id) }}" class="btn btn-secondary mt-3">Back to Question</a>
    </div>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>{{ $question->title }}</h2>
        <p><strong>Content:</strong> {{ $question->content }}</p>
        <p><strong>Created At:</strong> {{ $question->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Updated At:</strong> {{ $question->updated_at->format('d/m/Y H:i') }}</p>

        <a href="{{ route('questions.index') }}" class="btn btn-secondary">Back to Questions</a>
    </div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add FAQ</h1>

    <form action="{{ route('faq.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="question" class="form-label">Question
                <div class="tooltip">
                    <i class="fas fa-info-circle"></i>
                    <span class="tooltip-text">Maximum 255 characters</span>
                </div>
            </label>
            <input type="text" class="form-control" id="question" name="question" required>
        </div>
        <div class="mb-3">
            <label for="answer" class="form-label">Answer</label>
            <textarea class="form-control" id="answer" name="answer" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add FAQ</button>
    </form>
</div>
@endsection
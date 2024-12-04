@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit FAQ</h1>

    <form action="{{ route('faq.update', $faq->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="question" class="form-label">Question</label>
            <input type="text" class="form-control" id="question" name="question" value="{{ $faq->question }}" required>
        </div>
        <div class="mb-3">
            <label for="answer" class="form-label">Answer</label>
            <textarea class="form-control" id="answer" name="answer" rows="5" required>{{ $faq->answer }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update FAQ</button>
    </form>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container support-questions-section">
    <h2>Contact Support</h2>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="card mb-3">
        <div class="card-body">
            <form action="{{ route('support-questions.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="content">Your Question</label>
                    <textarea name="content" id="content" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection
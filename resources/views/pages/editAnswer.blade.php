@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Answer</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('answers.update', $answer) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="posts_id" value="{{ $answer->posts_id }}">

        <div class="form-group">
            <label for="content">Content</label>
            <textarea name="content" id="content" class="form-control" required>{{ old('content', $answer->post->content) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Answer</button>
    </form>
</div>
@endsection
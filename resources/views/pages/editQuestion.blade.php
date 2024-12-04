@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Question</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('questions.update', $question) }}" method="POST">
        @csrf
        @method('PUT')

        <input type="hidden" name="posts_id" value="{{ $question->posts_id }}">

        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $question->title) }}" required>
        </div>

        <div class="form-group">
            <label for="content">Content</label>
            <textarea name="content" id="content" class="form-control" required>{{ old('content', $question->post->content) }}</textarea>
        </div>

        <div class="form-group" id="question-tags">
            <label for="tags">Tags</label>
            <select name="tags[]" id="tags" class="form-control" multiple>
                @foreach($allTags as $tag)
                    <option value="{{ $tag->id }}" {{ in_array($tag->id, $question->tags->pluck('id')->toArray()) ? 'selected' : '' }}>
                        {{ $tag->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Question</button>
    </form>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Tags for Question: {{ $question->title }}</h1>

    <form action="{{ route('questions.update-tags', $question->posts_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="tags" class="form-label">Tags
                <div class="tooltip">
                    <i class="fas fa-info-circle"></i>
                    <span class="tooltip-text">Maximum 5 tags</span>
                </div>
            </label>
            <select multiple class="form-control" id="tags" name="tags[]">
                @foreach($allTags as $tag)
                    <option value="{{ $tag->id }}" {{ $question->tags->contains($tag->id) ? 'selected' : '' }}>
                        {{ $tag->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Tags</button>
    </form>
</div>
@endsection
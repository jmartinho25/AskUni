<!-- resources/views/questions/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create New Question</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="create-question-form" action="{{ route('questions.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-group" id="question-tags">
                <label for="tags">Tags</label>
                <select name="tags[]" id="tags" class="form-control" multiple>
                    @foreach($allTags as $tag)
                        <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" id="create-question-submit-button" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection

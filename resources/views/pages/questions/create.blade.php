<!-- resources/views/questions/create.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create New Question</h2>

        <form action="{{ route('questions.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" class="form-control" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <input type="datetime-local" id="date" name="date" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection

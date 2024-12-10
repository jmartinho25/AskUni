@extends('layouts.app')

@section('content')
<div class="container about-us-edit-container">
    <h1>Edit About Us Section</h1>

    <form action="{{ route('aboutUs.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="10" required>{{ $content }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Section</button>
    </form>
</div>
@endsection
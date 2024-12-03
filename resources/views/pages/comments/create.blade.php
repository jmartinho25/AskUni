@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Comment</h1>
    <form action="{{ route('comments.store', [$type, $parent->posts_id]) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="content">Comment</label>
            <textarea name="content" id="content" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
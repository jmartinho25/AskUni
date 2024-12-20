@extends('layouts.no-navbar')

@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
<div class="container">
    
    <h1>Appeal for Unblock</h1>

    <form action="{{ route('appealForUnblock.store') }}" method="POST">
        @csrf
        <label for="content">Appeal Content:</label>
        <textarea name="content" id="content" class="form-control" placeholder="Enter your appeal content" required></textarea>
        <button type="submit" class="btn btn-primary">Submit Appeal</button>
    </form>
</div>
@endsection
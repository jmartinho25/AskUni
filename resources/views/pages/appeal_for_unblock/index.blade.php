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
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="content">Appeal Content</label>
            <textarea id="content" name="content" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit Appeal</button>
    </form>
</div>
@endsection
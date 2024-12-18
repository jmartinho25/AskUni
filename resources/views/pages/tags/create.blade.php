@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Tag</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tags.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Name
                <div class="tooltip">
                    <i class="fas fa-info-circle"></i>
                    <span class="tooltip-text">Maximum 255 characters</span>
                </div>
            </label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="category">Category
                <div class="tooltip">
                    <i class="fas fa-info-circle"></i>
                    <span class="tooltip-text">Maximum 255 characters</span>
                </div>
            </label>
            <input type="text" name="category" id="category" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="about">About</label>
            <textarea name="about" id="about" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <label for="picture">Picture</label>
            <input type="file" name="picture" id="picture" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Save Tag</button>
    </form>
</div>
@endsection
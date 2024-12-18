@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Tag</h1>

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

    <form action="{{ route('tags.update', $tag->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name
                <div class="tooltip">
                    <i class="fas fa-info-circle"></i>
                    <span class="tooltip-text">Maximum 255 characters</span>
                </div>
            </label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $tag->name }}" required>
        </div>
        <div class="form-group">
            <label for="category">Category
                <div class="tooltip">
                    <i class="fas fa-info-circle"></i>
                    <span class="tooltip-text">Maximum 255 characters</span>
                </div>
            </label>
            <input type="text" name="category" id="category" class="form-control" value="{{ $tag->category }}" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control">{{ $tag->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="about">About</label>
            <textarea name="about" id="about" class="form-control">{{ $tag->about }}</textarea>
        </div>
        <div class="form-group">
            <label for="picture">Picture</label>
            <input type="file" name="picture" id="picture" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update Tag</button>
    </form>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Manage Tags</h1>

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
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="category">Category</label>
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
        <button type="submit" class="btn btn-primary">Create Tag</button>
    </form>

    <hr>

    <h2>Existing Tags</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>About</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tags as $tag)
                <tr>
                    <td>{{ $tag->name }}</td>
                    <td>{{ $tag->category }}</td>
                    <td>{{ $tag->description }}</td>
                    <td>{{ $tag->about }}</td>
                    <td>
                        <form action="{{ route('tags.update', $tag->id) }}" method="POST" enctype="multipart/form-data" style="display:inline;">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $tag->name }}" required>
                            <input type="text" name="category" value="{{ $tag->category }}" required>
                            <textarea name="description">{{ $tag->description }}</textarea>
                            <textarea name="about">{{ $tag->about }}</textarea>
                            <input type="file" name="picture">
                            <button type="submit" class="btn btn-warning">Update</button>
                        </form>
                        <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this tag?')" title="Delete">
                                <i class="fas fa-trash-alt"></i> 
                            </button>

                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
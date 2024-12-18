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

    <a href="{{ route('tags.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus"></i> Create Tag
    </a>

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
                    <td >
                    <div style="display: inline-flex; align-items: center; gap: 8px;">
                    <a href="{{ route('tags.edit', $tag->id) }}"  class="btn btn-edit" title="Edit" >
                            <i class="fas fa-pencil-alt"></i>
                    </a>
                    <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this tag?')" title="Delete" style="display:inline-block;">
                                <i class="fas fa-trash-alt"></i> 
                            </button>
                    </form>
                    </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@extends('layouts.app')

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
    <h1 id="explore-tags">Explore Tags</h1>

    @can('manage', App\Models\Tag::class)
        <a href="{{ route('tags.manage') }}" class="btn btn-primary mb-3">Manage Tags</a>
    @endcan

    @foreach($tags as $category => $categoryTags)
        <h2>{{ $category }}</h2>
        <div class="tag-container">
            @foreach($categoryTags as $tag)
                <a href="{{ route('tags.show', $tag->name) }}" class="tag-card">
                    <div>
                        <h3>{{ $tag->name }}</h3>
                        <p>{{ $tag->description }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    @endforeach
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 id="explore-tags">Explore Tags</h1>
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
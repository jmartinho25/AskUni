@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Tags</h1>
    <ul>
        @foreach($tags as $tag)
            <li><a href="{{ route('tags.show', $tag->name) }}">{{ $tag->name }}</a></li>
        @endforeach
    </ul>
</div>
@endsection
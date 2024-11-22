@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Search Results</h1>
    @if($results->isEmpty())
        <p>No results found for "{{ $query }}".</p>
    @else
        <ul>
            @foreach($results as $result)
                <li>
                    <a href="{{ route('questions.show', $result->posts_id) }}">{{ $result->title }}</a>
                </li>
            @endforeach
        </ul>
        <div class="pagination">
            {{ $results->appends(['query' => $query, 'exact_match' => request()->input('exact_match')])->links() }}
        </div>
    @endif
</div>
@endsection
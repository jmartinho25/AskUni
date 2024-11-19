<!-- resources/views/questions/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Lista de Perguntas</h2>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <ul>
            @foreach($questions as $question)
                <li>
                    <a href="{{ route('questions.show', $question) }}">{{ $question->title }}</a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection

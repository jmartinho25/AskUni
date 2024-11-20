@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Lista de Perguntas</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="add-question mb-3">
            <a href="{{ route('questions.create') }}">
                <button>Adicionar Pergunta</button>
            </a>
        </div>

        <ul>
            @foreach($questions as $question)
                <li>
                    <a href="{{ route('questions.show', $question) }}">{{ $question->title }}</a><br>
                    <small>Publicado em: {{ $question->created_at->format('d/m/Y H:i') }}</small>
                </li>
            @endforeach
        </ul>

        <div class="pagination">
            {{ $questions->links() }}
        </div>
    </div>
@endsection

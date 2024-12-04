@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{ route('send.email') }}" method="POST">
        @csrf
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <button type="submit">Send</button>
    </form>
</div>
@endsection
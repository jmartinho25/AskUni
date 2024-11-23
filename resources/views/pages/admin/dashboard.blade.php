@extends('layouts.app')

@section('content')
@if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
@endif
<div class="container">


    <h1>Admin Dashboard</h1>
    <p>Welcome, {{ Auth::user()->name }}! Here you can manage the platform.</p>

    <h2>Users</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                        Delete
                    </button>
                </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection

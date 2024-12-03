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
            <tr @if($user->deleted_at) class="table-danger" @endif>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if($user->deleted_at)
                        <span class="text-danger">Deleted</span>
                        <form action="{{ route('users.restore', $user->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to restore this user?')">
                                Restore
                            </button>
                        </form>
                    @else
                        <!-- Check if the logged-in user is an admin and if they are trying to delete themselves -->
                        @if(!$user->hasRole('admin') && Auth::user()->id != $user->id)
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this User?')">
                                        <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        @else
                            <span class="text-muted">Cannot delete admin</span>
                        @endif
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection

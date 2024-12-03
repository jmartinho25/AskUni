@extends('layouts.app')

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container">
    <h1>Admin Dashboard</h1>
    <p>Welcome, {{ Auth::user()->name }}! Here you can manage the platform.</p>

    <h2>Users</h2>

    <!-- Formulário de Pesquisa -->
    <form action="{{ route('admin.dashboard') }}" method="GET" id="user-search-bar">
        <input type="text" name="query" id="user-search-input" value="{{ $query ?? '' }}" placeholder="Search users...">
        <button type="submit" id="user-search-button">
            <i class="fa fa-search"></i> Search
        </button>
    </form>

    <!-- Exibição dos resultados -->
    @if(isset($query))
        <p>Results for: "{{ $query }}"</p>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No users found for your search.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination">
        {{ $users->appends(['query' => $query ?? ''])->links() }}
    </div>
</div>

@endsection

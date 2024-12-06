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

    <h1>Admin Dashboard</h1>
    <p>Welcome, {{ Auth::user()->name }}! Here you can manage the platform.</p>

    <a href="{{ route('admin.unblock.requests') }}" class="btn btn-primary mb-3">
        <i class="fas fa-unlock"></i> View Unblock Requests
    </a>
    <p></p>
    <a href="{{ route('admin.support.contacts') }}" class="btn btn-primary" title="View Support Contacts">
        <i class="fas fa-headset"></i> View Support Contacts
    </a>

    <h2>Users</h2>

    <a href="{{ route('admin.unblock.requests') }}" class="btn btn-primary mb-3">View Unblock Requests</a>

    <form action="{{ route('admin.dashboard') }}" method="GET" id="user-search-bar">
        <input type="text" name="query" id="user-search-input" value="{{ $query ?? '' }}" placeholder="Search users...">
        <button type="submit" id="user-search-button">
            <i class="fa fa-search"></i> Search
        </button>
    </form>

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
        <tbody id="users-table-body">
            @forelse($users as $user)
                <tr @if($user->deleted_at) class="table-danger" @endif>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->deleted_at)
                            <span class="text-danger">Deleted</span>
                            <form action="{{ route('users.restore', $user->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to restore this user?')" title="Restore User">
                                    <i class="fas fa-undo"></i>
                                </button>
                            </form>
                        @else
                            @if(!$user->hasRole('admin') && Auth::user()->id != $user->id)
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?')" title="Delete User">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>

                                <!-- Botão de elevação a admin -->
                                <form action="{{ route('admin.users.elevate', $user->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to elevate this user to admin?')" title="Elevate to Admin">
                                        <i class="fas fa-user-shield"></i>
                                    </button>
                                </form>
                            @else
                                @if($user->hasRole('admin'))
                                    <span class="text-muted">Admin</span>
                                @else
                                    <span class="text-muted">Cannot modify yourself</span>
                                @endif
                            @endif
                        @endif
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="3">No users found for your search.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Paginação -->
    <div id="pagination-container-user">
        {{ $users->appends(['query' => $query])->links() }}
    </div>

</div>


@endsection
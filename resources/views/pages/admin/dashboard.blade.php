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
    <p></p>
    <a href="{{ route('admin.reported.content') }}" class="btn btn-primary" title="View Reported Content">
        <i class="fas fa-exclamation-triangle"></i> View Reported Content
    </a>

    @if(Auth::user()->hasRole('admin'))
        <h2>Users</h2>

        <form action="{{ route('admin.dashboard') }}" method="GET" id="user-search-bar">
            <input type="text" name="query" id="user-search-input" value="{{ $query ?? '' }}" placeholder="Search users...">
            <button type="submit" id="user-search-button" class="btn btn-primary">
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
                    <th>Status</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="users-table-body">
                @forelse($users as $user)
                    <tr @if($user->deleted_at) class="table-danger" @endif>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->is_blocked)
                                <span class="text-danger">Blocked</span>
                            @else
                                <span class="text-success">Active</span>
                            @endif
                        </td>
                        <td>
                            @if($user->hasRole('admin'))
                                <span class="text-primary">Admin</span>
                            @elseif($user->hasRole('moderator'))
                                <span class="text-info">Moderator</span>
                            @else
                                <span class="text-secondary">User</span>
                            @endif
                        </td>
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

                                    @if(!$user->hasRole('admin'))
                                        <form action="{{ route('admin.users.elevate', $user->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to elevate this user to admin?')" title="Elevate to Admin">
                                                <i class="fas fa-user-shield"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if(!$user->hasRole('moderator') && !$user->hasRole('admin'))
                                        <form action="{{ route('admin.users.elevate.moderator', $user->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-info" onclick="return confirm('Are you sure you want to elevate this user to moderator?')" title="Elevate to Moderator">
                                                <i class="fas fa-user-tie"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if($user->hasRole('moderator'))
                                        <form action="{{ route('admin.users.demote.moderator', $user->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to demote this user from moderator?')" title="Demote from Moderator">
                                                <i class="fas fa-user-minus"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if(!$user->is_blocked)
                                        <form action="{{ route('admin.users.block', $user->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-warning alert-background-warning" onclick="return confirm('Are you sure you want to block this user?')" title="Block User">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif

                                    @if($user->is_blocked)
                                        <form action="{{ route('admin.users.unblock', $user->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to unblock this user?')" title="Unblock User">
                                                <i class="fas fa-unlock"></i>
                                            </button>
                                        </form>
                                    @endif

                                    
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
                        <td colspan="5">No users found for your search.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div id="pagination-container-user">
            {{ $users->appends(['query' => $query])->links() }}
        </div>
    @endif

</div>
@endsection
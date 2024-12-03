@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Unblock Requests</h1>

    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Content</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($unblockRequests as $request)
                <tr>
                    <td>{{ $request->user->name }}</td>
                    <td>{{ $request->content }}</td>
                    <td>
                        <form action="{{ route('admin.users.unblock', $request->user->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to unblock this user?')">
                                Unblock
                            </button>
                        </form>
                        <a href="{{ route('admin.user.reports', $request->user->id) }}" class="btn btn-info" style="display:inline-block;">
                            View Reports
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No unblock requests found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Paginação -->
    <div class="pagination-container">
        {{ $unblockRequests->links() }}
    </div>

</div>
@endsection
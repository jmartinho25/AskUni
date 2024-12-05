@extends('layouts.app')

@section('content')
<div class="container">

    <h1>Reports for {{ $user->name }}</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Report Reason</th>
                <th>Date</th>
                <th>Solved</th>
                <th>Comment</th>
                <th>Post</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $report)
                <tr>
                    <td>{{ $report->report_reason }}</td>
                    <td>{{ $report->date }}</td>
                    <td>{{ $report->solved ? 'Yes' : 'No' }}</td>
                    <td>{{ optional($report->comment)->content }}</td>
                    <td>{{ optional($report->post)->content }}</td>
                    <td>
                        @if(!$report->solved)
                            <form action="{{ route('admin.reports.resolve', $report->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-solve" onclick="return confirm('Are you sure you want to mark this report as resolved?')" title="Solve">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        @else
                            <span class="text-muted">Resolved</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No reports found for this user.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-container">
        {{ $reports->links() }}
    </div>

    <form action="{{ route('admin.unblock.requests') }}" method="GET" style="display:inline-block;">
        @csrf
        <button type="submit" id="btn-edit">
            <i class="fas fa-arrow-circle-left"></i> Back
        </button>
    </form>

</div>
@endsection
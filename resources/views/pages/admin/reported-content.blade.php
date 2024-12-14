@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Reported Content</h1>

    <form action="{{ route('admin.reported.content') }}" method="GET" id="user-search-bar">
        <input type="text" name="query" id="user-search-input" value="{{ $query ?? '' }}" placeholder="Search users...">
        <button type="submit" id="user-search-button" class="btn btn-primary">
            <i class="fa fa-search"></i> Search
        </button>
    </form>

    @if($reportedContent->isEmpty())
        <p>No reported content available.</p>
    @else
        @php
            $groupedReports = $reportedContent->groupBy(function($report) {
                return $report->post ? $report->post->user_id : ($report->comment ? $report->comment->user_id : null);
            });

            // Ordenar os grupos por ordem alfabética do nome do usuário
            $groupedReports = $groupedReports->sortBy(function($reports, $userId) {
                $user = $reports->first()->post ? $reports->first()->post->user : ($reports->first()->comment ? $reports->first()->comment->user : null);
                return $user ? $user->name : 'Deleted User';
            });
        @endphp

        @foreach($groupedReports as $userId => $reports)
            @php
                $user = $reports->first()->post ? $reports->first()->post->user : ($reports->first()->comment ? $reports->first()->comment->user : null);
            @endphp

            <div class="user-reports">
                <h2>Reports for {{ $user ? $user->name : 'Deleted User' }}</h2>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>Content</th>
                            <th>Reported For</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                            <tr>
                                <td>{{ $report->post ? 'Post' : 'Comment' }}</td>
                                <td>{{ $report->post ? Str::limit($report->post->content, 50) : Str::limit($report->comment->content, 50) }}</td>
                                <td>
                                    @if ($report->post && $report->post->user)
                                        {{ $report->post->user->name }}
                                    @elseif ($report->comment && $report->comment->user)
                                        {{ $report->comment->user->name }}
                                    @else
                                        <span>Deleted User</span>
                                    @endif
                                </td>
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @endif
</div>
@endsection
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
                                <td>
                                @if($report->post)
                                    @if($report->post->question)
                                        <a href="{{ route('questions.show', $report->post->question->posts_id) }}">
                                            {{ Str::limit($report->post->content, 50) }}
                                        </a>
                                    @elseif($report->post->answer)
                                        <a href="{{ route('questions.show', $report->post->answer->questions_id) }}#answer-{{ $report->post->id }}">
                                            {{ Str::limit($report->post->content, 50) }}
                                        </a>
                                    @endif
                                @elseif($report->comment)
                                    @if($report->comment->question)
                                        <a href="{{ route('questions.show', $report->comment->question->posts_id) }}#comment-{{ $report->comment->id }}">
                                            {{ Str::limit($report->comment->content, 50) }}
                                        </a>
                                    @elseif($report->comment->answer)
                                        <a href="{{ route('questions.show', $report->comment->answer->questions_id) }}#comment-{{ $report->comment->id }}">
                                            {{ Str::limit($report->comment->content, 50) }}
                                        </a>
                                    @endif
                                @else
                                    <span class="text-muted">No post</span>
                                @endif
                                </td>
                                <td>
                                    @if ($report->post && $report->post->user)
                                        <a href="{{ route('profile', $report->post->user->id) }}">{{ $report->post->user->name }}</a>
                                    @elseif ($report->comment && $report->comment->user)
                                        <a href="{{ route('profile', $report->comment->user->id) }}">{{ $report->comment->user->name }}</a>
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
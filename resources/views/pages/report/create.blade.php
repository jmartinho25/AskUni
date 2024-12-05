
@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Report Content</h2>
    <form action="{{ route('report.store') }}" method="POST">
        @csrf
        <input type="hidden" name="comments_id" value="{{ $type === 'comment' ? $id : '' }}">
        <input type="hidden" name="posts_id" value="{{ $type === 'post' ? $id : '' }}">
        <input type="hidden" name="redirect_url" value="{{ $redirect_url }}">
        <div class="form-group">
            <label for="report-reason">Reason</label>
            <select class="form-control" id="report-reason" name="report_reason" required>
                <option value="Inappropriate content">Inappropriate content</option>
                <option value="Spam">Spam</option>
                <option value="Harassment">Harassment</option>
                <option value="Offensive language">Offensive language</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Report</button>
    </form>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>About Us</h1>

    <div class="about-section">
        <div class="about-item">
            {!! \Illuminate\Support\Str::markdown($content) !!}
            @can('admin', Auth::user())
                <div class="about-actions">
                    <a class="button" href="{{ route('aboutUs.edit') }}" id="btn-edit" title="Edit">
                        <i class="fas fa-pencil"></i> Edit
                    </a>
                </div>
            @endcan
        </div>
    </div>
</div>
@endsection
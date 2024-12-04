@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Frequently Asked Questions</h1>

    <div class="faq-section">
        @foreach ($faqs as $faq)
            <div class="faq-item">
                <h3>{{ $faq->question }}</h3>
                <p>{!! $faq->answer !!}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection
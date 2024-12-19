@extends('layouts.app')

@section('content')
<div class="container">
    <div id="chat">
        <h4 id="chat-title">FEUP Community Chat Room</h4>
        <div id="chat-box">
            <div id="chat-loading">
                <i class="fas fa-circle-notch fa-spin"></i>
            </div>
        </div>
        <form id="chat-form">
            @csrf
            <input type="text" name="message" id="message" placeholder="Type your message...">
            <button type="submit" class="btn btn-primary"> <i class="fa-solid fa-paper-plane"></i> </button>
        </form>
    </div>
</div>

<script>
    window.chatConfig = {
        userId: "{{ Auth::user()->id }}",
        userName: "{{ Auth::user()->name }}",
        userImage: "{{ Auth::user()->photo }}",
        sendMessageUrl: "{{ route('chat.sendMessage') }}",
        csrfToken: "{{ csrf_token() }}",
        pusherAppKey: "{{ env('PUSHER_APP_KEY') }}",
        pusherCluster: "{{ env('PUSHER_APP_CLUSTER') }}"
    };
</script>
@endsection
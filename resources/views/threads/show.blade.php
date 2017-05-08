@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>{{ $thread->title }}</h4>
                    Posted by <a href='#'>{{ $thread->creator->name }}</a>
                </div>

                <div class="panel-body">
                    {{ $thread->body }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">

        @foreach($thread->replies as $reply)
            @include('threads.reply')
        @endforeach

        </div>
    </div>

    <div class='row'>
        <div class="col-md-8 col-md-offset-2">
        @if(auth()->check())
            <form method='POST' action='{{ $thread->path() . "/replies" }}'>
                {{ csrf_field() }}

                <div class='form-group'>
                    <textarea name='body' id='body' class='form-control' placeholder='Write your reply here' rows='5'></textarea>
                </div>

                <button type='submit' class='btn btn-default'>Post</button>
            </form>
        @else
            <p>Please <a href='{{ route("login") }}'>sign in</a> to post a reply to this thread.
        @endif
        </div>
    </div>
</div>
@endsection
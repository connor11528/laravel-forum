@extends('layouts.app')

@section('content')
<thread-view :initial-replies-count="{{ $thread->replies_count }}" inline-template>
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class='level'>
                        <span class='flex'>
                            <h4>{{ $thread->title }}</h4>

                            <!-- Using a named route. Same as "/profiles/{{ $thread->creator->name }}" -->
                            Posted by <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>
                        </span>

                        @can('update', $thread)
                        <form action="{{$thread->path()}}" method='POST'>
                            {{ csrf_field() }}
                            {{ method_field('DELETE') }}
                            <button type='submit' class='btn btn-danger'>Delete Thread</button>
                        </form>
                        @endcan
                    </div>
                </div>

                <div class="panel-body">
                    {{ $thread->body }}
                </div>
            </div>

            <replies @added='repliesCount++' @removed='repliesCount--'></replies>
            
        </div>
        <div class='col-md-4'>
            <div class="panel panel-default">

                <div class="panel-body">
                    This thread was published {{ $thread->created_at->diffForHumans() }} by <a href=''>{{ $thread->creator->name }}</a> and currently has <span v-text='repliesCount'></span> {{ str_plural('reply', $thread->replies_count) }}.
                    <p>
                        <subscribe-button :subscribed="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>
                    </p>

                </div>

            </div>
        </div>
    </div>
</div>
</thread-view>
@endsection
<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div class="panel panel-default">
        <div class="panel-heading">
        	<div class='level'>
        		<h5 class='flex'>
            		<a href="{{ route('profile', $reply->owner) }}">{{ $reply->owner->name }}</a> said {{ $reply->created_at->diffForHumans() }}...
            	</h5>
                
                @if(Auth::check())
                    <div>
                        <favorite :reply="{{ $reply }}"></favorite>
            	   </div>
                @endif
            </div>
        </div>

        <div class="panel-body">
            <div v-if='editing'>
                <div class='form-group'>
                    <textarea v-model='body' class='form-control'></textarea>
                </div>

                <button class='btn btn-xs btn-link' @click='editing = false'>Cancel</button>
                <button class='btn btn-xs btn-primary' @click='update'>Update</button>
            </div>
            <div v-else v-text='body'>
                {{ $reply->body }}
            </div>
        </div>

        @can('update', $reply)
            <div class='panel-footer level'>
                <span class='btn btn-primary btn-xs mr-1' @click="editing = true">Edit</span>
                <span class='btn btn-danger btn-xs mr-1' @click="destroy">Delete</span>
            </div>
        @endcan
    </div>
</reply>
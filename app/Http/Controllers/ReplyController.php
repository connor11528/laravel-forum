<?php

namespace App\Http\Controllers;
use App\Thread;
use App\Reply;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}
	
    public function store($channelId, Thread $thread)
    {
        $this->validate(request(), [
            'body' => 'required'
        ]);
        
        $thread->addReply([
        	'body' => request('body'),
        	'user_id' => auth()->id()
        ]);

        return back()->with('flash', '');
    }

    public function destroy(Reply $reply){

        $this->authorize();
        $reply->delete();

        return back();
    }
}

<?php 

namespace App\Http\Controllers;

use App\Thread;

class ThreadSubscriptionsController extends Controller 
{
	public function store($channelId, Thread $thread)
	{
		$thread->subscribe();
	}

	public function destroy($channelId, Thread $thread)
	{
		$thread->unsubscribe();
	}
}
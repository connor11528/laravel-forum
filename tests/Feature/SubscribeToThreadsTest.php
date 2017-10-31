<?php 

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase 
{
	use DatabaseMigrations;

	/** @test **/
	public function a_user_can_subscribe_to_threads()
	{
		$this->signIn();
		$thread = create('App\Thread');

		// user subscribes to thread
		$this->post($thread->path() . '/subscriptions');

		// check notifications is empty
		$this->assertCount(0, auth()->user()->notifications);

		// leave reply
		$thread->addReply([
			'user_id' => auth()->id(),
			'body' => "reply to a thread here"
		]);

		// get fresh instance of user's notifications and check that there's one
		$this->assertCount(1, auth()->user()->fresh()->notifications);
	}

	/** @test **/
	public function a_user_can_unsubscribe_from_threads()
	{
		$this->signIn();
		$thread = create('App\Thread');
		$thread->subscribe();

		$this->delete($thread->path() . '/subscriptions');

		$this->assertCount(0, $thread->subscriptions);
	}
}



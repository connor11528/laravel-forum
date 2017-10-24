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

		$this->post($thread->path() . '/subscriptions');

		$thread->addReply([
			'user_id' => auth()->id(),
			'body' => "reply to a thread here"
		]);

		// Notification gets sent to user
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



<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationsTest extends TestCase
{
	use DatabaseMigrations;

    /** @test **/
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_reply_from_not_current_user()
    {
    	// create thread and subscribe to it
    	$this->signIn();
    	$thread = create('App\Thread')->subscribe();

    	// check notifications is empty
		$this->assertCount(0, auth()->user()->notifications);

		// signed in user leaves reply on thread
		$thread->addReply([
			'user_id' => auth()->id(),
			'body' => "reply to a thread here"
		]);

		// get fresh instance of user's notifications and check that there is none
		$this->assertCount(0, auth()->user()->fresh()->notifications);

		// new user leaves reply on thread
		$thread->addReply([
			'user_id' => create('App\User')->id,
			'body' => "reply to a thread here"
		]);

		// notify the logged in user with one notification
		$this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test **/
    public function a_user_can_mark_a_notification_as_read()
    {
    	$this->signIn();
    	$thread = create('App\Thread')->subscribe();

    	$thread->addReply([
			'user_id' => create('App\User')->id,
			'body' => "reply to a thread here"
		]);

		$user = auth()->user();

		$this->assertCount(1, $user->unreadNotifications);

		$notificationId = $user->unreadNotifications->first()->id;

		// send delete request for user's notification
		$this->delete("/profiles/{$user->name}/notifications/{$notificationId}");

		$this->assertCount(0, $user->fresh()->unreadNotifications);

    }

    /** @test **/
    public function a_user_can_fetch_unread_notifications()
    {
    	$this->signIn();

    	$thread = create('App\Thread')->subscribe();

    	$thread->addReply([
			'user_id' => create('App\User')->id,
			'body' => "reply to a thread here"
		]);

		$user = auth()->user();

		// get notifications
		$response = $this->getJson("/profiles/{$user->name}/notifications")->json();

		// make sure there is one
		$this->assertCount(1, $response);
    }
}

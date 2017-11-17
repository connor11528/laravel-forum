<?php

namespace Tests\Feature;

use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotificationsTest extends TestCase
{
	use DatabaseMigrations;

	public function setup()
    {
        parent::setUp();
        $this->signIn();
    }

    /** @test **/
    public function a_notification_is_prepared_when_a_subscribed_thread_receives_a_reply_from_not_current_user()
    {
    	// create thread and subscribe to it
    	$thread = create('App\Thread')->subscribe();

    	// check notifications is empty
		$this->assertCount(0, auth()->user()->notifications);

		// signed in user leaves reply on thread
		$thread->addReply([
			'user_id' => auth()->id(),
			'body' => 'reply to a thread here'
		]);

		// get fresh instance of user's notifications and check that there is none
		$this->assertCount(0, auth()->user()->fresh()->notifications);

		// new user leaves reply on thread
		$thread->addReply([
			'user_id' => create('App\User')->id,
			'body' => 'reply to a thread here'
		]);

		// notify the logged in user with one notification
		$this->assertCount(1, auth()->user()->fresh()->notifications);
    }

    /** @test **/
    public function a_user_can_mark_a_notification_as_read()
    {

        create(DatabaseNotification::class);

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
        // invoke model factory to create notification
        create(DatabaseNotification::class);

		// make sure there is one
		$this->assertCount(
		    1,
            $this->getJson("/profiles/" . auth()->user()->name . "/notifications")->json()
        );
    }
}

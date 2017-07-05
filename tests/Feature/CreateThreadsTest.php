<?php

namespace Tests\Feature;

use App\Activity;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateThreadsTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	public function guests_may_not_create_threads()
	{
		$this->withExceptionHandling();

		$this->get('/threads/create')
			->assertRedirect('/login');
		$this->post('/threads')
			->assertRedirect('/login');
	}

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = make('App\Thread');
        $response = $this->post('/threads', $thread->toArray());

        // get location of the redirection and check post is there
        $this->get($response->headers->get('Location'))
			->assertSee($thread->title)
        	->assertSee($thread->body);
    }

    /** @test */
    public function a_thread_requires_a_title()
    {
    	$this->publishThread(['title' => null])
    		->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_thread_requires_a_body()
    {
    	$this->publishThread(['body' => null])
    		->assertSessionHasErrors('body');
    }

    /** @test */
    public function a_thread_requires_a_valid_channel()
    {
    	factory('App\Channel', 2)->create();

    	// cannot be null
    	$this->publishThread(['channel_id' => null])
    		->assertSessionHasErrors('channel_id');

    	// must be existing channel (fails w/ id=2)
    	$this->publishThread(['channel_id' => 999])
    		->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function authorized_users_can_delete_threads()
    {
        $this->signIn();

        // create thread owned by user with reply
        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        // make sure that threads and replies activities is also empty
        $this->assertEquals(0, Activity::count());

    }

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();
        $thread = create('App\Thread');

        // delete as guest
        $this->delete($thread->path())
            ->assertRedirect('/login');

        $this->signIn();

        // test authorization
        $this->delete($thread->path())
            ->assertStatus(403);
    }


    protected function publishThread($overrides = [])
    {
    	// laravel can catch exception and perform redirect
    	$this->withExceptionHandling()
    		->signIn();

    	$thread = make('App\Thread', $overrides);
    	return $this->post('/threads', $thread->toArray());
    }  
}

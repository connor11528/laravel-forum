<?php

namespace Tests\Feature;

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
		$this->expectException('Illuminate\Auth\AuthenticationException');
		$thread = make('App\Thread');
		$this->post('/threads', $thread->toArray());
	}

	/** @test */
	public function guests_may_not_see_create_threads_page()
	{
		$this->get('/threads/create')
			->assertRedirect('/login');
	}

    /** @test */
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())
			->assertSee($thread->title)
        	->assertSee($thread->body);
    }
}

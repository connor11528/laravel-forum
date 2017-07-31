<?php 

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	function guests_can_not_favorite_anything()
	{
		$this->withExceptionHandling()
			->post('replies/1/favorites')
			->assertRedirect('/login');
	}

	/** @test */
	public function an_authenticated_user_can_favorite_any_reply()
	{
		$this->signIn();

		$reply = create('App\Reply');

		// If I post to a favorite endpoint
		$this->post('replies/' . $reply->id . '/favorites');

		// It should be recorded in the database
		$this->assertCount(1, $reply->favorites);
	}

	/** @test */
	public function an_authenticated_user_may_only_favorite_a_reply_once()
	{
		$this->signIn();

		$reply = create('App\Reply');

		try {
			$this->post('replies/' . $reply->id . '/favorites');
			$this->post('replies/' . $reply->id . '/favorites');	
		} catch (\Exception $e) {
			// better fail test error handling
			$this->fail('Database did not expect to insert the same record twice.');
		}

		$this->assertCount(1, $reply->favorites);
	}

	/** @test */
	public function an_authenticated_user_can_unfavorite_any_reply()
	{
		$this->signIn();
		$reply = create('App\Reply');

		// favorite
		$reply->favorite();

		// unfavorite
		$this->delete('replies/' . $reply->id . '/favorites');
		$this->assertCount(0, $reply->favorites);
	}
}
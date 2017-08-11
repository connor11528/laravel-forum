<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends Filters
{
	// Register filters to operate on
	protected $filters = ['by', 'popular', 'unanswered'];

	// Filter the query by a given username
	protected function by($username)
	{
		$user = User::where('name', $username)->firstOrFail();
		
		return $this->builder->where('user_id', $user->id);
	}

	// Filter query according to most popular threads
	protected function popular()
	{
		// remove orders
		$this->builder->getQuery()->orders = [];
		
		// order by popularity
		return $this->builder->orderBy('replies_count', 'desc');
	}

	protected function unanswered()
	{
		return $this->builder->where('replies_count', 0);
	}
}
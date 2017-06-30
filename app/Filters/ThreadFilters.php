<?php

namespace App\Filters;

use App\User;

class ThreadFilters extends Filters
{
	// Register filters to operate on
	protected $filters = ['by'];

	// Filter the query by a given username
	protected function by($username)
	{
		$user = User::where('name', $username)->firstOrFail();
		
		return $this->builder->where('user_id', $user->id);
	}
}
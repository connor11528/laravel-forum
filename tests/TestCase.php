<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function signIn($user = null)
    {
    	// use passed in user or create one
    	$user = $user ?: create('App\User');
    	$this->actingAs($user);
    	return $this;
    }
}

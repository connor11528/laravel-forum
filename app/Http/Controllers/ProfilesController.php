<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Activity;

class ProfilesController extends Controller
{
	// Show a User's Profile page
    public function show(User $user)
    {
    	return view('profiles.show', [
    		'profileUser' => $user,
    		'activities' => Activity::feed($user)
    	]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ProfilesController extends Controller
{
	// Show a User's Profile page
    public function show(User $user)
    {
    	return view('profiles.show', [
    		'profileUser' => $user,
    		'threads' => $user->threads()->paginate(30)
    	]);
    }
}

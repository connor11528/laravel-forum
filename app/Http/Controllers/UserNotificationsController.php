<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserNotificationsController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index(User $user)
	{
		return auth()->user()->unreadNotifications;
	}

    public function destroy(User $user, $notificationId)
    {
    	auth()->user()->notifications()->findOrFail($notificationId)->markAsRead();
    }
}

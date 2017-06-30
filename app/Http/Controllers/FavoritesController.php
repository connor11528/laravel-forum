<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Reply;
use App\Favorite;

class FavoritesController extends Controller
{
	// must be logged in to favorite
	public function __construct()
	{
		$this->middleware('auth');
	}

    public function store(Reply $reply)
    {
    	return $reply->favorite();
    }
}

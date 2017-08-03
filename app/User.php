<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'email'
    ];

    public function threads()
    {
        // make database relationship and return threads in proper order
        return $this->hasMany(Thread::class)->latest();
    }

    // Get route key name for Laravel
    public function getRouteKeyName()
    {
        return 'name';
    }

    // Get all activity for the user
    public function activity()
    {
        return $this->hasMany(Activity::class);
    }
}

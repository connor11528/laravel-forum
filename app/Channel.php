<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{    
    // customizing the key name so we fetch by slug instead of id
    // https://laravel.com/docs/5.4/routing#route-model-binding
    public function getRouteKeyName()
    {
    	return 'slug';
    }

    public function threads()
    {
    	return $this->hasMany(Thread::class);
    }
}

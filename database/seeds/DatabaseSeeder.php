<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// Creates threads, replies and associated channels and users
        factory(App\Thread::class, 10)->create();
        factory(App\Reply::class, 5)->create();
    }

    
}

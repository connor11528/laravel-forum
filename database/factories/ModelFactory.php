<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


// quickly create fifty threads using $ php artisan tinker, then:
// factory('App\Thread', 50)->create();
$factory->define(App\Thread::class, function(Faker\Generator $faker){
	return [
		'user_id' => function(){
			return factory('App\User')->create()->id;
		},
		'channel_id' => function(){
			return factory('App\Channel')->create()->id;
		},
		'title' => $faker->sentence,
		'body' => $faker->paragraph,

	];
});

$factory->define(App\Reply::class, function(Faker\Generator $faker){
	return [
		'thread_id' => function(){
			return factory('App\Thread')->create()->id;
		},
		'user_id' => function(){
			return factory('App\User')->create()->id;
		},
		'body' => $faker->paragraph
	];
});

$factory->define(App\Channel::class, function(Faker\Generator $faker){
	$name = $faker->word;

	return [
		'name' => $name,
		'slug' => $name
	];
});


$factory->define(Illuminate\Notifications\DatabaseNotification::class, function(Faker\Generator $faker){
    return [
        'id' => \Ramsey\Uuid\Uuid::uuid4()->toString(),   // generate unique id
        'type' => 'App\Notifications\ThreadWasUpdated',
        'notifiable_id' => function(){
            return auth()->id() ?: factory('App\User')->create()->id;
        },
        'notifiable_type' => 'App\User',
        'data' => ['foo' => 'bar']
    ];
});

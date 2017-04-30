
Medium Link: https://medium.com/@connorleech/build-an-online-forum-with-laravel-initial-setup-and-seeding-part-1-a53138d1fffc

Build an online forum with Laravel — Initial Setup and Seeding (Part 1)
===

Using Laravel 5.4 

Get things going:

```
$ laravel new laravel-forum
$ composer install
```


1. Thread
2. Reply
3. User
	A. Thread is created by a user
	B. A reply belongs to a thread, and belongs to a user.

Next we have a command to make the Thread model with a migration and a resourceful controller.

```
$ php artisan make:model Thread -mr
```

Next update the migration for Thread:

```
public function up()
{
    Schema::create('threads', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id');
        $table->string('title');
        $table->text('body');
        $table->timestamps();
    });
}
```

> Next up we have to interact with MySQL. If you do not have MySQL set up on your machine, well, then good luck. You should for sure go do that and battle for a few hours and then pick up where we left off. It is not that hard but you need to have your password and username setup. I know that on my computer so I created a .env file to store my environment variables and then typed in my password. If you have used Laravel before or got set up this will be no sweat for you!

Log into MySQL

```
$ mysql -uroot -p 
```

Run a command to create the database we specified in our environment file:

```
MySQL [(none)]> create database forum;
Query OK, 1 row affected (0.02 sec)
```

Close out of MySQL with Ctrl + C.

Create the file to store variable specific to your computer. This os covered in the .gitignore file.

```
$ touch .env
```

Edit this part:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=forum
DB_USERNAME=YOUR_USERNAME_HERE
DB_PASSWORD=YOUR_PASSWORD_HERE
```

Migrate the database.

```
$ php artisan migrate
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table
Migrating: 2014_10_12_100000_create_password_resets_table
Migrated:  2014_10_12_100000_create_password_resets_table
Migrating: 2017_04_21_224745_create_threads_table
Migrated:  2017_04_21_224745_create_threads_table
```

Download Sequel Pro if you do not have it.

https://sequelpro.com/

Connect to the database using their friendly GUI.

![](http://i.imgur.com/FQtg9PR.png)

Back in the terminal make a model, migration and a controller for Reply. We went to have replies in our forum.

```
$ php artisan make:model Reply -mc
```

Update the corresponding migration file:

```
public function up()
{
    Schema::create('replies', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('thread_id');
        $table->integer('user_id');
        $table->text('body');
        $table->timestamps();
    });
}
```

Run migrations again.

```
$ php artisan migrate
```

In Sequel Pro click this button (it is in bottom left corner)

![](http://i.imgur.com/AaLJLZe.png)

You will see a new Reply table.

The next part is by far the most complicated... seeding the database.

Seed the Database
Seeding the database means that we are going to generate and pre-populate data (such as Threads, Replies and Users) so our application is not empty and we have real records to play with.
We are going to generate mock data for Threads. If you remember, in the create_threads migration file we defined threads as having an id, user_id, title, body and timestamps.
We use the Faker PHP library to generate this fake data.
Head into database/factories/ModelFactory.php and add a factory definition for creating Threads. The file should look like this:
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});
$factory->define(App\Thread::class, function(Faker\Generator $faker){
   return [
     'user_id' => function(){
        return factory('App\User')->create()->id;
     },
     'title' => $faker->sentence,
     'body' => $faker->paragraph
   ];
});
In the above snippet is automatically loaded by Laravel. The first part creates some Users. In the second part, we define a new factory for creating Threads. Faker generates bodies and titles. For the user_id section it creates a new user, persists it to the database and then associates the id for that user with that Thread.
Use php artisan tinker and factories to generate the data
Back at the command line fire up the tinker command. After that we are going to make a new factory and create 50 threads with their associated users.
$ php artisan tinker
Psy Shell v0.8.3 (PHP 5.6.27 — cli) by Justin Hileman
>>> factory('App\Thread', 50)->create();
=> Illuminate\Database\Eloquent\Collection {#688
all: [
App\Thread {#686
user_id: 1,
title: "Mollitia qui quos nesciunt perferendis error quam quo.",
body: "Laborum rem sit reprehenderit voluptatem. Dolorem magnam possimus quod. Quam omnis architecto doloremque et non reiciendis et. Sit dolorum doloribus quo iure est molestiae at.",
updated_at: "2017-04-30 22:06:47",
created_at: "2017-04-30 22:06:47",
id: 1,
},
Hit refresh on Sequel Pro and you will see our generated records:

The generated Threads

The generated Users
Now our Threads and Users MySQL tables are full of data for us to use and play with! There are 50 random threads that are associated with fake Users.
Add Replies to the Threads
Create another Factory definition at the bottom of the database/factories/ModelFactory.php file.
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
Refresh the database using Artisan. The below command will delete all of our data and run these commands again from scratch.
$ php artisan migrate:refresh
Rolling back: 2017_04_21_233654_create_replies_table
Rolled back:  2017_04_21_233654_create_replies_table
Rolling back: 2017_04_21_224745_create_threads_table
Rolled back:  2017_04_21_224745_create_threads_table
Rolling back: 2014_10_12_100000_create_password_resets_table
Rolled back:  2014_10_12_100000_create_password_resets_table
Rolling back: 2014_10_12_000000_create_users_table
Rolled back:  2014_10_12_000000_create_users_table
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table
Migrating: 2014_10_12_100000_create_password_resets_table
Migrated:  2014_10_12_100000_create_password_resets_table
Migrating: 2017_04_21_224745_create_threads_table
Migrated:  2017_04_21_224745_create_threads_table
Migrating: 2017_04_21_233654_create_replies_table
Migrated:  2017_04_21_233654_create_replies_table
Here are the commands in tinker to generate our data from the Factory we wrote:
$ php artisan tinker
$threads = factory('App\Thread', 50)->create();
$threads->each(function($thread){ factory('App\Reply', 10)->create([ 'thread_id' => $thread->id ]); });
These commands will recreate our Threads as we did previously and then will generate 10 replies to each thread and associate the replies with the thread via the thread_id.

Ten Replies for each Thread. Each Reply has a value for thread_id
Further Reading on Database Seeding
Below are some further reading resources on seeding databases and Model Factories in Laravel 5.4.
Database: Seeding - Laravel - The PHP Framework For Web Artisans

Laravel - The PHP framework for web artisans.
laravel.com	
Learn to use Model Factories in Laravel

Laravel 5.1 comes with a feature called model factories that are designed to allow you to quickly build out "fake…
laravel-news.com	
Conclusion
In this post we have set up the database, viewed in in Sequel Pro and mocked out our data using Factories.
The full video is available on Laracasts:
Initial Database Setup With Seeding

Let's begin by reviewing the most minimal requirements for a forum. If you think about it, we couldn't possibly…
laracasts.com	
Go sign up! Thanks for reading.
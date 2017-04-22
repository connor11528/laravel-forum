Laravel Forum
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


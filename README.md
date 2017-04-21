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


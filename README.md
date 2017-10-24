Laravel Forum
===

I'm blogging my way through the advanced Laracast course: [Let's Build A Forum with Laravel and TDD](https://laracasts.com/series/lets-build-a-forum-with-laravel/)

![](https://user-images.githubusercontent.com/3578908/26895669-24e15fb2-4b77-11e7-8b9f-5a3873f279d4.png)

## Build an online forum with Laravel

### [Part 1](https://medium.com/@connorleech/build-an-online-forum-with-laravel-initial-setup-and-seeding-part-1-a53138d1fffc): Initial Setup and Seeding

### [Part 2](https://medium.com/@connorleech/test-driven-development-tdd-in-laravel-b5a2bf9ab65b): Create routes, views, controllers. Generate auth. Write test

### [Part 3](https://medium.com/@connorleech/build-an-online-forum-with-laravel-analyzing-the-application-part-3-9317a0f893b4): Post and Show Replies to Threads

### [Part 4](https://medium.com/@connorleech/build-an-online-forum-with-laravel-give-the-user-the-ability-to-create-threads-part-4-ccdb6badc618): Give the User the ability to create Threads

### [Part 5](https://medium.com/@connorleech/testing-helpers-in-laravel-5-4-363ac47a8504): Testing Helpers in Laravel 5.4

### Helpful commands

```
# Clear cache
$ php artisan cache:clear

# Run tests 
$ phpunit
// OR 
$ ./vendor/bin/phpunit

# Clear config 
$ php artisan config:clear

# Migrate or refresh database
$ php artisan migrate:refresh
$ php artisan migrate:rollback

# Seed database with db seeders
$ php artisan db:seed 

# Seed database with model factories
$ php artisan tinker
> factory('App\Thread', 50)->create();

```

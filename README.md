# SayIt

A simple comments app to test and learn the framework and the MVC pattern.

# Database creation

If you have Apache, MySQL, PHP and Symfony installed, set your phpMyAdmin username, password and database name in `app/config/parameters.yml`. Open the project directory in the terminal and run:

```
php bin/console doctrine:database:create
php bin/console doctrine:generate:entities AppBundle
php bin/console doctrine:schema:update --force
```

Read the [Official Symfony Doctrine documentation](http://symfony.com/doc/current/doctrine.html).

SayIt
=====

A Symfony project created on October 17, 2016, 2:40 pm. A simple comments app to test and learn the framework and the MVC pattern.

Database creation
=================

If you have Apache, MySQL, PHP and Symfony installed, set your phpMyAdmin username, password and database name in app/config/parameters.yml

Then open the project directory in the terminal and run:

php bin/console doctrine:database:create

php bin/console doctrine:generate:entities AppBundle

php bin/console doctrine:schema:update --force

Official Symfony/Doctrine documentation page: http://symfony.com/doc/current/doctrine.html



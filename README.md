<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a free, open-source PHP web framework which is intended for the development of web applications following the Model-View-Controller (MVC) architectural pattern. This 
pattern divides the logic of an application into three interconnected elements:

- The **Model**, which manages the **data** of the application
- The **View**, which handles what the user can **see**
- And the **Controller**, which **connects** the two other parts by **processing** the user input

This division makes it able to edit one part of the application (e.g. the view) without having to edit the other parts. This is especially useful when working on a project with 
multiple developers, because one can work on the Model while another works on the View or on the Controller. This division can also be found when looking at the structure of a laravel project:

- All Model related files can be found in the folder *database* (Migrations & Seeders) or *app/Models* (Model Classes)
- All View related files can be found in *resources/views* (Blades) or *public/css* (CSS files)
- All Controller related files can be found in *app/Http/Controllers*

Besides that some of the most helpful features of Laravel are e.g. [Blade Templates](https://laravel.com/docs/8.x/blade), which allow to use plain PHP Code inside the views (If Statements, Loops, easy usage of variables passed to the view etc.), [Database Migrations](https://laravel.com/docs/8.x/migrations), which allow to define, share and edit
database schemas in an easy manner, or [ELoquent ORM](https://laravel.com/docs/8.x/eloquent), which allows to interact with the database without writing complex SQL-Statements.

## Local development environment

### Prerequisites

In order to run this project locally there are a few things that need to be installed first:

- [XAMMP](https://www.apachefriends.org/index.html) for local database and PHP
- [Composer](https://getcomposer.org/) for PHP dependencies
- [Node.js/NPM](https://nodejs.org/en/download/) for JavaScript dependencies

### Set up

1. Create a new folder for the project 
2. Clone this project inside of the folder by running *git clone https://github.com/Marcat98/Basex.git* inside of this folder
3. Make sure your installation of Composer and NPM is correct by running *composer -v* and *npm -v* in the same folder. If everything is installed correctly this should output 
the version number. If it says something like 'command not found' you may have to add whatever is lacking to your systems path variable
4. Run *composer install* to install all of the projects composer dependencies
5. Run *npm install* to install all of the npm dependencies
6. In the project you find a file named *.env.example*. Copy this file to create a new file named *.env* in the same directory
7. Generate an app encryption key by running *php artisan key:generate --show*
8. Copy the outputed key into your .env-file at 'APP_KEY='
9. Open the XAMMP Control Panel and start Apache and MySQL
10. Open your browser and go to localhost/phpmyadmin
11. Here you need to create an empty database for the application
12. Add the database information in the .env file to allow Laravel to connect the database (fill in the DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, and DB_PASSWORD options to
match the credentials of the database you just created)
14. Run *php artisan migrate* to migrate the database. After that you should see various (empty) tables added to the database you just created.
15. Run *php artisan db:seed* to seed the database. This will make the entries in *entitlements*-table 
16. To start the application you can now run *php artisan serve* and follow the instructions in the output

## Additional remarks

If you want a user account to have the moderator entitlement you need to:

1. Create a new user by registering or simply creating one in the database
2. Go to the database and look up the id of the user in the *users*-table
3. Look up the id of the moderator entitlement in the *entitlements*-table
4. Go to the *user_entitlements*-table and insert an entry where user_id is the id of the user and entitlement_id the id of the moderator entitlement
5. The user is now Moderator

In the future there should be the possibilty to request the moderator entitlement via the application. For this reason we added the admin entitlement. A admin would still need to be set in the database, but then all users can request the moderator entitlement and the admins can simply confirm (This is not implemented yet).

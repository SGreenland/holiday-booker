<!-- <p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p> -->

## Holiday Booker

A Holiday booking web app, built with Laravel and Vue.js

## Screenshot

![image](https://github.com/SGreenland/holiday-booker/assets/78597962/ee17e7fb-085a-4fc4-bf4a-8626d78d6916)

## Run locally

After cloning the project:

#### create a .env file
cp .env.example .env

#### install composer dependencies
composer install

#### install npm dependencies
npm install

#### generate a key for your application
php artisan key:generate

#### create a local MySQL database (make sure you have MySQL up and running)
mysql -u root

> create database {your_db_name};
> exit;

#### add the database connection config to your .env file
DB_CONNECTION=mysql
DB_DATABASE={your_db_name}
DB_USERNAME=root
DB_PASSWORD=

#### run the migration files to generate the schema
php artisan migrate

#### run webpack and watch for changes
npm run watch

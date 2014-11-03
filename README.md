Dun &amp; Bradstreet Credibility Corp. Laravel Client [UNOFFICIAL]
=============
[![Coverage Status](https://img.shields.io/coveralls/credibility/dandb-laravel.svg)](https://coveralls.io/r/credibility/dandb-laravel) 
[![Build Status](https://travis-ci.org/credibility/dandb-laravel.svg)](https://travis-ci.org/credibility/dandb-laravel)
[![Packagist](http://img.shields.io/packagist/v/credibility/dandb-laravel.svg)](https://packagist.org/packages/credibility/dandb-laravel)

Dun and Bradstreet Cedibility Corp. API Client for Laravel based on [credibility/dandb](https://github.com/credibility/dandb).

## Installation

Using composer:

    "require": {
      "credibility/dandb-laravel": "dev-master"
    }
    
Then, in `config/app.php`, add the following to the service providers array.

    array(
       ...
      'Credibility\DandB\Providers\DandBServiceProvider',
    )

## Configs

Run `php artisan config:publish credibility/dandb-laravel` to publish your config file. Usage instructions are inside the file.

## Usage

To use within the application, call `App::make('dandb')` or if you have an instance of the `Illuminate/Foundation/Application`, `$app->make('dandb')`

From there, the returned object will allow you to call methods within the [credibility/dandb](https://github.com/credibility/dandb) class. These calls will automatically be cached using the Laravel Cache abstraction.

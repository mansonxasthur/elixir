<?php


namespace App\Facades;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Facade;

class User extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'user';
    }

    public static function routes(array $options = [])
    {
        $router = static::$app->make('router');
        Auth::routes($options);

        // Home
        $router->get('/home', 'HomeController@index')->name('home');

        // Settings Routes
        if ($options['settings'] ?? true) {
            self::settings($router);
        }

    }

    private static function settings(\Illuminate\Routing\Router $router)
    {
        $router->get('settings', 'HomeController@show')->name('settings');
        $router->put('settings/info', 'HomeController@info')->name('info');
        $router->put('settings/password', 'HomeController@password')->name('info');
    }
}
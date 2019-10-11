<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class Admin extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'admin';
    }

    public static function routes(array $options = [])
    {
        $router = static::$app->make('router');

        $router->prefix('mx-admin')->namespace('Admin')->group(function($router) use ($options) {
            // Home
            $router->get('', 'HomeController@index')->name('admin.home');

            // Authentication Routes...
            $router->get('login', 'Auth\LoginController@showLoginForm')->name('admin.login');
            $router->post('login', 'Auth\LoginController@login');
            $router->post('logout', 'Auth\LoginController@logout')->name('admin.logout');

            // Password Reset Routes...
            if ($options['reset'] ?? true) {
                self::resetPassword($router);
            }

            // Settings Routes
            if ($options['settings'] ?? true) {
                self::settings($router);
            }

            // ACL Routes
            if ($options['acl'] ?? false) {
                self::acl($router);
            }
        });
    }

    private static function resetPassword(\Illuminate\Routing\Router $router)
    {
        $router->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
        $router->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
        $router->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('admin.password.reset');
        $router->post('password/reset', 'Auth\ResetPasswordController@reset')->name('admin.password.update');
    }

    private static function settings(\Illuminate\Routing\Router $router)
    {
        $router->get('settings', 'HomeController@show')->name('admin.settings');
        $router->put('settings/info', 'HomeController@info')->name('admin.info');
        $router->put('settings/password', 'HomeController@password')->name('admin.info');
    }

    private static function acl($router)
    {
        $router->get('admins', 'AdminController@index')->name('admin.admins');
        $router->post('admins', 'AdminController@store');
        $router->put('admins/{admin}', 'AdminController@update');
        $router->delete('admins/{admin}', 'AdminController@destroy');
    }
}
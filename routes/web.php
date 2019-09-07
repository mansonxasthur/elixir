<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

/******************** Admin Area ***********************/
Route::prefix('mx-admin')->namespace('Admin')->group(function(){

    // Authentication
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    // Password Reset
    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('admin.password.reset');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('admin.password.update');

    // Settings
    Route::get('/home', 'HomeController@index')->name('home');

    // Home
    Route::get('', 'HomeController@index')->name('admin.home');

    // Settings
    Route::get('settings', 'HomeController@show')->name('admin.settings');
    Route::put('settings/info', 'HomeController@info')->name('admin.info');
    Route::put('settings/password', 'HomeController@password')->name('admin.info');

    // Admins
    Route::get('admins', 'AdminController@index')->name('admin.admins');
    Route::post('admins', 'AdminController@store');
    Route::put('admins/{admin}', 'AdminController@update');
    Route::delete('admins/{admin}', 'AdminController@destroy');
});

/******************** User Area ***********************/

// Authentication
Auth::routes();

// Home
Route::get('/home', 'HomeController@index')->name('home');

// Settings
Route::get('settings', 'HomeController@show')->name('admin.settings');
Route::put('settings/info', 'HomeController@info')->name('admin.info');
Route::put('settings/password', 'HomeController@password')->name('admin.info');
/******************** Public Area ************************/

$locales = config('app.locales');

if (count($locales) > 1) {
    Route::get('/', function (Request $request) {
        $locale = substr($request->headers->get('accept_language'), 0, 2) ?? config('translatable.fallback_locale');

        if (in_array($locale, config('translatable.locales'))) {
            return redirect("/{$locale}");
        } else {
            return redirect('/' . config('translatable.fallback_locale'));
        }
    });

    foreach ($locales as $locale) {
        Route::prefix($locale)->middleware('lang')->group(function () {
            Route::get('/', function() {
                return view('welcome');
            });
        });
    }

    Route::middleware('lang')->get('/{any}', function () {
        abort(404);
    })->where('any', '.*');
}

Route::get('/', function() {
    return view('welcome');
});












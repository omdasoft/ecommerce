<?php

use Illuminate\Support\Facades\Route;

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


define('PAGINATION_COUNT',10);
Route::group(['namespace' => 'Admin','middleware' => 'auth:admin'], function() {
    Route::get('/','DashboardController@index')->name('admin.dashboard');

    ################################## begin languages routes ####################
    Route::group(['prefix' => 'languages'],function(){
        Route::get('/','LanguagesController@index')->name('admin.languages');
        Route::get('create','LanguagesController@create')->name('admin.languages.create');
        Route::post('store','LanguagesController@store')->name('admin.languages.store');

        Route::get('edit/{id}','LanguagesController@edit')->name('admin.languages.edit');
        Route::post('update/{id}','LanguagesController@update')->name('admin.languages.update');
        Route::get('delete/{id}','LanguagesController@destroy')->name('admin.languages.delete');

    });
    ################################## end languages routes ######################

    ################################## begin MainCats routes ####################
    Route::group(['prefix' => 'main_cats'],function(){
        Route::get('/','MainCategoriesController@index')->name('admin.maincats');
        Route::get('create','MainCategoriesController@create')->name('admin.maincats.create');
        Route::post('store','MainCategoriesController@store')->name('admin.maincats.store');

        Route::get('edit/{id}','MainCategoriesController@edit')->name('admin.maincats.edit');
        Route::post('update/{id}','MainCategoriesController@update')->name('admin.maincats.update');
        Route::get('delete/{id}','MainCategoriesController@destroy')->name('admin.maincats.delete');
        Route::get('changeStatus/{id}','MainCategoriesController@changeStatus')->name('admin.maincats.status');

    });
    ################################## end languages routes ######################

    ################################## begin vendors routes ####################
    Route::group(['prefix' => 'vendors'],function(){
        Route::get('/','VendorsController@index')->name('admin.vendors');
        Route::get('create','VendorsController@create')->name('admin.vendors.create');
        Route::post('store','VendorsController@store')->name('admin.vendors.store');

        Route::get('edit/{id}','VendorsController@edit')->name('admin.vendors.edit');
        Route::post('update/{id}','VendorsController@update')->name('admin.vendors.update');
        Route::get('delete/{id}','VendorsController@destroy')->name('admin.vendors.delete');
        Route::get('changeStatus/{id}','VendorsController@changeStatus')->name('admin.vendors.status');

    });
    ################################## end vendors routes ######################
});
Route::group(['namespace' => 'Admin'], function(){
    Route::get('login','LoginController@getLogin')->name('admin.get.login');
    Route::post('login','LoginController@login')->name('admin.login');
});


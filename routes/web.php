<?php

use Illuminate\Routing\RouteUri;
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

// Route::get('/', function () {
//     return view('home');
// });

// untuk search
Route::get('search', 'SearchController@post')->name('search.posts');

// bisa juga tanpa withoutMiddleware
Route::get('posts', 'PostController@index')->name('posts.index');
Route::prefix('posts')->middleware('auth')->group(function () {
  // Route::get('posts', 'PostController@index')->name('posts.index')->withoutMiddleware('auth');
  Route::get('create',  'PostController@create')->name('posts.create');
  Route::post('store', 'PostController@store');
  Route::get('{post:slug}/edit', 'PostController@edit');
  Route::patch('{post:slug}/edit', 'PostController@update');
  Route::delete('{post:slug}/delete', 'PostController@destroy');
});
Route::get('posts/{post:slug}', 'PostController@show')->name('posts.show');

Route::get('categories/{category:slug}', 'CategoryController@show')->name('categories.show');
Route::get('tags/{tag:slug}', 'TagController@show')->name('tags.show');



// Route::get('contact', function () {
//     // request()->fullUrl() //menampilkan semua url
//     // request()->path() == //dapetin contact
//     // request()->is('') //dapetin contact
//     return request()->path() == 'contact' ? true : false;
//     return request()->is('contact') ? true : false;
// });

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

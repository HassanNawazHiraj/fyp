<?php

use Illuminate\Support\Facades\Route;




//Auth::routes();

// Route::get('/register', function() {
//     return redirect('/');
// });
// // Route::get('/home', 'HomeController@index')->name('home');

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/{any}', 'AppController@index')->where('any', '.*');



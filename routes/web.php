<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UtilityController;


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
Route::get('/test', function () {
  return 'Route is working!';
});

Route::group(['middleware'=>['Locale']],function(){
  include('admin.php');
  include('ajax.php');
  include('patient.php');
});

Route::get('change_locale/{lang}','HomeController@change_locale')->name('change_locale');

Route::get('clear-cache', [UtilityController::class, 'clearCache']);

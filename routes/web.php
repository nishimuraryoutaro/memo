<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ScheduleController;
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
Auth::routes();
Route::get('/', [HomeController::class, 'index'])
->name('home');
Route::get('/home', [HomeController::class, 'index'])
->name('home');
Route::post('/store', [HomeController::class, 'store'])
->name('store');
Route::get('/edit/{id}', [HomeController::class, 'edit'])
->name('edit');
Route::post('/edit/{id}', [HomeController::class, 'edit'])
->name('edit');
Route::post('/update', [HomeController::class, 'update'])
->name('update');
Route::get('/update', [HomeController::class, 'update'])
->name('update');
Route::post('/destory', [HomeController::class, 'destory'])
->name('destory');


Route::resource('/shedules', ScheduleController::class);
Route::put('/schedules/{schedule}/updateByCalendar', [ScheduleController::class, 'updateByCalendar'])->name('schedules.updateByCalendar');
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\AttendanceRegisterController;
use App\Http\Controllers\UserAttendanceListController;


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

Route::get('/register', [RegisterController::class, 'register']);

Route::get('/login', [UserLoginController::class, 'login']);

Route::get('/attendance', [AttendanceRegisterController::class, 'attendance']);

Route::get('/attendance/list', [UserAttendanceListController::class, 'list']);


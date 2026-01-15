<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\AttendanceRegisterController;
use App\Http\Controllers\UserAttendanceListController;
use App\Http\Controllers\UserAttendanceDetailController;
use App\Http\Controllers\UserApplicationController;
use App\Http\Controllers\AdministratorLoginController;
use App\Http\Controllers\AdministratorAttendanceListController;
use App\Http\Controllers\AdministratorAttendanceDetailController;
use App\Http\Controllers\StaffListController;
use App\Http\Controllers\StaffAttendanceListController;
use App\Http\Controllers\AdministratorApplicationController;
use App\Http\Controllers\ApprovalController;


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

// Route::get('/register', [RegisterController::class, 'register']);

// Route::get('/login', [UserLoginController::class, 'login']);

Route::get('/attendance', [AttendanceRegisterController::class, 'attendance']);

Route::post('/stamp/attendance', [AttendanceRegisterController::class, 'stampAttendance']);

Route::get('/attendance/list', [UserAttendanceListController::class, 'list']);

Route::get('/attendance/detail/{id}', [UserAttendanceDetailController::class, 'detail']);

Route::get('/stamp_correction_request/list', [UserApplicationController::class, 'application']);

Route::get('/admin/login', [AdministratorLoginController::class, 'login']);

Route::prefix('admin')->group(function () {
    Route::post('/login', [AdministratorLoginController::class, 'authenticate']);
    Route::post('/logout', [AdministratorLoginController::class, 'logout']);
});

Route::get('/admin/attendance/list', [AdministratorAttendanceListController::class, 'attendancelist']);

Route::get('admin/attendance/{id}', [AdministratorAttendanceDetailController::class, 'detail']);

Route::get('/admin/staff/list', [StaffListController::class, 'list']);

Route::get('/admin/attendance/staff/{id}', [StaffAttendanceListController::class, 'list']);

Route::get('/stamp_correction_request/list', [AdministratorApplicationController::class, 'application']);

Route::get('/stamp_correction_request/approve/{attendance_correct_request_id}', [ApprovalController::class, 'approval']);


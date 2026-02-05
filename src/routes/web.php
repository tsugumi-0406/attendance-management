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
use Illuminate\Foundation\Auth;


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

Route::get('/login', [UserLoginController::class, 'login'])->name('user.login');


Route::middleware('auth:web')->group(function () {
    Route::get('/attendance', [AttendanceRegisterController::class, 'attendance']);

    Route::post('/stamp/attendance', [AttendanceRegisterController::class, 'stampAttendance']);

    Route::post('/stamp/break/start', [AttendanceRegisterController::class, 'stampBreakStart']);

    Route::post('/stamp/break/stop', [AttendanceRegisterController::class, 'stampBreakStop']);

    Route::post('/stamp/leave', [AttendanceRegisterController::class, 'stampLeave']);

    Route::get('/attendance/list', [UserAttendanceListController::class, 'list']);

    Route::get('/attendance/detail/{id}', [UserAttendanceDetailController::class, 'detail'])->name('attendance.detail');
});




Route::get('/admin/login', [AdministratorLoginController::class, 'login']);

Route::post('/admin/login', [AdministratorLoginController::class, 'authenticate']);



Route::prefix('admin')->group(function () {
    

    Route::post('/logout', [AdministratorLoginController::class, 'logout']);


    Route::get('/attendance/list', [AdministratorAttendanceListController::class, 'attendancelist']);

    Route::get('/attendance/detail/{id}', [UserAttendanceDetailController::class, 'detail'])->name('admin.attendance.detail');

    Route::get('/attendance/{id}', [AdministratorAttendanceDetailController::class, 'detail']);

    Route::post('/export/staff/{id}', [StaffAttendanceListController::class, 'export']);

    Route::get('/stamp_correction_request/list', [AdministratorApplicationController::class, 'application']);

    Route::post('/approve', [ApprovalController::class, 'approveWork']);

    Route::get('/staff/list', [StaffListController::class, 'list']);

    Route::get('/attendance/staff/{id}', [StaffAttendanceListController::class, 'list']);
});

Route::get('/stamp_correction_request/approve/{work_id}',[ApprovalController::class, 'approval']
)->middleware('auth:admin');

Route::get('/stamp_correction_request/list', [UserApplicationController::class, 'application'])
    ->middleware('auth.any:web,admin');

Route::post('/attendance/correction/apply', [UserAttendanceDetailController::class, 'apply'])
    ->middleware('auth.any:web,admin');














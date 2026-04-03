<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceCorrectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// トップ
Route::get('/', function () {
    return view('welcome');
});

// ログアウト処理（上書き）
Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

// 勤怠打刻画面
Route::middleware('auth')->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock_in');
    Route::post('/attendance/break-start', [AttendanceController::class, 'breakStart'])->name('attendance.break_start');
    Route::post('/attendance/break-end', [AttendanceController::class, 'breakEnd'])->name('attendance.break_end');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock_out');
});

Route::get('/attendance/list', [AttendanceController::class, 'list'])
    ->middleware('auth')
    ->name('attendance.list');

Route::get('/attendance/corrections', [AttendanceCorrectionController::class, 'index'])
    ->middleware('auth')
    ->name('attendance.corrections.index');

Route::get('/attendance/corrections/{correction}', [AttendanceCorrectionController::class, 'show'])
    ->middleware('auth')
    ->name('attendance.corrections.show');

Route::get('/attendance/{attendance}', [AttendanceController::class, 'show'])
    ->middleware('auth')
    ->name('attendance.show');

Route::get('/attendance/{attendance}/edit', [AttendanceController::class, 'edit'])
    ->middleware('auth')
    ->name('attendance.edit');

Route::post('/attendance/{attendance}/edit', [AttendanceController::class, 'update'])
    ->middleware('auth')
    ->name('attendance.update');
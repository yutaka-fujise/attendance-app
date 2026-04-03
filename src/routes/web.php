<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceCorrectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminCorrectionController;

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

    Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('attendance.list');

    Route::get('/attendance/corrections', [AttendanceCorrectionController::class, 'index'])
        ->name('attendance.corrections.index');

    // 勤怠詳細 + 修正申請フォーム
    Route::get('/attendance/{attendance}', [AttendanceController::class, 'show'])
        ->name('attendance.show');

    // 修正申請保存
    Route::post('/attendance/{attendance}', [AttendanceController::class, 'update'])
        ->name('attendance.update');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/corrections', [AdminCorrectionController::class, 'index'])
        ->name('admin.corrections.index');

    Route::get('/corrections/{correction}', [AdminCorrectionController::class, 'show'])
        ->name('admin.corrections.show');

    Route::post('/corrections/{correction}/approve', [AdminCorrectionController::class, 'approve'])
        ->name('admin.corrections.approve');
});
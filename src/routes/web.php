<?php

use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminCorrectionController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceCorrectionController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// トップ
Route::get('/', function () {
    return redirect('/login');
});

// 一般ユーザーログアウト
Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

// 管理者ログイン画面
Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');

// 管理者ログイン処理
Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.login.store');

// 管理者ログアウト
Route::post('/admin/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/admin/login');
})->name('admin.logout');


// ==========================
// メール認証関連（追加部分）
// ==========================

// 認証案内画面
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// 認証処理
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/attendance');
})->middleware(['auth', 'signed'])->name('verification.verify');

// 再送
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// ==========================
// 一般ユーザー機能
// ==========================
Route::middleware(['auth', 'verified'])->group(function () {
    // 勤怠打刻
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock_in');
    Route::post('/attendance/break-start', [AttendanceController::class, 'breakStart'])->name('attendance.break_start');
    Route::post('/attendance/break-end', [AttendanceController::class, 'breakEnd'])->name('attendance.break_end');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock_out');

    // 勤怠一覧
    Route::get('/attendance/list', [AttendanceController::class, 'list'])->name('attendance.list');

    // 申請一覧
    Route::get('/attendance/corrections', [AttendanceCorrectionController::class, 'index'])
        ->name('attendance.corrections.index');

    // 申請詳細
    Route::get('/attendance/corrections/{correction}', [AttendanceCorrectionController::class, 'show'])
        ->name('attendance.corrections.show');

    // 勤怠詳細
    Route::get('/attendance/{attendance}', [AttendanceController::class, 'show'])
        ->name('attendance.show');

    // 修正申請保存
    Route::post('/attendance/{attendance}', [AttendanceController::class, 'update'])
        ->name('attendance.update');
});


// ==========================
// 管理者機能
// ==========================
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->group(function () {
    // 申請一覧
    Route::get('/corrections', [AdminCorrectionController::class, 'index'])
        ->name('admin.corrections.index');

    // 申請詳細
    Route::get('/corrections/{correction}', [AdminCorrectionController::class, 'show'])
        ->name('admin.corrections.show');

    // 申請承認
    Route::post('/corrections/{correction}/approve', [AdminCorrectionController::class, 'approve'])
        ->name('admin.corrections.approve');

    // 日次勤怠一覧
    Route::get('/attendances', [AdminAttendanceController::class, 'index'])
        ->name('admin.attendances.index');

    // 勤怠詳細
    Route::get('/attendances/{attendance}', [AdminAttendanceController::class, 'show'])
        ->name('admin.attendances.show');

    // 勤怠更新
    Route::put('/attendances/{attendance}', [AdminAttendanceController::class, 'update'])
        ->name('admin.attendances.update');

    // スタッフ一覧
    Route::get('/staff', [AdminStaffController::class, 'index'])
        ->name('admin.staff.index');

    // スタッフ別月次勤怠一覧
    Route::get('/staff/{user}/attendances', [AdminAttendanceController::class, 'staffAttendances'])
        ->name('admin.staff.attendances');

    // CSV出力
    Route::get('/staff/{user}/attendances/csv', [AdminAttendanceController::class, 'exportCsv'])
        ->name('admin.staff.attendances.csv');
});
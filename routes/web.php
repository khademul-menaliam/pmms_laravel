<?php

use App\Http\Controllers\BackupController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\GivenLoanController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReminderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TakenLoanController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => auth()->check()
    ? redirect()->route('dashboard')
    : redirect()->route('login')
);

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::resource('categories', CategoryController::class)->except('show');

    Route::resource('incomes', IncomeController::class)->except('show');
    Route::patch('incomes/{income}/mark-paid', [IncomeController::class, 'markPaid'])->name('incomes.mark-paid');

    Route::resource('expenses', ExpenseController::class)->except('show');
    Route::patch('expenses/{expense}/mark-paid', [ExpenseController::class, 'markPaid'])->name('expenses.mark-paid');

    Route::resource('given-loans', GivenLoanController::class)->except('show');
    Route::patch('given-loans/{givenLoan}/mark-returned', [GivenLoanController::class, 'markReturned'])->name('given-loans.mark-returned');

    Route::resource('taken-loans', TakenLoanController::class)->except('show');
    Route::patch('taken-loans/{takenLoan}/mark-paid', [TakenLoanController::class, 'markPaid'])->name('taken-loans.mark-paid');

    Route::resource('reminders', ReminderController::class)->except('show');
    Route::patch('reminders/{reminder}/mark-complete', [ReminderController::class, 'markComplete'])->name('reminders.mark-complete');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');

    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/export', [BackupController::class, 'export'])->name('backup.export');
    Route::post('/backup/import', [BackupController::class, 'import'])->name('backup.import');
    Route::get('/backup/files/{file}', [BackupController::class, 'download'])->name('backup.download');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/users', [SuperAdminController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/block', [SuperAdminController::class, 'block'])->name('users.block');
        Route::patch('/users/{user}/unblock', [SuperAdminController::class, 'unblock'])->name('users.unblock');
        Route::delete('/users/{user}', [SuperAdminController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/impersonate', [SuperAdminController::class, 'impersonate'])->name('users.impersonate');
    });

    Route::post('/admin/stop-impersonating', [SuperAdminController::class, 'stopImpersonating'])->name('admin.stop-impersonating');
});

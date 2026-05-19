<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EarlyReleaseController;
use App\Http\Controllers\LateEntryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pesquisa dinâmica de alunos (JSON)
    Route::middleware('role:coordinator,teacher,gatekeeper')->group(function () {
        Route::get('/api/students/search', [EarlyReleaseController::class, 'search'])->name('students.search');
    });

    // Alunos — rotas específicas ANTES do wildcard {student}
    Route::middleware('role:coordinator')->group(function () {
        Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
        Route::post('/students', [StudentController::class, 'store'])->name('students.store');
        Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
        Route::patch('/students/{student}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

        Route::resource('classrooms', ClassroomController::class);
        Route::resource('users', UserController::class);
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
    });

    // Alunos — leitura (wildcard DEPOIS das rotas específicas)
    Route::middleware('role:coordinator,teacher,gatekeeper')->group(function () {
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    });

    // Liberações — coordenação CRIA (rota específica antes do wildcard)
    Route::middleware('role:coordinator')->group(function () {
        Route::get('/early-releases/create', [EarlyReleaseController::class, 'create'])->name('early-releases.create');
        Route::post('/early-releases', [EarlyReleaseController::class, 'store'])->name('early-releases.store');
        Route::post('/early-releases/{earlyRelease}/cancel', [EarlyReleaseController::class, 'cancel'])->name('early-releases.cancel');
    });

    // Liberações — acesso de leitura para todos os papéis
    Route::middleware('role:coordinator,teacher,gatekeeper')->group(function () {
        Route::get('/early-releases', [EarlyReleaseController::class, 'index'])->name('early-releases.index');
        Route::get('/early-releases/{earlyRelease}', [EarlyReleaseController::class, 'show'])->name('early-releases.show');
    });

    // Professor confirma saída em sala (step 2)
    Route::middleware('role:teacher')->group(function () {
        Route::post('/early-releases/{earlyRelease}/confirm-teacher', [EarlyReleaseController::class, 'confirmTeacher'])->name('early-releases.confirm-teacher');
    });

    // Porteiro confirma saída física (step 3)
    Route::middleware('role:gatekeeper')->group(function () {
        Route::post('/early-releases/{earlyRelease}/confirm', [EarlyReleaseController::class, 'confirm'])->name('early-releases.confirm');
    });

    // Entradas Atrasadas — coordenação cria, professor confirma
    Route::middleware('role:coordinator')->group(function () {
        Route::get('/late-entries/create', [LateEntryController::class, 'create'])->name('late-entries.create');
        Route::post('/late-entries', [LateEntryController::class, 'store'])->name('late-entries.store');
        Route::post('/late-entries/{lateEntry}/cancel', [LateEntryController::class, 'cancel'])->name('late-entries.cancel');
    });

    Route::middleware('role:coordinator,teacher')->group(function () {
        Route::get('/late-entries', [LateEntryController::class, 'index'])->name('late-entries.index');
        Route::get('/late-entries/{lateEntry}', [LateEntryController::class, 'show'])->name('late-entries.show');
    });

    Route::middleware('role:teacher')->group(function () {
        Route::post('/late-entries/{lateEntry}/confirm', [LateEntryController::class, 'confirm'])->name('late-entries.confirm');
    });
});

require __DIR__.'/auth.php';

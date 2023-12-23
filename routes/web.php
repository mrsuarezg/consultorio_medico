<?php

/*
 * This file is part of Consultorio Medico Application.
 * (c) The devcsuarez Team <devcsuarez@gmail.com>
 */

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

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

// Route::get('/',static function (): Response {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Redirección para usuarios no autenticados
Route::middleware(['guest'])->group(static function (): void {
    Route::redirect('/', '/login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(static function (): void {
    Route::get('/dashboard',static function (): Response {
        return Inertia::render('Dashboard', [
            'title' => 'Dashboard',
        ]);
    })->name('dashboard');

    Route::get('/patients',static function (): Response {
        return Inertia::render('Patients/Index', [
            'title' => 'Pacientes',
        ]);
    })->name('patients');

    Route::get('/consultations',static function (): Response {
        return Inertia::render('Consultations/Index', [
            'title' => 'Consultas',
        ]);
    })->name('consultations');

    Route::get('/users',static function (): Response {
        return Inertia::render('Users/Index', [
            'title' => 'Usuarios',
        ]);
    })->name('users');
});

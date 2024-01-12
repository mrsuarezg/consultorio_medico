<?php

/*
 * This file is part of Consultorio Medico Application.
 * (c) The devcsuarez Team <devcsuarez@gmail.com>
 */

use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\PatientsController;
use App\Http\Controllers\PersonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

Route::prefix('v1')
    ->middleware(['auth:api', 'throttle:60,1'])
    ->group(static function (): void {
        Route::prefix('/consultation')->group(static function ():void {
            Route::post('/', [ConsultationController::class, 'search']);
            Route::post('/store', [ConsultationController::class, 'store']);
            Route::put('/{consultation}/update', [ConsultationController::class, 'update']);
        });
        Route::prefix('/patient')->group(static function ():void {
            Route::post('/', [PatientsController::class, 'search']);
            Route::post('/store', [PatientsController::class, 'store']);
            Route::put('/{patient}/update', [PatientsController::class, 'update']);
        });
        Route::prefix('/person')->group(static function ():void {
            Route::post('/', [PersonController::class, 'search']);
            Route::post('/store', [PersonController::class, 'store']);
        });
    });

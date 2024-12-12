<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\AttendanceController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// routes/api.php
Route::get('people', [PersonController::class, 'index']);
Route::post('people', [PersonController::class, 'store']);
Route::get('people/{id}', [PersonController::class, 'show']);
Route::put('people/{id}', [PersonController::class, 'update']);
Route::delete('people/{id}', [PersonController::class, 'destroy']);

Route::get('/people/{unique_code}/remaining-days', [PersonController::class, 'getRemainingDaysByCode']);
Route::get('reports/daily', [ReportController::class, 'dailyReport']);
Route::get('reports/monthly', [ReportController::class, 'monthlyReport']);




// Rotas para entradas
Route::get('attendances', [AttendanceController::class, 'index']);
Route::post('attendances', [AttendanceController::class, 'store']);
Route::get('attendances/{id}', [AttendanceController::class, 'show']);
Route::put('attendances/{id}', [AttendanceController::class, 'update']);
Route::delete('attendances/{id}', [AttendanceController::class, 'destroy']);
Route::get('people/{personId}/attendances', [AttendanceController::class, 'getByPerson']);
Route::get('attendances/stats/{month}', [AttendanceController::class, 'getMonthlyStats']);
Route::get('/monthly-stats/{month}', [AttendanceController::class, 'getMonthlyStats']);


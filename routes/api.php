<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DTRController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/employee/create', [EmployeeController::class, 'create'])->name('api.employee.create');

Route::post('/dtr/create', [DTRController::class, 'create'])->name('api.dtr.create');
Route::get('/dtr/log', [DTRController::class, 'getLogs'])->name('api.dtr.get');


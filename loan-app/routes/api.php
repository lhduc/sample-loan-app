<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PaymentController;
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

Route::prefix('v1')->as('api.v1.')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('login');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [AuthController::class, 'me'])->name('me');

        Route::get('loans', [LoanController::class, 'get'])->name('loan.list');
        Route::post('loans', [LoanController::class, 'create'])->name('loan.create');
        Route::get('loans/{id}', [LoanController::class, 'getDetails'])->name('loan.details');
        Route::post('loans/{id}/approve', [LoanController::class, 'approve'])->name('loan.approve');

        Route::post('payments', [PaymentController::class, 'pay'])->name('pay');
    });
});

<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\{TranslationController, TranslationExportController};

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
	Route::post('/logout', [AuthController::class, 'logout']);

	Route::get('/me', function (Request $request) {
		return $request->user();
	});

	Route::get('/translations', [TranslationController::class, 'index']);
	Route::post('/translations', [TranslationController::class, 'store']);
	Route::put('/translations/{id}', [TranslationController::class, 'update']);
	Route::delete('/translations/{id}', [TranslationController::class, 'destroy']);

	Route::get('/translations/export/{locale}', [TranslationExportController::class, 'export']);
});

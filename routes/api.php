<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/connection_token', [PaymentController::class, 'createConnectionToken']);
Route::post('/create_payment_intent', [PaymentController::class, 'createPaymentIntent']);
Route::post('/create-payment-link', [PaymentController::class, 'createPaymentLink']);

Route::get('/test', function () {
    return response()->json(['message' => 'Test route is working!']);
});
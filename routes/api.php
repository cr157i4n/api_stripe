<?php

use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/create_connection_token', [PaymentController::class, 'createConnectionToken']);
Route::post('/create_payment_intent', [PaymentController::class, 'createPaymentIntent']);
Route::post('/retrieve_payment_method', [PaymentController::class, 'retrievePaymentMethod']);
Route::post('/create_payment_link', [PaymentController::class, 'createPaymentLink']);
Route::post('/store_payment_details', [PaymentController::class, 'storePaymentDetails']);
Route::post('/cancel_payment_intent', [PaymentController::class, 'cancelPaymentIntent']);
Route::post('/resume_payment_intent', [PaymentController::class, 'resumePaymentIntent']);
Route::get('/get_payment_history', [PaymentController::class, 'getPaymentHistory']);


Route::get('/test', function () {
    return response()->json(['message' => 'Test route is working!']);
});

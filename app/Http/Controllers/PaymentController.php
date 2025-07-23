<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Terminal\ConnectionToken;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));
    }

    public function createConnectionToken(Request $request)
    {
        try {
            $token = ConnectionToken::create();
            return response()->json(['secret' => $token->secret]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createPaymentIntent(Request $request)
    {
        try {
            $data = $request->validate([
                'amount' => 'integer|min:1',
                'simulate' => 'boolean',
                'currency' => 'string|in:usd,eur',
                'description' => 'string|max:255|nullable',
            ]);

            $amount = $data['amount'] ?? 1000;
            $simulate = $data['simulate'] ?? false;
            $currency = $data['currency'] ?? 'eur';
            $description = $data['description'] ?? 'Pago genérico';

            $paymentIntentParams = [
                'amount' => $amount,
                'currency' => $currency,
                'payment_method_types' => ['card_present'],
                'capture_method' => 'automatic',
                'metadata' => [
                    'source' => 'tap_to_pay',
                    'environment' => $simulate ? 'test' : 'live',
                    'description' => $description,
                ],
            ];

            $paymentIntent = PaymentIntent::create($paymentIntentParams);
            return response()->json(['client_secret' => $paymentIntent->client_secret]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function retrievePaymentMethod(Request $request)
    {
        try {
            $data = $request->validate([
                'payment_method_id' => 'required|string',
            ]);
            $paymentMethod = PaymentMethod::retrieve($data['payment_method_id']);
            $response = [
                'id' => $paymentMethod->id,
                'card' => [
                    'last4' => $paymentMethod->card->last4 ?? 'N/A',
                    'brand' => $paymentMethod->card->brand ?? 'N/A',
                    'exp_month' => $paymentMethod->card->exp_month ?? 'N/A',
                    'exp_year' => $paymentMethod->card->exp_year ?? 'N/A',
                    'country' => $paymentMethod->card->country ?? 'N/A',
                    'funding' => $paymentMethod->card->funding ?? 'N/A'
                ]
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function createPaymentLink(Request $request)
    {
        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => 'Producto de ejemplo',
                                'description' => 'Descripción del producto',
                            ],
                            'unit_amount' => 2000,
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => url('/payment/success'),
                'cancel_url' => url('/payment/cancel'),
            ]);

            return response()->json([
                'success' => true,
                'checkout_url' => $session->url,
                'session_id' => $session->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el link de pago: ' . $e->getMessage()
            ], 500);
        }
    }
}
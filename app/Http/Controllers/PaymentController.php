<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Terminal\ConnectionToken;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret_key'));
    }

    /**
     * Generate a connection token for Stripe Terminal
     */
    public function createConnectionToken(Request $request)
    {
        try {
            $token = ConnectionToken::create();
            \Log::info('Connection token generated: ' . $token->secret);
            return response()->json(['secret' => $token->secret]);
        } catch (\Exception $e) {
            \Log::error('Error generating connection token: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create a payment intent for Stripe Terminal
     */
    public function createPaymentIntent(Request $request)
    {
        try {
            $data = $request->validate([
                'amount' => 'integer|min:1',
                'simulate' => 'boolean',
            ]);

            $amount = $data['amount'] ?? 1000;
            $simulate = $data['simulate'] ?? false;

            $paymentIntentParams = [
                'amount' => $amount,
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'capture_method' => 'automatic',
            ];

            if ($simulate) {
                $paymentIntentParams['payment_method'] = 'pm_card_visa';
                $paymentIntentParams['confirmation_method'] = 'manual';
            }

            $paymentIntent = PaymentIntent::create($paymentIntentParams);
            \Log::info('PaymentIntent created: ' . $paymentIntent->client_secret);
            return response()->json(['client_secret' => $paymentIntent->client_secret]);
        } catch (\Exception $e) {
            \Log::error('Error creating payment intent: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Create a checkout session for payment link
     */
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
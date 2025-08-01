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
                    'funding' => $paymentMethod->card->funding ?? 'N/A',
                ],
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


    public function cancelPaymentIntent(Request $request)
    {
        try {
            $data = $request->validate([
                'payment_intent_id' => 'required|string',
                'original_payment_intent_id' => 'string|nullable',
            ]);
            $paymentIntent = PaymentIntent::retrieve($data['payment_intent_id']);
            if ($paymentIntent->status !== 'canceled') {
                $paymentIntent->cancel();
            }

            $searchId = $data['original_payment_intent_id'] ?? $data['payment_intent_id'];
            $payment = \App\Models\Pago_Solicitude::where('id_stripe', $searchId)->first();

            if ($payment) {
                $payment->update([
                    'state' => 'PENDING',
                    'id_stripe' => $data['payment_intent_id'],
                    'tarjeta' => null,
                ]);
            } else {
                $newPayment = \App\Models\Pago_Solicitude::create([
                    'state' => 'PENDING',
                    'amount' => (int)($paymentIntent->amount),
                    'description' => $paymentIntent->metadata['description'] ?? 'Sin descripción',
                    'type_coin' => $paymentIntent->currency,
                    'tarjeta' => null,
                    'id_caja' => 4,
                    'id_stripe' => $data['payment_intent_id'],
                ]);
            }

            return response()->json(['success' => true, 'message' => 'Transacción cancelada y marcada como pendiente']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function resumePaymentIntent(Request $request)
{
    try {
        $data = $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        $payment = \App\Models\Pago_Solicitude::where('id_stripe', $data['payment_intent_id'])->first();
        if (!$payment || $payment->state !== 'PENDING') {
            return response()->json(['success' => false, 'error' => 'La transacción no está en estado pendiente'], 400);
        }

        $paymentIntent = PaymentIntent::create([
            'amount' => $payment->amount,
            'currency' => $payment->type_coin,
            'payment_method_types' => ['card_present'],
            'capture_method' => 'automatic',
            'metadata' => [
                'source' => 'tap_to_pay',
                'environment' => config('app.env') === 'local' ? 'test' : 'live',
                'description' => $payment->description,
                'original_payment_intent_id' => $data['payment_intent_id'],
            ],
        ]);

        $payment->update([
            'id_stripe' => $paymentIntent->id,
        ]);


        return response()->json([
            'success' => true,
            'client_secret' => $paymentIntent->client_secret,
            'original_payment_intent_id' => $data['payment_intent_id'],
        ]);
    } catch (\Exception $e) {
        return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
    }
}

        public function storePaymentDetails(Request $request)
        {
            try {
                $data = $request->validate([
                    'payment_intent_id' => 'required|string',
                    'payment_method_id' => 'string|nullable',
                    'card_brand' => 'string|nullable',
                    'card_last4' => 'string|nullable',
                    'card_exp_month' => 'integer|nullable',
                    'card_exp_year' => 'integer|nullable',
                    'card_country' => 'string|nullable',
                    'card_funding' => 'string|nullable',
                    'amount' => 'required|numeric',
                    'currency' => 'required|string',
                    'status' => 'required|string',
                    'description' => 'string|max:255|nullable',
                    'original_payment_intent_id' => 'string|nullable',
                ]);

                $tarjeta = null;
                if ($data['card_brand'] && $data['card_last4']) {
                    $tarjeta = json_encode([
                        'brand' => $data['card_brand'],
                        'last4' => $data['card_last4'],
                        'exp_month' => $data['card_exp_month'] ?? null,
                        'exp_year' => $data['card_exp_year'] ?? null,
                        'country' => $data['card_country'] ?? null,
                        'funding' => $data['card_funding'] ?? null,
                    ]);
                }

                $idCaja = 4;
                $searchId = $data['original_payment_intent_id'] ?? $data['payment_intent_id'];
                $payment = \App\Models\Pago_Solicitude::where('id_stripe', $searchId)->first();

                if ($payment) {
                    $payment->update([
                        'state' => $data['status'],
                        'amount' => (int)($data['amount'] * 100),
                        'description' => $data['description'],
                        'type_coin' => $data['currency'],
                        'tarjeta' => $tarjeta,
                        'id_caja' => $idCaja,
                        'id_stripe' => $data['payment_intent_id'],
                    ]);
                } else {
                    $newPayment = \App\Models\Pago_Solicitude::create([
                        'state' => $data['status'],
                        'amount' => (int)($data['amount'] * 100),
                        'description' => $data['description'],
                        'type_coin' => $data['currency'],
                        'tarjeta' => $tarjeta,
                        'id_caja' => $idCaja,
                        'id_stripe' => $data['payment_intent_id'],
                    ]);
                }

                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
        }

    public function getPaymentHistory(Request $request)
    {
        try {
            $payments = \App\Models\Pago_Solicitude::all();
            $paymentList = $payments->map(function ($payment) {
                $tarjeta = json_decode($payment->tarjeta, true);
                return [
                    'id_stripe' => $payment->id_stripe,
                    'date' => $payment->created_at->format('Y-m-d H:i:s'),
                    'status' => $payment->state,
                    'amount' => $payment->amount / 100,
                    'currency' => $payment->type_coin,
                    'description' => $payment->description ?? 'Sin descripción',
                ];
            })->values();

            return response()->json(['payments' => $paymentList]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
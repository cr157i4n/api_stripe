<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cashier;
use App\Models\Device;
use App\Models\PaymentIntent;
use App\Models\QrDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;

class CashierController extends Controller
{
    public function index()
    {
        $merchant = Auth::user()->merchant;
        
        $cashiers = Cashier::where('merchant_id', $merchant->id)
            ->with(['qrDevices' => function($query) {
                $query->where('status', 'active');
            }])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $stats = [
            'total_cashiers' => $cashiers->count(),
            'active_codes' => QrDevice::whereHas('cashier', function($query) use ($merchant) {
                $query->where('merchant_id', $merchant->id);
            })->where('status', 'active')->count(),
            'max_allowed' => $merchant->max_cash_register,
            'available' => $merchant->max_cash_register - $cashiers->count()
        ];

        return view('client.cashiers.index', compact('cashiers', 'stats', 'merchant'));
    }

    public function create()
    {
        $merchant = Auth::user()->merchant;
        
        $currentCount = Cashier::where('merchant_id', $merchant->id)->count();
        if ($currentCount >= $merchant->max_cash_register) {
            return redirect()->route('client.cashiers.index')
                ->with('error', 'Has alcanzado el límite máximo de cajas permitidas.');
        }

        return view('client.cashiers.create', compact('merchant'));
    }

    public function store(Request $request)
    {
        $merchant = Auth::user()->merchant;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500'
        ]);

        $currentCount = Cashier::where('merchant_id', $merchant->id)->count();
        if ($currentCount >= $merchant->max_cash_register) {
            return back()->with('error', 'Has alcanzado el límite máximo de cajas permitidas.');
        }

        DB::beginTransaction();
        try {
            $cashier = Cashier::create([
                'merchant_id' => $merchant->id,
                'name' => $request->name,
                'location' => $request->location,
                'description' => $request->description,
                'last_activity' => now()
            ]);

            $merchant->increment('active_cash_register');

            DB::commit();

            return redirect()->route('client.cashiers.show', $cashier)
                ->with('success', 'Caja creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear la caja: ' . $e->getMessage());
        }
    }

    public function show(Cashier $cashier)
    {
        
        $cashier->load(['qrDevices' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);

        $activeCode = $cashier->qrDevices()->where('status', 'active')->first();
        
        return view('client.cashiers.show', compact('cashier', 'activeCode'));
    }

    public function generateCode(Cashier $cashier)
    {

        $existingCode = $cashier->qrDevices()->where('status', 'active')->first();
        if ($existingCode) {
            return back()->with('error', 'Esta caja ya tiene un código activo.');
        }

        DB::beginTransaction();
        try {
            $code = $this->generateUniqueCode();
            
            $qrImage = QrCode::format('png')
                ->size(300)
                ->margin(2)
                ->generate($code);
            
            $qrImageBase64 = base64_encode($qrImage);

            $qrDevice = QrDevice::create([
                'cashier_id' => $cashier->id,
                'code' => $code,
                'image_qr' => $qrImageBase64,
                'status' => 'active',
                'expires_at' => Carbon::now()->addHours(24)
            ]);

            DB::commit();

            return redirect()->route('client.cashiers.code-generated', $qrDevice)
                ->with('success', 'Código generado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al generar el código: ' . $e->getMessage());
        }
    }

    public function showGeneratedCode(QrDevice $qrDevice)
    {
        
        if ($qrDevice->status !== 'active') {
            return redirect()->route('client.cashiers.index')
                ->with('error', 'Este código ya no está activo.');
        }

        return view('client.cashiers.code-generated', compact('qrDevice'));
    }

    public function revokeCode(QrDevice $qrDevice)
    {

        $qrDevice->update(['status' => 'expired']);

        return back()->with('success', 'Código revocado exitosamente.');
    }

    public function update(Request $request, Cashier $cashier)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500'
        ]);

        $cashier->update($request->only(['name', 'location', 'description']));

        return back()->with('success', 'Caja actualizada exitosamente.');
    }

    public function destroy(Cashier $cashier)
    {

        DB::beginTransaction();
        try {
            $cashier->qrDevices()->where('status', 'active')->update(['status' => 'expired']);
            
            $cashier->delete();
            
            $cashier->merchant->decrement('active_cash_register');

            DB::commit();

            return redirect()->route('client.cashiers.index')
                ->with('success', 'Caja eliminada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar la caja: ' . $e->getMessage());
        }
    }

    public function linkDevice(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
            'installation_id' => 'required|string|max:75'
        ]);

        $qrDevice = QrDevice::where('code', $request->code)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();

        if (!$qrDevice) {
            return response()->json([
                'success' => false,
                'message' => 'Código inválido o expirado.'
            ], 400);
        }

        DB::beginTransaction();
        try {
            $device = Device::create([
                'merchant_id' => $qrDevice->cashier->merchant_id,
                'cashier_id' => $qrDevice->cashier_id,
                'installation_id' => $request->installation_id,
                'metadata' => json_encode([
                    'linked_at' => now(),
                    'code_used' => $request->code,
                    'device_info' => $request->get('device_info', [])
                ]),
                'status' => 'active'
            ]);

            $qrDevice->update(['status' => 'expired']);

            $qrDevice->cashier->update(['last_activity' => now()]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dispositivo vinculado exitosamente.',
                'device_id' => $device->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al vincular dispositivo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function checkCodeStatus(QrDevice $qrDevice)
    {

        return response()->json([
            'status' => $qrDevice->status,
            'expires_at' => $qrDevice->expires_at,
            'is_active' => $qrDevice->isActive(),
            'is_expired' => $qrDevice->isExpired(),
            'reason' => $qrDevice->status === 'expired' ? 'expired' : null
        ]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $qrDevice = QrDevice::where('code', $request->code)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->first();

        if (!$qrDevice) {
            return response()->json([
                'valid' => false,
                'message' => 'Código inválido o expirado.'
            ], 404);
        }

        return response()->json([
            'valid' => true,
            'cashier_name' => $qrDevice->cashier->name,
            'merchant_name' => $qrDevice->cashier->merchant->name,
            'expires_at' => $qrDevice->expires_at,
            'message' => 'Código válido'
        ]);
    }

    public function getMerchantInfo($code)
    {
        $qrDevice = QrDevice::where('code', $code)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->with(['cashier.merchant'])
            ->first();

        if (!$qrDevice) {
            return response()->json([
                'error' => 'Código no encontrado o expirado'
            ], 404);
        }

        return response()->json([
            'merchant' => [
                'name' => $qrDevice->cashier->merchant->name,
                'nit' => $qrDevice->cashier->merchant->nit,
                'address' => $qrDevice->cashier->merchant->address,
                'phone' => $qrDevice->cashier->merchant->phone
            ],
            'cashier' => [
                'name' => $qrDevice->cashier->name,
                'location' => $qrDevice->cashier->location
            ]
        ]);
    }

    public function deviceStatusWebhook(Request $request)
    {
        $request->validate([
            'installation_id' => 'required|string',
            'status' => 'required|in:online,offline,error',
            'metadata' => 'nullable|array'
        ]);

        $device = Device::where('installation_id', $request->installation_id)->first();

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        $metadata = $device->metadata ?? [];
        $metadata['last_status_update'] = now();
        $metadata['status'] = $request->status;
        if ($request->metadata) {
            $metadata = array_merge($metadata, $request->metadata);
        }

        $device->update(['metadata' => $metadata]);

        $device->cashier->updateLastActivity();

        return response()->json(['success' => true]);
    }

    public function paymentProcessedWebhook(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
            'status' => 'required|string',
            'amount' => 'required|numeric',
            'installation_id' => 'required|string'
        ]);

        $device = Device::where('installation_id', $request->installation_id)->first();

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        $paymentIntent = PaymentIntent::where('stripe_payment_intent_id', $request->payment_intent_id)
            ->where('cashier_id', $device->cashier_id)
            ->first();

        if ($paymentIntent) {
            $paymentIntent->update(['status' => $request->status]);
        }

        $device->cashier->updateLastActivity();

        return response()->json(['success' => true]);
    }

    private function generateUniqueCode()
    {
        do {
            $code = sprintf('%06d', mt_rand(100000, 999999));
        } while (QrDevice::where('code', $code)->where('status', 'active')->exists());

        return $code;
    }
}
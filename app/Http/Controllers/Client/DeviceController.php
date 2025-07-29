<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\LinkingCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        // $commerce = Auth::user();

        $devices = Collect([
            (Object) [
                'id' => 1,
                'device_id' => Str::uuid(),
                'device_name' => 'Caja 1',
                'location' => 'Sucursal Principal',
                'status' => 'active',
                'last_activity' => now()->subMinutes(5),
                'created_at' => now()->subDays(10),
                'description' => 'Dispositivo principal de la sucursal',
            ],
        ]);

        return view('client.devices.index', compact('devices'));
    }

    public function showCreateCodeForm()
    {
        $commerce = Auth::user();

        $activeDevices = 5;

        $activeCodes = collect([
            (object) [
                'id' => 1,
                'code' => '123456',
                'device_name' => 'Caja 1',
                'location' => 'Sucursal Principal',
                'description' => 'Código para vincular Caja 1',
                'expires_at' => now()->addHours(24),
            ],
        ]);

        return view('client.devices.create-code', compact(
            'commerce',
            'activeDevices',
            'activeCodes'
        ));
    }

    public function generateCode(Request $request)
    {
        $commerce = Auth::user();

      
        return redirect()->route('client.devices.code-generated', );
    }

    public function showGeneratedCode()
    {

        return view('client.devices.code-generated', compact('linkingCode'));
    }

    public function revokeCode()
    {
    
        return back()->with('success', 'Código revocado exitosamente.');
    }

    public function toggleStatus(Request $request, Device $device)
    {
    
        return response()->json(['success' => true]);
    }

    public function update(Request $request)
    {
    
        return response()->json(['success' => true]);
    }

    public function destroy(Device $device)
    {
       

        return response()->json(['success' => true]);
    }

    public function linkDevice(Request $request)
    {
        return response()->json([
            'success' => true,
        ]);
    }

    public function heartbeat(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string',
        ]);

        return response()->json(['success' => true]);
    }

    private function generateUniqueCode()
    {
        return sprintf('%06d', mt_rand(100000, 999999));
    }
}

<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $commerce = Auth::user();

        $devicesCount = [
            'active' => 5,
            'inactive' => 4,
            'pending' => 3,
        ];

        $activeCodes = 5;

        $recentActivity = [
            (object)[
                'device_added' => 'Dispositivo "Caja 1" agregado.',
                'code_generated' => 'Código de vinculación generado.',
                'device_removed' => 'Dispositivo "Caja 2" eliminado.',
                'description' => 'Actividad reciente de tu cuenta.',
                'created_at' => now()->subMinutes(10),
            ]
        ];

        return view('client.dashboard', compact(
            'commerce',
            'devicesCount',
            'activeCodes',
            'recentActivity'
        ));
    }
}
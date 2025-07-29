<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cashier;
use App\Models\QrDevice;
use App\Models\Device;
use App\Models\PaymentIntent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();


        return view('client.dashboard', );
    }


}
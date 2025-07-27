<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BranchController extends Controller
{
    /**
     * Datos de prueba
     */
    public function index(Request $request)
    {

        $data = collect([
            (object) [
                'id' => 1,
                'business_name' => 'Comercial El Éxito',
                'nit' => '900123456-7',
                'address' => 'Cra 45 #12-34, Medellín',
                'phone' => '3104567890',
                'api_username' => 'exito_api_user',
                'api_token' => 'tok1234567890abcdefghi',
                'created_at' => now()->subDays(10),
                'is_active' => true
            ],
            (object) [
                'id' => 2,
                'business_name' => 'Tienda Don Pepe',
                'nit' => '800987654-3',
                'address' => 'Cl 10 #23-45, Bogotá',
                'phone' => '3012345678',
                'api_username' => 'donpepe_user',
                'api_token' => 'tokabcdef1234567890xyz',
                'created_at' => now()->subDays(30),
                'is_active' => false
            ],
            (object) [
                'id' => 3,
                'business_name' => 'La Bodega S.A.S.',
                'nit' => '901234567-1',
                'address' => 'Av 68 #50-60, Cali',
                'phone' => '3001112233',
                'api_username' => 'bodega_api',
                'api_token' => 'toklmnop9876543210rstu',
                'created_at' => now()->subDays(5),
                'is_active' => true
            ],
            (object) [
                'id' => 4,
                'business_name' => 'Supermercado Central',
                'nit' => '802345678-0',
                'address' => 'Cra 7 #80-90, Bucaramanga',
                'phone' => '3025556677',
                'api_username' => 'supercentral_api',
                'api_token' => 'tokxyz9876543210lmno',
                'created_at' => now()->subDays(15),
                'is_active' => false
            ]
        ]);
        $perPage = 2;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $pagedData = new LengthAwarePaginator(
            $data->forPage($currentPage, $perPage),
            $data->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('admin.branches.index', ['data' => $pagedData]);
    }

    public function create()
    {
        return view('admin.branches.form');
    }
}

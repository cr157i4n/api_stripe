<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago_Solicitude extends Model
{
    protected $table = 'pago__solicitudes';
    protected $fillable = [
        'state',
        'amount',
        'description',
        'type_coin',
        'tarjeta',
        'id_caja',
        'id_stripe',
    ];
}

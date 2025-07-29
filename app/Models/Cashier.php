<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cashier extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'name',
        'location',
        'description',
        'last_activity'
    ];

    protected $casts = [
        'last_activity' => 'datetime'
    ];

    /**
     * Relación con el comercio
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    /**
     * Relación con códigos QR
     */
    public function qrDevices(): HasMany
    {
        return $this->hasMany(QrDevice::class);
    }

    /**
     * Relación con dispositivos vinculados
     */
    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    /**
     * Relación con intenciones de pago
     */
    public function paymentIntents(): HasMany
    {
        return $this->hasMany(PaymentIntent::class);
    }

    /**
     * Obtener el código QR activo
     */
    public function getActiveQrCodeAttribute()
    {
        return $this->qrDevices()->where('status', 'active')->first();
    }

    /**
     * Verificar si tiene código activo
     */
    public function hasActiveCode(): bool
    {
        return $this->qrDevices()->where('status', 'active')->exists();
    }

    /**
     * Obtener dispositivos activos
     */
    public function getActiveDevicesAttribute()
    {
        return $this->devices()->where('status', 'active')->get();
    }

    /**
     * Actualizar última actividad
     */
    public function updateLastActivity(): void
    {
        $this->update(['last_activity' => now()]);
    }

    /**
     * Scope para buscar por nombre o ubicación
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('location', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Scope para filtrar por comercio
     */
    public function scopeForMerchant($query, $merchantId)
    {
        return $query->where('merchant_id', $merchantId);
    }
}
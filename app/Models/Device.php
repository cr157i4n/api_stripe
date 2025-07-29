<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'cashier_id',
        'installation_id',
        'metadata',
        'status'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * Relación con el comercio
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(Merchant::class);
    }

    /**
     * Relación con la caja
     */
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(Cashier::class);
    }

    /**
     * Relación con intenciones de pago
     */
    public function paymentIntents(): HasMany
    {
        return $this->hasMany(PaymentIntent::class, 'cashier_id', 'cashier_id');
    }

    /**
     * Verificar si está activo
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * Activar dispositivo
     */
    public function activate(): void
    {
        $this->update(['status' => self::STATUS_ACTIVE]);
    }

    /**
     * Desactivar dispositivo
     */
    public function deactivate(): void
    {
        $this->update(['status' => self::STATUS_INACTIVE]);
    }

    /**
     * Scope para dispositivos activos
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope para filtrar por comercio
     */
    public function scopeForMerchant($query, $merchantId)
    {
        return $query->where('merchant_id', $merchantId);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Merchant extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'nit',
        'address',
        'phone',
        'account_username',
        'account_password',
        'image',
        'rate_fixed',
        'rate_porcentage',
        'max_cash_register',
        'active_cash_register',
        'is_active'
    ];

    protected $casts = [
        'rate_fixed' => 'decimal:2',
        'rate_porcentage' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    protected $hidden = [
        'account_password'
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con las cajas
     */
    public function cashiers(): HasMany
    {
        return $this->hasMany(Cashier::class);
    }

    /**
     * Relación con dispositivos
     */
    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    /**
     * Verificar si puede agregar más cajas
     */
    public function canAddMoreCashiers(): bool
    {
        return $this->cashiers()->count() < $this->max_cash_register;
    }

    /**
     * Obtener cajas disponibles
     */
    public function getAvailableCashiersAttribute(): int
    {
        return $this->max_cash_register - $this->cashiers()->count();
    }

    /**
     * Obtener cajas activas
     */
    public function getActiveCashiersAttribute()
    {
        return $this->cashiers()->whereHas('qrDevices', function ($query) {
            $query->where('status', 'active');
        })->get();
    }

    /**
     * Obtener códigos activos
     */
    public function getActiveCodesCountAttribute(): int
    {
        return QrDevice::whereHas('cashier', function ($query) {
            $query->where('merchant_id', $this->id);
        })->where('status', 'active')->count();
    }

    /**
     * Verificar si está activo
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Activar comercio
     */
    public function activate(): void
    {
        $this->update(['is_active' => true]);
    }

    /**
     * Desactivar comercio
     */
    public function deactivate(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Scope para comercios activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope para buscar por NIT
     */
    public function scopeByNit($query, $nit)
    {
        return $query->where('nit', $nit);
    }

    /**
     * Obtener el nombre completo para mostrar
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name . ' (NIT: ' . $this->nit . ')';
    }

    /**
     * Obtener URL de la imagen
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('storage/merchants/' . $this->image);
        }
        
        return asset('images/default-merchant.png');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class QrDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'cashier_id',
        'code',
        'image_qr',
        'status',
        'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_USED = 'used';

    /**
     * Relación con la caja
     */
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(Cashier::class);
    }

    /**
     * Verificar si el código está activo
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE && 
               $this->expires_at > now();
    }

    /**
     * Verificar si el código está expirado
     */
    public function isExpired(): bool
    {
        return $this->status === self::STATUS_EXPIRED || 
               $this->expires_at <= now();
    }

    /**
     * Marcar como expirado
     */
    public function markAsExpired(): void
    {
        $this->update(['status' => self::STATUS_EXPIRED]);
    }

    /**
     * Marcar como usado
     */
    public function markAsUsed(): void
    {
        $this->update(['status' => self::STATUS_USED]);
    }

    /**
     * Obtener tiempo restante en formato legible
     */
    public function getTimeRemainingAttribute(): string
    {
        if ($this->isExpired()) {
            return 'Expirado';
        }

        $now = now();
        $expires = $this->expires_at;
        
        $diff = $expires->diff($now);
        
        if ($diff->days > 0) {
            return $diff->days . ' días restantes';
        } elseif ($diff->h > 0) {
            return $diff->h . ' horas restantes';
        } elseif ($diff->i > 0) {
            return $diff->i . ' minutos restantes';
        } else {
            return 'Menos de 1 minuto';
        }
    }

    /**
     * Scope para códigos activos
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE)
                    ->where('expires_at', '>', now());
    }

    /**
     * Scope para códigos expirados
     */
    public function scopeExpired($query)
    {
        return $query->where(function ($q) {
            $q->where('status', self::STATUS_EXPIRED)
              ->orWhere('expires_at', '<=', now());
        });
    }

    /**
     * Scope para buscar por código
     */
    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    /**
     * Boot method para auto-expirar códigos vencidos
     */
    protected static function boot()
    {
        parent::boot();

        static::retrieved(function ($qrDevice) {
            if ($qrDevice->status === self::STATUS_ACTIVE && $qrDevice->expires_at <= now()) {
                $qrDevice->markAsExpired();
            }
        });
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PaymentIntent extends Model
{
    use HasFactory;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'public_id',
        'cashier_id',
        'currency_id',
        'stripe_payment_intent_id',
        'balance_transaction_id',
        'amount',
        'description',
        'payment_method',
        'receipt_url',
        'billing_detail',
        'payment_method_detail',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'billing_detail' => 'array',
        'payment_method_detail' => 'array'
    ];

    const STATUS_REQUIRES_PAYMENT_METHOD = 'requires_payment_method';
    const STATUS_REQUIRES_CONFIRMATION = 'requires_confirmation';
    const STATUS_REQUIRES_ACTION = 'requires_action';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SUCCEEDED = 'succeeded';
    const STATUS_CANCELED = 'canceled';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid();
            }
            if (empty($model->public_id)) {
                $model->public_id = 'pi_' . Str::random(16);
            }
        });
    }

    /**
     * Relación con la caja
     */
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(Cashier::class);
    }

    /**
     * Relación con la moneda
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Verificar si el pago fue exitoso
     */
    public function isSuccessful(): bool
    {
        return $this->status === self::STATUS_SUCCEEDED;
    }

    /**
     * Verificar si está pendiente
     */
    public function isPending(): bool
    {
        return in_array($this->status, [
            self::STATUS_REQUIRES_PAYMENT_METHOD,
            self::STATUS_REQUIRES_CONFIRMATION,
            self::STATUS_REQUIRES_ACTION,
            self::STATUS_PROCESSING
        ]);
    }

    /**
     * Verificar si fue cancelado
     */
    public function isCanceled(): bool
    {
        return $this->status === self::STATUS_CANCELED;
    }

    /**
     * Marcar como exitoso
     */
    public function markAsSuccessful(): void
    {
        $this->update(['status' => self::STATUS_SUCCEEDED]);
    }

    /**
     * Marcar como cancelado
     */
    public function markAsCanceled(): void
    {
        $this->update(['status' => self::STATUS_CANCELED]);
    }

    /**
     * Scope para pagos exitosos
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', self::STATUS_SUCCEEDED);
    }

    /**
     * Scope para pagos pendientes
     */
    public function scopePending($query)
    {
        return $query->whereIn('status', [
            self::STATUS_REQUIRES_PAYMENT_METHOD,
            self::STATUS_REQUIRES_CONFIRMATION,
            self::STATUS_REQUIRES_ACTION,
            self::STATUS_PROCESSING
        ]);
    }

    /**
     * Scope para filtrar por caja
     */
    public function scopeForCashier($query, $cashierId)
    {
        return $query->where('cashier_id', $cashierId);
    }

    /**
     * Obtener el monto formateado
     */
    public function getFormattedAmountAttribute(): string
    {
        return '$' . number_format($this->amount, 2);
    }
}
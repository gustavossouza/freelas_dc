<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'user_id',
        'payment_method',
        'total_amount',
        'discount',
        'status',
        'payment_status',
        'sale_date',
        'due_date',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'sale_date' => 'date',
        'due_date' => 'date',
    ];

    /**
     * Get the client that owns the sell.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the user that created the sell.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payment method text.
     */
    public function getPaymentMethodTextAttribute()
    {
        switch ($this->payment_method) {
            case 'pix':
                return 'PIX';
            case 'cartao_debito':
                return 'Cartão de Débito';
            case 'cartao_credito':
                return 'Cartão de Crédito';
            case 'boleto':
                return 'Boleto';
            default:
                return 'Não informado';
        }
    }

    /**
     * Get the sell items for this sell.
     */
    public function sellItems()
    {
        return $this->hasMany(SellItem::class);
    }

    /**
     * Get the installments for this sell.
     */
    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    /**
     * Get the subtotal amount (before discount and tax).
     */
    public function getSubtotalAttribute()
    {
        return $this->sellItems()->sum('total_price');
    }

    /**
     * Get the formatted total amount.
     */
    public function getFormattedTotalAmountAttribute()
    {
        return 'R$ ' . number_format($this->total_amount, 2, ',', '.');
    }

    /**
     * Get the formatted discount.
     */
    public function getFormattedDiscountAttribute()
    {
        return 'R$ ' . number_format($this->discount, 2, ',', '.');
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'bg-warning';
            case 'completed':
                return 'bg-success';
            case 'cancelled':
                return 'bg-danger';
            default:
                return 'bg-secondary';
        }
    }

    /**
     * Get the payment status badge class.
     */
    public function getPaymentStatusBadgeClassAttribute()
    {
        switch ($this->payment_status) {
            case 'pending':
                return 'bg-warning';
            case 'paid':
                return 'bg-success';
            case 'overdue':
                return 'bg-danger';
            default:
                return 'bg-secondary';
        }
    }

    /**
     * Get the status text.
     */
    public function getStatusTextAttribute()
    {
        switch ($this->status) {
            case 'pending':
                return 'Pendente';
            case 'completed':
                return 'Concluída';
            case 'cancelled':
                return 'Cancelada';
            default:
                return 'Desconhecido';
        }
    }

    /**
     * Get the payment status text.
     */
    public function getPaymentStatusTextAttribute()
    {
        switch ($this->payment_status) {
            case 'pending':
                return 'Pendente';
            case 'paid':
                return 'Pago';
            case 'overdue':
                return 'Vencido';
            default:
                return 'Desconhecido';
        }
    }

    /**
     * Check if the sell has installments.
     */
    public function hasInstallments()
    {
        return $this->installments()->count() > 0;
    }

    /**
     * Get the total number of installments.
     */
    public function getTotalInstallmentsAttribute()
    {
        return $this->installments()->count();
    }

    /**
     * Get the paid installments count.
     */
    public function getPaidInstallmentsAttribute()
    {
        return $this->installments()->where('status', 'paid')->count();
    }

    /**
     * Get the pending installments count.
     */
    public function getPendingInstallmentsAttribute()
    {
        return $this->installments()->where('status', 'pending')->count();
    }

    /**
     * Get the overdue installments count.
     */
    public function getOverdueInstallmentsAttribute()
    {
        return $this->installments()->where('status', 'overdue')->count();
    }
} 
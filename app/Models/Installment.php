<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Installment extends Model
{
    use HasFactory;

    protected $fillable = [
        'sell_id',
        'installment_number',
        'amount',
        'due_date',
        'status',
        'paid_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];

    /**
     * Get the sell that owns the installment.
     */
    public function sell()
    {
        return $this->belongsTo(Sell::class);
    }

    /**
     * Get the client through the sell.
     */
    public function client()
    {
        return $this->hasOneThrough(Client::class, Sell::class, 'id', 'id', 'sell_id', 'client_id');
    }

    /**
     * Scope a query to only include pending installments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include paid installments.
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Scope a query to only include overdue installments.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', today())->where('status', 'pending');
    }

    /**
     * Scope a query to only include installments due today.
     */
    public function scopeDueToday($query)
    {
        return $query->where('due_date', today());
    }

    /**
     * Scope a query to only include installments due this week.
     */
    public function scopeDueThisWeek($query)
    {
        return $query->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope a query to only include installments due this month.
     */
    public function scopeDueThisMonth($query)
    {
        return $query->whereBetween('due_date', [now()->startOfMonth(), now()->endOfMonth()]);
    }

    /**
     * Get the formatted amount.
     */
    public function getFormattedAmountAttribute()
    {
        return 'R$ ' . number_format($this->amount, 2, ',', '.');
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        switch ($this->status) {
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
            case 'paid':
                return 'Pago';
            case 'overdue':
                return 'Vencido';
            default:
                return 'Desconhecido';
        }
    }

    /**
     * Check if the installment is overdue.
     */
    public function isOverdue()
    {
        return $this->due_date < today() && $this->status === 'pending';
    }

    /**
     * Check if the installment is due today.
     */
    public function isDueToday()
    {
        return $this->due_date->isToday() && $this->status === 'pending';
    }

    /**
     * Check if the installment is due this week.
     */
    public function isDueThisWeek()
    {
        return $this->due_date->between(now()->startOfWeek(), now()->endOfWeek()) && $this->status === 'pending';
    }

    /**
     * Get the days until due date.
     */
    public function getDaysUntilDueAttribute()
    {
        if ($this->status === 'paid') return 0;
        
        $days = $this->due_date->diffInDays(today(), false);
        return $days;
    }

    /**
     * Get the overdue days.
     */
    public function getOverdueDaysAttribute()
    {
        if ($this->status === 'paid' || $this->due_date >= today()) return 0;
        
        return $this->due_date->diffInDays(today());
    }

    /**
     * Get the formatted due date.
     */
    public function getFormattedDueDateAttribute()
    {
        return $this->due_date->format('d/m/Y');
    }

    /**
     * Get the formatted paid date.
     */
    public function getFormattedPaidDateAttribute()
    {
        return $this->paid_date ? $this->paid_date->format('d/m/Y') : null;
    }

    /**
     * Mark the installment as paid.
     */
    public function markAsPaid($paidDate = null)
    {
        $this->update([
            'status' => 'paid',
            'paid_date' => $paidDate ?? today(),
        ]);
    }

    /**
     * Mark the installment as overdue.
     */
    public function markAsOverdue()
    {
        if ($this->status === 'pending' && $this->due_date < today()) {
            $this->update(['status' => 'overdue']);
        }
    }

    /**
     * Get the installment number with prefix.
     */
    public function getFormattedInstallmentNumberAttribute()
    {
        return "Parcela {$this->installment_number}";
    }

    /**
     * Get the client name through the sell.
     */
    public function getClientNameAttribute()
    {
        return $this->sell->client->name ?? 'Cliente não encontrado';
    }

    /**
     * Get the sell total amount.
     */
    public function getSellTotalAttribute()
    {
        return $this->sell->total_amount ?? 0;
    }

    /**
     * Get the formatted sell total.
     */
    public function getFormattedSellTotalAttribute()
    {
        return 'R$ ' . number_format($this->sell_total, 2, ',', '.');
    }

    /**
     * Check if this is the last installment.
     */
    public function isLastInstallment()
    {
        $totalInstallments = $this->sell->installments()->count();
        return $this->installment_number === $totalInstallments;
    }

    /**
     * Get the next installment.
     */
    public function getNextInstallmentAttribute()
    {
        return $this->sell->installments()
            ->where('installment_number', '>', $this->installment_number)
            ->orderBy('installment_number')
            ->first();
    }

    /**
     * Get the previous installment.
     */
    public function getPreviousInstallmentAttribute()
    {
        return $this->sell->installments()
            ->where('installment_number', '<', $this->installment_number)
            ->orderBy('installment_number', 'desc')
            ->first();
    }
} 
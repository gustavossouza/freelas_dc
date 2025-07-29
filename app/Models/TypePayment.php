<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the sells for this type payment.
     */
    public function sells()
    {
        return $this->hasMany(Sell::class);
    }

    /**
     * Scope a query to only include active type payments.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive type payments.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Get the total amount of sells using this payment type.
     */
    public function getTotalAmountAttribute()
    {
        return $this->sells()->sum('total_amount');
    }

    /**
     * Get the total number of sells using this payment type.
     */
    public function getTotalSellsAttribute()
    {
        return $this->sells()->count();
    }

    /**
     * Get the formatted total amount.
     */
    public function getFormattedTotalAmountAttribute()
    {
        return 'R$ ' . number_format($this->total_amount, 2, ',', '.');
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->is_active ? 'bg-success' : 'bg-secondary';
    }

    /**
     * Get the status text.
     */
    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Ativo' : 'Inativo';
    }

    /**
     * Get the usage percentage compared to other payment types.
     */
    public function getUsagePercentageAttribute()
    {
        $totalSells = Sell::count();
        if ($totalSells === 0) return 0;
        
        return round(($this->total_sells / $totalSells) * 100, 2);
    }

    /**
     * Get the average amount per sell for this payment type.
     */
    public function getAverageAmountAttribute()
    {
        if ($this->total_sells === 0) return 0;
        
        return $this->total_amount / $this->total_sells;
    }

    /**
     * Get the formatted average amount.
     */
    public function getFormattedAverageAmountAttribute()
    {
        return 'R$ ' . number_format($this->average_amount, 2, ',', '.');
    }

    /**
     * Check if this payment type is commonly used.
     */
    public function isCommonlyUsed()
    {
        return $this->usage_percentage > 10; // More than 10% of total sells
    }

    /**
     * Get the last sell date using this payment type.
     */
    public function getLastSellDateAttribute()
    {
        $lastSell = $this->sells()->latest('sale_date')->first();
        return $lastSell ? $lastSell->sale_date : null;
    }

    /**
     * Get the most recent sells using this payment type.
     */
    public function getRecentSellsAttribute()
    {
        return $this->sells()->latest('sale_date')->limit(5)->get();
    }
} 
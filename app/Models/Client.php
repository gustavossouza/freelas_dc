<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip_code',
        'document',
        'notes',
    ];

    /**
     * Get the sells for this client.
     */
    public function sells()
    {
        return $this->hasMany(Sell::class);
    }

    /**
     * Get the installments for this client.
     */
    public function installments()
    {
        return $this->hasManyThrough(Installment::class, Sell::class);
    }



    /**
     * Get the formatted phone number.
     */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) return null;
        
        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        
        if (strlen($phone) === 11) {
            return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 5) . '-' . substr($phone, 7);
        } elseif (strlen($phone) === 10) {
            return '(' . substr($phone, 0, 2) . ') ' . substr($phone, 2, 4) . '-' . substr($phone, 6);
        }
        
        return $this->phone;
    }

    /**
     * Get the formatted document.
     */
    public function getFormattedDocumentAttribute()
    {
        if (!$this->document) return null;
        
        $document = preg_replace('/[^0-9]/', '', $this->document);
        
        if (strlen($document) === 11) {
            // CPF
            return substr($document, 0, 3) . '.' . substr($document, 3, 3) . '.' . substr($document, 6, 3) . '-' . substr($document, 9);
        } elseif (strlen($document) === 14) {
            // CNPJ
            return substr($document, 0, 2) . '.' . substr($document, 2, 3) . '.' . substr($document, 5, 3) . '/' . substr($document, 8, 4) . '-' . substr($document, 12);
        }
        
        return $this->document;
    }

    /**
     * Get the full address.
     */
    public function getFullAddressAttribute()
    {
        $parts = [];
        
        if ($this->address) $parts[] = $this->address;
        if ($this->city) $parts[] = $this->city;
        if ($this->state) $parts[] = $this->state;
        if ($this->zip_code) $parts[] = $this->zip_code;
        
        return implode(', ', $parts);
    }

    /**
     * Get the total amount spent by this client.
     */
    public function getTotalSpentAttribute()
    {
        return $this->sells()->sum('total_amount');
    }

    /**
     * Get the total number of purchases by this client.
     */
    public function getTotalPurchasesAttribute()
    {
        return $this->sells()->count();
    }

    /**
     * Get the last purchase date.
     */
    public function getLastPurchaseDateAttribute()
    {
        $lastSell = $this->sells()->latest('sale_date')->first();
        return $lastSell ? $lastSell->sale_date : null;
    }
} 
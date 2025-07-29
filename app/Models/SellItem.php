<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sell_id',
        'product_id',
        'product_name',
        'description',
        'quantity',
        'unit_price',
        'total_price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the sell that owns the sell item.
     */
    public function sell()
    {
        return $this->belongsTo(Sell::class);
    }

    /**
     * Get the product for this sell item.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the formatted unit price.
     */
    public function getFormattedUnitPriceAttribute()
    {
        return 'R$ ' . number_format($this->unit_price, 2, ',', '.');
    }

    /**
     * Get the formatted total price.
     */
    public function getFormattedTotalPriceAttribute()
    {
        return 'R$ ' . number_format($this->total_price, 2, ',', '.');
    }

    /**
     * Calculate the total price based on quantity and unit price.
     */
    public function calculateTotalPrice()
    {
        return $this->quantity * $this->unit_price;
    }

    /**
     * Update the total price based on current quantity and unit price.
     */
    public function updateTotalPrice()
    {
        $this->total_price = $this->calculateTotalPrice();
        $this->save();
    }

    /**
     * Get the product name (from product or stored name).
     */
    public function getDisplayNameAttribute()
    {
        return $this->product_name ?: ($this->product ? $this->product->name : 'Produto não encontrado');
    }

    /**
     * Get the product description (from product or stored description).
     */
    public function getDisplayDescriptionAttribute()
    {
        return $this->description ?: ($this->product ? $this->product->description : '');
    }

    /**
     * Get the product unit (from product).
     */
    public function getProductUnitAttribute()
    {
        return $this->product ? $this->product->unit : 'unidade';
    }
} 
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'item_id',
        'quantity',
        'unit_price',
        'tax_rate',
        'subtotal',
        'tax_amount',
        'total',
    ];

    protected static function booted(): void
    {
        static::created(function ($saleItem) {
            // Reduce item stock when sold
            if ($saleItem->item) {
                $saleItem->item->decrement('stock_quantity', $saleItem->quantity);
            }
        });

        static::deleted(function ($saleItem) {
            // Restore stock if sale item is deleted
            if ($saleItem->item) {
                $saleItem->item->increment('stock_quantity', $saleItem->quantity);
            }
        });

        static::updated(function ($saleItem) {
            if ($saleItem->wasChanged('quantity')) {
                $originalQuantity = $saleItem->getOriginal('quantity');
                $difference = $saleItem->quantity - $originalQuantity;

                if ($difference > 0) {
                    // Sold more → reduce stock
                    $saleItem->item->decrement('stock_quantity', $difference);
                } elseif ($difference < 0) {
                    // Reduced sale quantity → restore stock
                    $saleItem->item->increment('stock_quantity', abs($difference));
                }
            }
        });
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

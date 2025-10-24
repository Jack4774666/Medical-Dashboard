<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'sale_date',
        'subtotal',
        'tax_total',
        'total_amount',
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function calculateTotals(): void
{
    $subtotal = $this->saleItems()->sum('subtotal');
    $taxTotal = $this->saleItems()->sum('tax_amount');
    $total = $subtotal + $taxTotal;

    $this->update([
        'subtotal' => $subtotal,
        'tax_total' => $taxTotal,
        'total_amount' => $total,
    ]);
}

}

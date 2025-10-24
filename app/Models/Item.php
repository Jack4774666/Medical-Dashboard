<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'unit_price',
        'tax_rate',
        'stock_quantity',
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}

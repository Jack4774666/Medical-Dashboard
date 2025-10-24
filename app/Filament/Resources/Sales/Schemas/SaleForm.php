<?php

namespace App\Filament\Resources\Sales\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Forms\Form;
use Illuminate\Support\Str;

class SaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('invoice_number')
                ->default(fn() => 'INV-' . strtoupper(Str::random(6)))
                ->disabled()
                ->dehydrated()
                ->required()
                ->columnSpan(2),

            Forms\Components\DatePicker::make('sale_date')
                ->default(now())
                ->required(),

            Forms\Components\TextInput::make('subtotal')
                ->label('Subtotal ($)')
                ->disabled(),

            Forms\Components\TextInput::make('tax_total')
                ->label('Tax Total ($)')
                ->disabled(),

            Forms\Components\TextInput::make('total_amount')
                ->label('Total ($)')
                ->disabled(),
        
            ])->columns(2);
    }
}

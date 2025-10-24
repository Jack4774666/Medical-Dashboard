<?php

namespace App\Filament\Resources\Items\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use App\Models\Item;
use App\Filament\Resources\ItemResource\Pages;

class ItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\Textarea::make('description'),
            Forms\Components\TextInput::make('unit_price')->numeric()->required(),
            Forms\Components\TextInput::make('tax_rate')->numeric()->default(0),
            Forms\Components\TextInput::make('stock_quantity')->numeric()->default(0),

            ]);
    }
}

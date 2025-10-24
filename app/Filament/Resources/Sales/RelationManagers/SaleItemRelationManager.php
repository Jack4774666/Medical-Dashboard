<?php

namespace App\Filament\Resources\Sales\RelationManagers;

use App\Models\Item;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SaleItemRelationManager extends RelationManager
{
    protected static bool $shouldRefreshParentComponent = true;

    protected static string $relationship = 'saleItems';
    protected static ?string $title = 'Sale Items';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('item_id')
                    ->label('Item')
                    ->relationship('item', 'name')
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state && $item = Item::find($state)) {
                            $set('unit_price', $item->unit_price);
                            $set('tax_rate', $item->tax_rate);
                            static::calc($set, fn($field) => $field);
                        }
                    })
                    ->required(),

                TextInput::make('quantity')
                    ->numeric()
                    ->default(1)
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $set, callable $get) => static::calc($set, $get)),

                TextInput::make('unit_price')
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $set, callable $get) => static::calc($set, $get)),

                TextInput::make('tax_rate')
                    ->numeric()
                    ->suffix('%')
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $set, callable $get) => static::calc($set, $get)),

                TextInput::make('subtotal')
                    ->disabled()
                    ->dehydrated(true),

                TextInput::make('tax_amount')
                    ->disabled()
                    ->dehydrated(true),

                TextInput::make('total')
                    ->disabled()
                    ->dehydrated(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('SaleItem')
            ->columns([
                TextColumn::make('item.name')->label('Item'),
                TextColumn::make('quantity'),
                TextColumn::make('unit_price')->money('usd'),
                TextColumn::make('subtotal')->money('usd'),
                TextColumn::make('tax_amount')->money('usd'),
                TextColumn::make('total')->money('usd')->color('success'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->after(function ($record, $livewire) {
                        $livewire->ownerRecord->refresh()->calculateTotals();
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->after(function ($record, $livewire) {
                        $livewire->ownerRecord->refresh()->calculateTotals();
                    }),
                DeleteAction::make()
                    ->after(function ($record, $livewire) {
                        $livewire->ownerRecord->refresh()->calculateTotals();
                    }),
            ]);
    }

    protected static function calc(callable $set, callable $get): void
    {
        $qty = (float) $get('quantity');
        $price = (float) $get('unit_price');
        $tax = (float) $get('tax_rate');

        $subtotal = $qty * $price;
        $taxAmount = $subtotal * ($tax / 100);
        $total = $subtotal + $taxAmount;

        $set('subtotal', round($subtotal, 2));
        $set('tax_amount', round($taxAmount, 2));
        $set('total', round($total, 2));
    }
}

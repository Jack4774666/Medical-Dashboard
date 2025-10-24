<?php

namespace App\Filament\Resources\Items\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use App\Models\Item;
use App\Filament\Resources\ItemResource\Pages;
class ItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('unit_price')->money('usd'),
            Tables\Columns\TextColumn::make('tax_rate')->suffix('%'),
            Tables\Columns\TextColumn::make('stock_quantity'),
            Tables\Columns\TextColumn::make('created_at')->date(),
            ])

            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockTransactionResource\Pages;
use App\Filament\Resources\StockTransactionResource\RelationManagers;
use App\Models\StockTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StockTransactionResource extends Resource
{
    protected static ?string $model = StockTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('reference_number')->required()->maxLength(255),
                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('warehouse_id')
                    ->label('Warehouse')
                    ->relationship('warehouse', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options([
                        'inbound' => 'Inbound',
                        'outbound' => 'Outbound',
                        'transfer' => 'Transfer',
                        'adjustment' => 'Adjustment',
                    ])->required(),
                Forms\Components\TextInput::make('quantity')->numeric()->required(),
                Forms\Components\TextInput::make('batch_number')->maxLength(255),
                Forms\Components\TextInput::make('serial_number')->maxLength(255),
                Forms\Components\DatePicker::make('expiry_date'),
                Forms\Components\TextInput::make('unit_cost')->numeric()->nullable(),
                Forms\Components\TextInput::make('reason')->maxLength(255),
                Forms\Components\Select::make('source_warehouse_id')
                    ->label('Source Warehouse')
                    ->relationship('sourceWarehouse', 'name')
                    ->searchable()
                    ->nullable(),
                Forms\Components\Select::make('destination_warehouse_id')
                    ->label('Destination Warehouse')
                    ->relationship('destinationWarehouse', 'name')
                    ->searchable()
                    ->nullable(),
                Forms\Components\Select::make('created_by')
                    ->label('Created By')
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('status')->default('completed')->maxLength(255),
                Forms\Components\KeyValue::make('metadata')->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('product.name')->label('Product')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('warehouse.name')->label('Warehouse')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type')->sortable(),
                Tables\Columns\TextColumn::make('quantity')->sortable(),
                Tables\Columns\TextColumn::make('status')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockTransactions::route('/'),
            'create' => Pages\CreateStockTransaction::route('/create'),
            'edit' => Pages\EditStockTransaction::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Bavix\Wallet\Models\Transaction as WalletTransaction;

class WalletTransactionResource extends Resource
{
    protected static ?string $model = WalletTransaction::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Coins & Wallet';
    protected static ?string $navigationLabel = 'Coin Transactions';
    protected static ?string $label = 'Coin Transaction';
    protected static ?string $pluralLabel = 'Coin Transactions';
    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('payable_id')->label('User ID')->sortable(),
                TextColumn::make('type')->sortable(),
                TextColumn::make('amount')->sortable(),
                TextColumn::make('meta')
                    ->label('Meta')
                    ->formatStateUsing(fn($state) => is_array($state) ? json_encode($state) : (string) $state)
                    ->limit(30),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Filter::make('type')->query(fn ($query, $value) => $query->where('type', $value)),
                Filter::make('created_at')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('created_from')->label('From'),
                        \Filament\Forms\Components\DatePicker::make('created_until')->label('Until'),
                    ])
                    ->query(function ($query, $data) {
                        if ($data['created_from']) {
                            $query->whereDate('created_at', '>=', $data['created_from']);
                        }
                        if ($data['created_until']) {
                            $query->whereDate('created_at', '<=', $data['created_until']);
                        }
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->searchable()
            ->actions([
                Tables\Actions\ExportAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\ExportBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\WalletTransactionResource\Pages\ListWalletTransactions::route('/'),
        ];
    }
}

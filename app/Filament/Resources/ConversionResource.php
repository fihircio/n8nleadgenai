<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConversionResource\Pages;
use App\Models\Conversion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ConversionResource extends Resource
{
    protected static ?string $model = Conversion::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'Lead Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('lead_id')
                    ->relationship('lead', 'email')
                    ->required(),
                Forms\Components\Select::make('ai_lead_score_id')
                    ->relationship('aiLeadScore', 'score')
                    ->nullable(),
                Forms\Components\Select::make('conversion_type')
                    ->options(Conversion::getConversionTypes())
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options(Conversion::getStatusOptions())
                    ->required(),
                Forms\Components\TextInput::make('revenue')
                    ->numeric()
                    ->prefix('$')
                    ->required(),
                Forms\Components\TextInput::make('deal_size')
                    ->numeric()
                    ->prefix('$')
                    ->required(),
                Forms\Components\DatePicker::make('conversion_date')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->maxLength(1000),
                Forms\Components\KeyValue::make('metadata')
                    ->label('Additional Data'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lead.email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('conversion_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sale' => 'success',
                        'meeting' => 'info',
                        'demo' => 'warning',
                        'trial' => 'primary',
                        'subscription' => 'danger',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'lost' => 'danger',
                        'delayed' => 'gray',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('revenue')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deal_size')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('conversion_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('conversion_type')
                    ->options(Conversion::getConversionTypes()),
                Tables\Filters\SelectFilter::make('status')
                    ->options(Conversion::getStatusOptions()),
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name'),
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['from'],
                                fn ($query) => $query->whereDate('conversion_date', '>=', $data['from'])
                            )
                            ->when(
                                $data['until'],
                                fn ($query) => $query->whereDate('conversion_date', '<=', $data['until'])
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('conversion_date', 'desc');
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
            'index' => Pages\ListConversions::route('/'),
            'create' => Pages\CreateConversion::route('/create'),
            'edit' => Pages\EditConversion::route('/{record}/edit'),
        ];
    }
} 
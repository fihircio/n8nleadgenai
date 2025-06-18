<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AiLeadScoreResource\Pages;
use App\Models\AiLeadScore;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AiLeadScoreResource extends Resource
{
    protected static ?string $model = AiLeadScore::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'Lead Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('lead_id')
                    ->relationship('lead', 'email')
                    ->required(),
                Forms\Components\TextInput::make('score')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('category')
                    ->required(),
                Forms\Components\KeyValue::make('scoring_factors')
                    ->required(),
                Forms\Components\KeyValue::make('enrichment_data')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('lead.email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('score')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListAiLeadScores::route('/'),
            'create' => Pages\CreateAiLeadScore::route('/create'),
            'edit' => Pages\EditAiLeadScore::route('/{record}/edit'),
        ];
    }
} 
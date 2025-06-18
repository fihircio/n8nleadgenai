<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TemplateListingResource\Pages;
use App\Models\TemplateListing;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TemplateListingResource extends Resource
{
    protected static ?string $model = TemplateListing::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';
    protected static ?string $navigationGroup = 'Marketplace';
    protected static ?string $navigationLabel = 'Templates Listing';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required()->maxLength(255),
                Forms\Components\Select::make('category')
                    ->options([
                        'sourcing' => 'Sourcing',
                        'enrichment' => 'Enrichment',
                        'outreach' => 'Outreach',
                        'automation' => 'Automation',
                        'reporting' => 'Reporting',
                    ])->required(),
                Forms\Components\TextInput::make('icon')->label('Icon Path')->maxLength(255),
                Forms\Components\Textarea::make('description')->rows(2),
                Forms\Components\TextInput::make('coin_cost')->numeric()->minValue(1)->default(1),
                Forms\Components\KeyValue::make('inputs')->label('Inputs (key = name, value = label)'),
                Forms\Components\Textarea::make('sample_output')->rows(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\BadgeColumn::make('category')->colors([
                    'primary' => 'sourcing',
                    'success' => 'enrichment',
                    'warning' => 'outreach',
                    'danger' => 'automation',
                    'info' => 'reporting',
                ]),
                Tables\Columns\TextColumn::make('coin_cost'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTemplateListings::route('/'),
            'create' => Pages\CreateTemplateListing::route('/create'),
            'edit' => Pages\EditTemplateListing::route('/{record}/edit'),
        ];
    }
}

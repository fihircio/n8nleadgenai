<?php

namespace App\Filament\Resources\AiLeadScoreResource\Pages;

use App\Filament\Resources\AiLeadScoreResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAiLeadScores extends ListRecords
{
    protected static string $resource = AiLeadScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
} 
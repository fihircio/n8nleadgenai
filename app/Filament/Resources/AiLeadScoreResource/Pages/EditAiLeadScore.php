<?php

namespace App\Filament\Resources\AiLeadScoreResource\Pages;

use App\Filament\Resources\AiLeadScoreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAiLeadScore extends EditRecord
{
    protected static string $resource = AiLeadScoreResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
} 
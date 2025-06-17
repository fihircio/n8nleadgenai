<?php

namespace App\Filament\Resources\AiTemplateResource\Pages;

use App\Filament\Resources\AiTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAiTemplate extends EditRecord
{
    protected static string $resource = AiTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
} 
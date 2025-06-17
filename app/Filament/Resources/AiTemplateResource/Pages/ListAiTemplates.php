<?php

namespace App\Filament\Resources\AiTemplateResource\Pages;

use App\Filament\Resources\AiTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAiTemplates extends ListRecords
{
    protected static string $resource = AiTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
} 
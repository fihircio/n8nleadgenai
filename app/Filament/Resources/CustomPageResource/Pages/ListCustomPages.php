<?php

namespace App\Filament\Resources\CustomPageResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CustomPageResource;
use LaraZeus\Sky\SkyPlugin;

class ListCustomPages extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = CustomPageResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
            LocaleSwitcher::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return SkyPlugin::get()->getModel('Post')::query()
            ->where('post_type', 'page')
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

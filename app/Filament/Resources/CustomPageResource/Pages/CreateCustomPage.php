<?php

namespace App\Filament\Resources\CustomPageResource\Pages;

use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CustomPageResource;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class CreateCustomPage extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = CustomPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);

        if (isset($data['permission'])) {
            $permission = Permission::findById($data['permission']);
            $record->permissions()->sync([$permission->id]);
        }

        return $record;
    }
}

<?php

namespace App\Filament\Resources\CustomPageResource\Pages;

use Filament\Actions\LocaleSwitcher;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\CustomPageResource;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class EditCustomPage extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = CustomPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            LocaleSwitcher::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        if (isset($data['permission'])) {
            $permission = Permission::findById($data['permission']);
            $record->permissions()->sync([$permission->id]);
        }

        return $record;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['permission'] = $this->record->permissions->first()->id ?? null;
        return $data;
    }
}

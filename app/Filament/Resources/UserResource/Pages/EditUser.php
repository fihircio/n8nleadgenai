<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use STS\FilamentImpersonate\Pages\Actions\Impersonate;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Impersonate::make()->record($this->getRecord())
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->getRecord();
        if (isset($data['coin_balance']) && is_numeric($data['coin_balance'])) {
            $current = $record->getCoinBalance();
            $new = (float) $data['coin_balance'];
            if ($new > $current) {
                $record->addCoins($new - $current);
            } elseif ($new < $current) {
                $record->subtractCoins($current - $new);
            }
        }
        return $data;
    }

    protected function getFormSchema(): array
    {
        return array_merge(parent::getFormSchema(), [
            TextInput::make('coin_balance')
                ->label('Coin Balance')
                ->numeric()
                ->default(fn($record) => $record->getCoinBalance())
                ->required(),
        ]);
    }
}

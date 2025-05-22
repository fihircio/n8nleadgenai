<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $coinBalance = isset($data['coin_balance']) ? (float) $data['coin_balance'] : 0;
        unset($data['coin_balance']);
        $this->afterCreate(function ($record) use ($coinBalance) {
            if ($coinBalance > 0) {
                $record->addCoins($coinBalance);
            }
        });
        return $data;
    }

    protected function getFormSchema(): array
    {
        return array_merge(parent::getFormSchema(), [
            TextInput::make('coin_balance')
                ->label('Coin Balance')
                ->numeric()
                ->default(0)
                ->required(),
        ]);
    }
}

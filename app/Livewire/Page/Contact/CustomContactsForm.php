<?php

namespace App\Livewire\Page\Contact;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use LaraZeus\Wind\Livewire\ContactsForm;
use LaraZeus\Wind\WindPlugin;

class CustomContactsForm extends ContactsForm
{
    protected function getFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    ViewField::make('department_id')
                        ->view(app('windTheme') . '.departments')
                        ->columnSpan([
                            'default' => 1,
                            'md' => 2,
                        ])
                        ->label('')
                        ->hiddenLabel()
                        ->visible(fn (): bool => WindPlugin::get()->hasDepartmentResource()),

                    Section::make()
                        ->schema([
                            Grid::make()
                                ->schema([
                                    TextInput::make('name')
                                        ->required()
                                        ->minLength(2)
                                        ->label(__('name')),

                                    TextInput::make('email')
                                        ->required()
                                        ->email()
                                        ->label(__('email')),
                                ]),

                            TextInput::make('title')
                                ->columnSpan(2)
                                ->required()
                                ->label(__('title')),

                            Textarea::make('message')
                                ->columnSpan(2)
                                ->rows(10)
                                ->required()
                                ->label(__('message')),
                        ])
                        ->columns([
                            'default' => 1,
                            'md' => 2,
                        ]),
                ]),
        ];
    }
}

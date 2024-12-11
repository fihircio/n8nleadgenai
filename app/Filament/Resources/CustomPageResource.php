<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomPageResource\Pages\CreateCustomPage;
use App\Filament\Resources\CustomPageResource\Pages\EditCustomPage;
use App\Filament\Resources\CustomPageResource\Pages\ListCustomPages;
use LaraZeus\Sky\Filament\Resources\PageResource as SkyPageResource;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use LaraZeus\Sky\Models\Post;
use LaraZeus\Sky\SkyPlugin;
use Spatie\Permission\Models\Permission;

class CustomPageResource extends SkyPageResource
{
    public static function form(Form $form): Form
    {
        return $form->schema([
            Tabs::make('post_tabs')->schema([
                Tabs\Tab::make(__('Title & Content'))->schema([
                    TextInput::make('title')
                        ->label(__('Page Title'))
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (Set $set, $state) {
                            $set('slug', Str::slug($state));
                            $set('content', $state);
                        }),
                    config('zeus-sky.editor')::component(),
                ]),
                Tabs\Tab::make(__('SEO'))->schema([
                    Hidden::make('user_id')
                        ->required()
                        ->default(auth()->user()->id),

                    Hidden::make('post_type')
                        ->default('page')
                        ->required(),

                    Textarea::make('description')
                        ->maxLength(255)
                        ->label(__('Description'))
                        ->hint(__('Write an excerpt for your page')),

                    TextInput::make('slug')
                        ->unique(ignorable: fn (?Post $record): ?Post => $record)
                        ->required()
                        ->maxLength(255)
                        ->label(__('Page Slug')),

                    Select::make('parent_id')
                        ->options(SkyPlugin::get()->getModel('Post')::where('post_type', 'page')->pluck(
                            'title',
                            'id'
                        ))
                        ->label(__('Parent Page')),

                    TextInput::make('ordering')
                        ->integer()
                        ->label(__('Page Order'))
                        ->default(1),
                ]),
                Tabs\Tab::make(__('Visibility'))->schema([
                    Select::make('status')
                        ->label(__('Status'))
                        ->default('publish')
                        ->required()
                        ->live()
                        ->options(SkyPlugin::get()->getModel('PostStatus')::pluck('label', 'name')),

                    Select::make('permission')
                        ->label(__('Permission'))
                        ->required()
                        ->options(function () {
                            return Permission::all()->mapWithKeys(function ($permission) {
                                $name = $permission->name;
                                $name = str_replace(['access ', ' features', ' panel'], '', $name);
                                $name = ucfirst($name);
                                return [$permission->id => $name];
                            });
                        })
                        ->preload(),

                    TextInput::make('password')
                        ->label(__('Password'))
                        ->visible(fn (Get $get): bool => $get('status') === 'private'),

                    DateTimePicker::make('published_at')
                        ->label(__('published at'))
                        ->required()
                        ->default(now()),
                ]),
                Tabs\Tab::make(__('Image'))->schema([
                    Placeholder::make(__('Featured Image')),
                    ToggleButtons::make('featured_image_type')
                        ->dehydrated(false)
                        ->hiddenLabel()
                        ->live()
                        ->afterStateHydrated(function (Set $set, Get $get) {
                            $setVal = ($get('featured_image') === null) ? 'upload' : 'url';
                            $set('featured_image_type', $setVal);
                        })
                        ->grouped()
                        ->options([
                            'upload' => __('upload'),
                            'url' => __('url'),
                        ])
                        ->default('upload'),
                    SpatieMediaLibraryFileUpload::make('featured_image_upload')
                        ->collection('pages')
                        ->disk(SkyPlugin::get()->getUploadDisk())
                        ->directory(SkyPlugin::get()->getUploadDirectory())
                        ->visible(fn (Get $get) => $get('featured_image_type') === 'upload')
                        ->label(''),
                    TextInput::make('featured_image')
                        ->label(__('featured image url'))
                        ->visible(fn (Get $get) => $get('featured_image_type') === 'url')
                        ->url(),
                ]),
            ])->columnSpan(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ViewColumn::make('title_card')
                    ->label(__('Title'))
                    ->sortable(['title'])
                    ->searchable(['title'])
                    ->toggleable()
                    ->view('zeus::filament.columns.page-title'),

                ViewColumn::make('status_desc')
                    ->label(__('Status'))
                    ->sortable(['status'])
                    ->searchable(['status'])
                    ->toggleable()
                    ->view('zeus::filament.columns.status-desc')
                    ->tooltip(fn (Post $record): string => $record->published_at->translatedFormat('Y/m/d | H:i A')),

                TextColumn::make('permissions.name')
                    ->label(__('Permission'))
                    ->formatStateUsing(fn ($state) => ucfirst(str_replace(['access ', ' features', ' panel'], '', $state)))
                    ->sortable()
                    ->searchable(),
            ])
            ->defaultSort('id', 'desc')
            ->actions(static::getActions())
            ->bulkActions([
                DeleteBulkAction::make(),
                ForceDeleteBulkAction::make(),
                RestoreBulkAction::make(),
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('status')
                    ->multiple()
                    ->label(__('Status'))
                    ->options(SkyPlugin::get()->getModel('PostStatus')::pluck('label', 'name')),
                Filter::make('password')
                    ->label(__('Password Protected'))
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('password')),
            ]);
    }

	public static function getPages(): array
    {
        return [
            'index' => ListCustomPages::route('/'),
            'create' => CreateCustomPage::route('/create'),
            'edit' => EditCustomPage::route('/{record}/edit'),
        ];
    }
}

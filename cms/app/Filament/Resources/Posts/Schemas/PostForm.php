<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Forms\Components\GutenbergEditor;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Flex::make([
                    Group::make([
                        Section::make('Contenido')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Título')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, $state, callable $set) {
                                        if ($operation === 'create') {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),
                                TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->helperText('URL del post: /blog/{slug}/'),
                                GutenbergEditor::make('content')
                                    ->label('Contenido')
                                    ->columnSpanFull(),
                                Textarea::make('excerpt')
                                    ->label('Extracto')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),

                        Section::make('SEO')
                            ->collapsed()
                            ->schema([
                                TextInput::make('seo_title')
                                    ->label('Título SEO')
                                    ->helperText('Deja vacío para usar el título del post'),
                                TextInput::make('seo_focus_keyword')
                                    ->label('Palabra clave principal'),
                                Textarea::make('seo_description')
                                    ->label('Meta descripción')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Toggle::make('_is_canonical')
                                    ->label('Esta entrada es la URL canónica')
                                    ->helperText(fn ($get) => 'URL de esta entrada: /blog/' . ($get('slug') ?: '{slug}') . '/')
                                    ->dehydrated(false)
                                    ->default(true)
                                    ->live()
                                    ->afterStateHydrated(fn ($component, $record) => $component->state(empty($record?->seo_canonical_url)))
                                    ->afterStateUpdated(function (bool $state, callable $set) {
                                        if ($state) $set('seo_canonical_url', null);
                                    })
                                    ->columnSpanFull(),
                                TextInput::make('seo_canonical_url')
                                    ->label('URL canónica personalizada')
                                    ->url()
                                    ->placeholder('https://ejemplo.com/pagina-original/')
                                    ->helperText('URL de la página original si este contenido existe en otra dirección.')
                                    ->columnSpanFull()
                                    ->hidden(fn ($get) => (bool) $get('_is_canonical')),
                            ]),
                    ])->grow(),

                    Group::make([
                        Section::make('Publicación')
                            ->schema([
                                Select::make('status')
                                    ->label('Estado')
                                    ->options([
                                        'published' => 'Publicado',
                                        'draft'     => 'Borrador',
                                        'private'   => 'Privado',
                                    ])
                                    ->default('published')
                                    ->required(),
                                DateTimePicker::make('published_at')
                                    ->label('Fecha de publicación')
                                    ->default(now()),
                            ]),

                        Section::make('Imagen destacada')
                            ->schema([
FileUpload::make('featured_image')
                                    ->label('Cambiar imagen')
                                    ->image()
                                    ->disk('public')
                                    ->directory('posts')
                                    ->visibility('public')
                                    ->imagePreviewHeight('200')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                                    ->maxSize(8192)
                                    ->rules(['mimes:jpg,jpeg,png,gif,webp'])
                                    ->placeholder('Arrastra una imagen aquí o <span class="filepond--label-action">selecciona una</span>'),
                                TextInput::make('featured_image_alt')
                                    ->label('Texto alternativo'),
                            ]),

                        Section::make('Categorías y Etiquetas')
                            ->schema([
                                Select::make('categories')
                                    ->label('Categorías')
                                    ->relationship('categories', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label('Nombre')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                        TextInput::make('slug')
                                            ->required(),
                                    ]),
                                Select::make('tags')
                                    ->label('Etiquetas')
                                    ->relationship('tags', 'name')
                                    ->multiple()
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label('Nombre')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                        TextInput::make('slug')
                                            ->required(),
                                    ]),
                            ]),
                    ])->grow(false)->extraAttributes(['style' => 'width:320px;min-width:320px;max-width:320px;flex-shrink:0']),
                ])->from('lg'),
            ]);
    }
}

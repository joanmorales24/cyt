<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Grid::make(1)
                            ->columnSpan(2)
                            ->schema([
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
                                        RichEditor::make('content')
                                            ->label('Contenido')
                                            ->toolbarButtons([
                                                'bold', 'italic', 'underline', 'strike',
                                                'link', 'h2', 'h3', 'bulletList', 'orderedList',
                                                'blockquote', 'codeBlock', 'undo', 'redo',
                                            ])
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
                                        TextInput::make('seo_canonical_url')
                                            ->label('URL canónica')
                                            ->url()
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Grid::make(1)
                            ->columnSpan(1)
                            ->schema([
                                Section::make('Publicación')
                                    ->schema([
                                        Select::make('status')
                                            ->label('Estado')
                                            ->options([
                                                'published' => 'Publicado',
                                                'draft' => 'Borrador',
                                                'private' => 'Privado',
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
                                            ->label('Imagen')
                                            ->image()
                                            ->directory('posts')
                                            ->visibility('public')
                                            ->imagePreviewHeight('200'),
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
                            ]),
                    ]),
            ]);
    }
}

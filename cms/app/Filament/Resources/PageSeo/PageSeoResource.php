<?php

namespace App\Filament\Resources\PageSeo;

use App\Models\PageSeo;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PageSeoResource extends Resource
{
    protected static ?string $model = PageSeo::class;

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-magnifying-glass';
    }

    protected static \UnitEnum|string|null $navigationGroup = 'Configuración';
    protected static ?string $navigationLabel = 'SEO de Páginas';
    protected static ?string $modelLabel = 'SEO de Página';
    protected static ?string $pluralModelLabel = 'SEO de Páginas';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Página')->schema([
                Select::make('page')
                    ->label('Página')
                    ->options([
                        'home'      => 'Inicio (cytcomunicaciones.com)',
                        'voice-bot' => 'Voice Bot (cytcomunicaciones.com/voice-bot)',
                    ])
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disabledOn('edit'),
            ]),

            Section::make('Meta tags')->columns(2)->schema([
                TextInput::make('title')
                    ->label('Título SEO')
                    ->helperText('Recomendado: 50–60 caracteres')
                    ->maxLength(70)
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->label('Meta descripción')
                    ->helperText('Recomendado: 140–160 caracteres')
                    ->rows(3)
                    ->maxLength(170)
                    ->columnSpanFull(),
                TextInput::make('focus_keyword')
                    ->label('Palabra clave principal'),
                Select::make('robots')
                    ->label('Robots')
                    ->options([
                        'index, follow'     => 'index, follow (normal)',
                        'noindex, follow'   => 'noindex, follow',
                        'noindex, nofollow' => 'noindex, nofollow',
                    ])
                    ->default('index, follow'),
            ]),

            Section::make('URLs e imagen')->schema([
                TextInput::make('canonical_url')
                    ->label('URL canónica')
                    ->url()
                    ->placeholder('https://cytcomunicaciones.com')
                    ->columnSpanFull(),
                FileUpload::make('og_image')
                    ->label('Imagen OG (Open Graph)')
                    ->helperText('Recomendado: 1200×630px')
                    ->image()
                    ->disk('public')
                    ->directory('seo')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->maxSize(2048),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('page')
                    ->label('Página')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'home'      => '🏠 Inicio',
                        'voice-bot' => '🤖 Voice Bot',
                        default     => $state,
                    }),
                TextColumn::make('title')->label('Título SEO')->limit(50),
                TextColumn::make('description')->label('Descripción')->limit(60),
                TextColumn::make('robots')->label('Robots')->badge(),
            ])
            ->paginated(false);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPageSeo::route('/'),
            'create' => Pages\CreatePageSeo::route('/create'),
            'edit'   => Pages\EditPageSeo::route('/{record}/edit'),
        ];
    }
}

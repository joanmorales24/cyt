<?php

namespace App\Filament\Resources\MediaItems;

use App\Filament\Resources\MediaItems\Pages\ManageMediaItems;
use App\Models\MediaItem;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaItemResource extends Resource
{
    protected static ?string $model = MediaItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $navigationLabel = 'Biblioteca de medios';

    protected static ?string $modelLabel = 'archivo';

    protected static ?string $pluralModelLabel = 'Biblioteca de medios';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->maxLength(255),

                SpatieMediaLibraryFileUpload::make('file')
                    ->label('Archivo')
                    ->collection('default')
                    ->image()
                    ->imageEditor()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(fn () => Media::query()->whereIn('collection_name', ['featured_image', 'default'])->latest())
            ->columns([
                ImageColumn::make('preview')
                    ->label('Vista previa')
                    ->state(fn (Media $record) => $record->hasGeneratedConversion('thumb') ? $record->getUrl('thumb') : $record->getUrl()),

                TextColumn::make('name')->label('Nombre')->searchable(),

                TextColumn::make('model_type')
                    ->label('Usado en')
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        \App\Models\Post::class => 'Entrada de blog',
                        \App\Models\MediaItem::class => 'Biblioteca',
                        default => class_basename($state),
                    })
                    ->badge(),

                TextColumn::make('created_at')->label('Subido')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('delete')
                    ->label('Eliminar')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Media $record) => $record->delete()),
            ])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMediaItems::route('/'),
        ];
    }
}

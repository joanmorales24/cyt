<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;

    public function getTitle(): string
    {
        return 'Nueva entrada';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Guardar entrada')
                ->color('primary')
                ->action('create'),
        ];
    }
}

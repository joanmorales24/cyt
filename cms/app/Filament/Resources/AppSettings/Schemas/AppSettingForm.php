<?php

namespace App\Filament\Resources\AppSettings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AppSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->required()
                    ->maxLength(255)
                    ->disabled(fn (string $operation): bool => $operation === 'edit'),

                TextInput::make('value')
                    ->password()
                    ->revealable()
                    ->nullable()
                    ->maxLength(2000),
            ]);
    }
}

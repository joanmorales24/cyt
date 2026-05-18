<?php

namespace App\Filament\Resources\Leads\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LeadForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Datos del contacto')->schema([
                TextInput::make('name')->label('Nombre')->required(),
                TextInput::make('email')->label('Email')->email(),
                TextInput::make('phone')->label('Teléfono'),
                TextInput::make('company')->label('Empresa'),
                TextInput::make('source')->label('Origen'),
                Textarea::make('message')->label('Mensaje')->rows(4)->columnSpanFull(),
            ])->columns(2),
        ]);
    }
}

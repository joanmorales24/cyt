<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadNotificationEmailResource\Pages;
use App\Models\LeadNotificationEmail;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters;
use Filament\Tables\Table;

class LeadNotificationEmailResource extends Resource
{
    protected static ?string $model = LeadNotificationEmail::class;

    protected static ?string $navigationLabel = 'Correos de notificación';

    protected static ?string $pluralModelLabel = 'Correos de notificación';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Correo electrónico'),
                Forms\Components\Toggle::make('active')
                    ->default(true)
                    ->label('Activo'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')
                    ->label('Correo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Filters\TernaryFilter::make('active')
                    ->label('Estado')
                    ->placeholder('Todos')
                    ->trueLabel('Activos')
                    ->falseLabel('Inactivos'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeadNotificationEmails::route('/'),
            'create' => Pages\CreateLeadNotificationEmail::route('/create'),
            'edit' => Pages\EditLeadNotificationEmail::route('/{record}/edit'),
        ];
    }
}

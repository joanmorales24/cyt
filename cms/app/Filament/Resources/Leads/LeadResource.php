<?php

namespace App\Filament\Resources\Leads;

use App\Filament\Resources\Leads\Pages\CreateLead;
use App\Filament\Resources\Leads\Pages\EditLead;
use App\Filament\Resources\Leads\Pages\ListLeads;
use App\Models\Lead;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static \UnitEnum|string|null $navigationGroup = 'CRM';
    protected static ?string $navigationLabel = 'Leads';
    protected static ?int    $navigationSort  = 10;

    public static function form(Schema $schema): Schema
    {
        return \App\Filament\Resources\Leads\Schemas\LeadForm::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListLeads::route('/'),
            'create' => CreateLead::route('/create'),
            'edit'   => EditLead::route('/{record}/edit'),
        ];
    }
}

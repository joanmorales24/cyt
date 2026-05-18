<?php

namespace App\Filament\Resources\Leads\Pages;

use App\Filament\Resources\Leads\LeadResource;
use App\Filament\Resources\Leads\Tables\LeadsTable;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    public function table(Table $table): Table
    {
        return LeadsTable::configure($table);
    }

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}

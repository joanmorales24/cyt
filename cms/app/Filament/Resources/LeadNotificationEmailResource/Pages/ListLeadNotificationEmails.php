<?php

namespace App\Filament\Resources\LeadNotificationEmailResource\Pages;

use App\Filament\Resources\LeadNotificationEmailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeadNotificationEmails extends ListRecords
{
    protected static string $resource = LeadNotificationEmailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

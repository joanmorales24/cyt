<?php

namespace App\Filament\Resources\LeadNotificationEmailResource\Pages;

use App\Filament\Resources\LeadNotificationEmailResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeadNotificationEmail extends EditRecord
{
    protected static string $resource = LeadNotificationEmailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

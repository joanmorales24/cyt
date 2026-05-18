<?php

namespace App\Filament\Resources\PageSeo\Pages;

use App\Filament\Resources\PageSeo\PageSeoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPageSeo extends ListRecords
{
    protected static string $resource = PageSeoResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}

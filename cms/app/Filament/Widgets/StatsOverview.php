<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use App\Models\Post;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Entradas publicadas', Post::where('status', 'published')->count())
                ->description('Total de posts publicados')
                ->icon('heroicon-o-document-text')
                ->color('success'),

            Stat::make('Borradores', Post::where('status', 'draft')->count())
                ->description('Posts en borrador')
                ->icon('heroicon-o-pencil-square')
                ->color('warning'),

            Stat::make('Leads recibidos', class_exists(Lead::class) ? Lead::count() : 0)
                ->description('Total de leads en CRM')
                ->icon('heroicon-o-users')
                ->color('info'),
        ];
    }
}

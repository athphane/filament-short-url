<?php

namespace App\Filament\Resources\ShortUrlResource\Widgets;

use AshAllenDesign\ShortURL\Models\ShortURL;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ShortURLResourceStats extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Short URLs', ShortURL::count())
                ->icon('heroicon-o-link'),

            Card::make('Active Short URLs', ShortURL::where('deactivated_at', '>=', now())->orWhereNull('deactivated_at')->count())
                ->icon('heroicon-o-check'),

            Card::make('Deactivated Short URLs', ShortURL::where('deactivated_at', '<=', now())->count())
                ->icon('heroicon-o-x'),
        ];
    }
}

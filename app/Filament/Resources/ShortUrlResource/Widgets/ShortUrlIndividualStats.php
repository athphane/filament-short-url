<?php

namespace App\Filament\Resources\ShortUrlResource\Widgets;

use AshAllenDesign\ShortURL\Models\ShortURL;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ShortUrlIndividualStats extends BaseWidget
{
    public ?ShortURL $record = null;

    protected function getCards(): array
    {
        $url_visits_chart = $this->record->visits()
            ->select(['id', 'visited_at'])
            ->orderBy('visited_at')
            ->get()
            ->groupBy(function ($item) {
                return $item->visited_at->format('d-m-y');
            })->transform(function ($item, $key) {
                return $item->count();
            });

        return [
            Card::make('Total Opens', $this->record->visits()->count())
                ->chart($url_visits_chart->values()->toArray())
                ->color('success'),

            Card::make('Last Opened', $this->record->visits()->latest('visited_at')->first()?->visited_at?->diffForHumans())
        ];
    }
}

<?php

namespace App\Filament\Resources\ShortUrlResource\Widgets\ShortUrlVisitsWidgets;

use AshAllenDesign\ShortURL\Models\ShortURL;
use Filament\Widgets\BarChartWidget;
use Filament\Widgets\PieChartWidget;

class OperatingSystems extends BarChartWidget
{
    public ?ShortURL $record = null;

    /**
     * @return string|null
     */
    public function getHeading(): ?string
    {
        return __('Operating Systems');
    }

    protected function getData(): array
    {
        $operating_systems = $this->record->visits()
            ->select(['id', 'operating_system'])
            ->get()
            ->groupBy(function ($item) {
                return $item->operating_system;
            })->transform(function ($item, $key) {
                return $item->count();
            })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Operating Systems',
                    'data'  => array_values($operating_systems),
                ],
            ],
            'labels'   => collect(array_keys($operating_systems))
                ->transform(fn($item) => str($item)->title()),

        ];

    }

}

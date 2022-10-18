<?php

namespace App\Filament\Resources\ShortUrlResource\Widgets\ShortUrlVisitsWidgets;

use App\Filament\Support\CustomFilamentCharts;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Collection;

class DeviceTypesWidget extends BarChartWidget
{
    use CustomFilamentCharts;

    public ?ShortURL $record = null;

    /**
     * @return string|null
     */
    public function getHeading(): ?string
    {
        return __('Device Types');
    }

    public function getChartQuery(): array
    {
        return $this->record->visits()
            ->select(['id', 'device_type'])
            ->get()
            ->groupBy(function ($item) {
                return $item->device_type;
            })->transform(function ($item, $key) {
                return $item->count();
            })->toArray();
    }

    public function getLabelName(): string
    {
        return 'Devices';
    }

    public function formatLabelsUsing(array $labels): array|Collection
    {
        return collect($labels)
            ->transform(fn($item) => str($item)->title());
    }
}

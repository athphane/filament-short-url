<?php

namespace App\Filament\Resources\ShortUrlResource\Widgets\ShortUrlVisitsWidgets;

use App\Filament\Support\CustomFilamentCharts;
use Arr;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Collection;

class BrowsersWidget extends BarChartWidget
{
    use CustomFilamentCharts;

    public ?ShortURL $record = null;

    public ?string $filter = 'today';

    /**
     * @return string|null
     */
    public function getHeading(): ?string
    {
        return __('Browsers');
    }

    protected function getFilters(): ?array
    {
        return [
            'today'      => 'Today',
            'last_week'  => 'Last week',
            'last_month' => 'Last month',
            'this_year'  => 'This year',
        ];
    }

    public function getDateRangesForFilter(): array
    {
        [$start_at, $end_at] = [now(), now()];

        if ($this->filter == 'today') {
            [$start_at, $end_at] = [now(), now()];
        }

        if ($this->filter == 'last_week') {
            [$start_at, $end_at] = [now()->subWeek(), now()];
        }


        if ($this->filter == 'last_month') {
            [$start_at, $end_at] = [now()->subMonth(), now()];
        }


        if ($this->filter == 'this_year') {
            [$start_at, $end_at] = [now()->startOfYear(), now()->endOfYear()];
        }


        return [$start_at, $end_at];
    }

    public function formatLabelsUsing(array $labels): array|Collection
    {
        return collect($labels)->transform(fn($item) => str($item)->title())->toArray();
    }

    public function getChartQuery(): array
    {
        return $this->record->visits()
            ->select(['id', 'browser'])
            ->whereBetween('visited_at', $this->getDateRangesForFilter())
            ->get()
            ->groupBy(function ($item) {
                return $item->browser;
            })->transform(function ($item, $key) {
                return $item->count();
            })->toArray();
    }

    public function getLabelName(): string
    {
        return '';
    }
}

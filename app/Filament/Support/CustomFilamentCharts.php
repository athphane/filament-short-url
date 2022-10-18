<?php

namespace App\Filament\Support;

use Illuminate\Support\Collection;

trait CustomFilamentCharts
{
    protected array $colors = [
        'backgroundColor' => [
            'rgba(255, 99, 132, 0.2)',
            'rgba(255, 159, 64, 0.2)',
            'rgba(255, 205, 86, 0.2)',
            'rgba(75, 192, 192, 0.2)',
            'rgba(54, 162, 235, 0.2)',
            'rgba(153, 102, 255, 0.2)',
            'rgba(201, 203, 207, 0.2)'
        ],
        'borderColor'     => [
            'rgb(255, 99, 132)',
            'rgb(255, 159, 64)',
            'rgb(255, 205, 86)',
            'rgb(75, 192, 192)',
            'rgb(54, 162, 235)',
            'rgb(153, 102, 255)',
            'rgb(201, 203, 207)'
        ],
        'borderWidth'     => 1
    ];

    abstract public function getChartQuery(): array;

    abstract public function getLabelName(): string;

    public function getLabelData(): array
    {
        return array_keys($this->getChartQuery());
    }

    public function getSeriesData(): array
    {
        return array_values($this->getChartQuery());
    }

    public function formatLabelsUsing(array $labels): array|Collection
    {
        return $labels;
    }

    public function getCustomChartData(array $series_data, array $labels): array
    {
        $data = [
            'datasets' => [],
            'labels'   => $this->formatLabelsUsing($labels),
        ];

        $temp = [
            'label' => $this->getLabelName(),
            'data'  => $series_data
        ];

        $temp = $this->applyColors($temp, count($series_data));

        $data['datasets'][] = $temp;

        return $data;
    }


    private function applyColors(array $temp, int $series_size): array
    {
        $color_array = ['backgroundColor', 'borderColor'];

        foreach ($this->colors as $color_key => $colors) {
            if (in_array($color_key, $color_array)) {
                $temp[$color_key] = array_slice($colors, 0, $series_size);
            } else {
                $temp[$color_key] = $colors;
            }
        }

        return $temp;
    }

    public function getData(): array
    {
        return $this->getCustomChartData($this->getSeriesData(), $this->getLabelData());
    }
}

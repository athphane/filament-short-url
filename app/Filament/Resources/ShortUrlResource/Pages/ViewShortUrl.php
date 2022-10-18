<?php

namespace App\Filament\Resources\ShortUrlResource\Pages;

use App\Filament\Resources\ShortUrlResource;
use Filament\Resources\Pages\ViewRecord;

class ViewShortUrl extends ViewRecord
{
    protected static string $resource = ShortUrlResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            ShortUrlResource\Widgets\ShortUrlIndividualStats::class,
            ShortUrlResource\Widgets\ShortUrlVisitsWidgets\DeviceTypesWidget::class,
            ShortUrlResource\Widgets\ShortUrlVisitsWidgets\BrowsersWidget::class,
            ShortUrlResource\Widgets\ShortUrlVisitsWidgets\OperatingSystems::class
        ];
    }
}

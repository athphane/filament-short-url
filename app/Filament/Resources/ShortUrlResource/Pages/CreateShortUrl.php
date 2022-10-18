<?php

namespace App\Filament\Resources\ShortUrlResource\Pages;

use App\Filament\Resources\ShortUrlResource;
use AshAllenDesign\ShortURL\Classes\Builder;
use Carbon\Carbon;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateShortUrl extends CreateRecord
{
    protected static string $resource = ShortUrlResource::class;

    /**
     * @throws \AshAllenDesign\ShortURL\Exceptions\ShortURLException
     */
    protected function handleRecordCreation(array $data): Model
    {
        $builder = new Builder();

        $short_url = $builder->destinationUrl($data['destination_url']);

        if ($value = data_get($data, 'url_key')) {
            $short_url->urlKey($value);
        }

        if ($value = data_get($data, 'deactivated_at')) {
            $short_url->deactivateAt(Carbon::parse($value));
        }

        $short_url->redirectStatusCode(
            data_get($data, 'redirect_status_code', config('short-url.default_redirect_status_code'))
        );

        if ($value = data_get($data, 'single_use')) {
            $short_url->singleUse($value);
        }

        if ($value = data_get($data, 'forward_query_params')) {
            $short_url->forwardQueryParams($value);
        }

        if ($value = data_get($data, 'track_visits')) {
            $short_url->trackVisits($value);
        }

        if ($value = data_get($data, 'track_ip_address')) {
            $short_url->trackIPAddress($value);
        }

        if ($value = data_get($data, 'track_operating_system')) {
            $short_url->trackOperatingSystem($value);
        }

        if ($value = data_get($data, 'track_operating_system_version')) {
            $short_url->trackOperatingSystemVersion($value);
        }

        if ($value = data_get($data, 'track_browser')) {
            $short_url->trackBrowser($value);
        }

        if ($value = data_get($data, 'track_referer_url')) {
            $short_url->trackRefererURL($value);
        }

        if ($value = data_get($data, 'track_device_type')) {
            $short_url->trackDeviceType($value);
        }

        return $short_url->make();
    }
}

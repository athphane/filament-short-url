<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShortUrlResource\Pages;
use App\Filament\Resources\ShortUrlResource\RelationManagers;
use App\Filament\Resources\ShortUrlResource\Widgets\ShortURLResourceStats;
use AshAllenDesign\ShortURL\Models\ShortURL;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class ShortUrlResource extends Resource
{
    protected static ?string $model = ShortUrl::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $slug = 'short-urls';

    protected static function getNavigationLabel(): string
    {
        return __('Short URLs');
    }

    public static function getModelLabel(): string
    {
        return __('Short URL');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()->schema([
                    Forms\Components\TextInput::make('destination_url')
                        ->required()
                        ->url(),

                    Forms\Components\TextInput::make('url_key')
                        ->required(),

                    Forms\Components\DateTimePicker::make('deactivated_at')
                        ->nullable(),

                    Forms\Components\Select::make('redirect_status_code')
                        ->label(__('Redirect Type'))
                        ->options([
                            301 => 'Permanent',
                            302 => 'Temporary'
                        ])
                        ->default(config('short-url.default_redirect_status_code'))
                        ->required(),
                ])->columns(2),

                Forms\Components\Section::make(__('Configure Tracking'))->schema([
                    Forms\Components\Grid::make(4)->schema([
                        Forms\Components\Toggle::make('single_use')
                            ->default(false),

                        Forms\Components\Toggle::make('forward_query_params')
                            ->default(false),
                    ]),

                    Forms\Components\Grid::make(4)->schema([
                        Forms\Components\Toggle::make('track_visits')
                            ->default(config('short-url.tracking.default_enabled')),

                        Forms\Components\Toggle::make('track_ip_address')
                            ->default(config('short-url.tracking.default_enabled') && config('short-url.tracking.fields.ip_address')),

                        Forms\Components\Toggle::make('track_operating_system')
                            ->default(config('short-url.tracking.default_enabled') && config('short-url.tracking.fields.operating_system')),

                        Forms\Components\Toggle::make('track_operating_system_version')
                            ->default(config('short-url.tracking.default_enabled') && config('short-url.tracking.fields.operating_system_version')),

                        Forms\Components\Toggle::make('track_browser')
                            ->default(config('short-url.tracking.default_enabled') && config('short-url.tracking.fields.browser')),

                        Forms\Components\Toggle::make('track_browser_version')
                            ->default(config('short-url.tracking.default_enabled') && config('short-url.tracking.fields.browser_version')),

                        Forms\Components\Toggle::make('track_referer_url')
                            ->default(config('short-url.tracking.default_enabled') && config('short-url.tracking.fields.referer_url')),

                        Forms\Components\Toggle::make('track_device_type')
                            ->default(config('short-url.tracking.default_enabled') && config('short-url.tracking.fields.device_type')),
                    ]),

                ])
                    ->columns(4),
            ]);

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('destination_url'),

                Tables\Columns\TextColumn::make('url_key')
                    ->url(function ($record) {
                        return $record->default_short_url;
                    })
                    ->openUrlInNewTab(),

                Tables\Columns\TextColumn::make('visits_count'),

                Tables\Columns\TextColumn::make('last_visit_on')
                    ->getStateUsing(function ($record) {
                        return $record->visits()->latest('visited_at')->first()?->visited_at->diffForHumans() ?? __('Not Visited');
                    }),

                Tables\Columns\BooleanColumn::make('active')
                    ->getStateUsing(function ($record) {
                        if ($date = $record->deactivated_at) {
                            return ! $date->isPast();
                        }
                        return true;
                    })
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->color('primary'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getRouteBaseName(): string
    {
        return parent::getRouteBaseName(); // TODO: Change the autogenerated stub
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListShortUrls::route('/'),
            'create' => Pages\CreateShortUrl::route('/create'),
            'edit'   => Pages\EditShortUrl::route('/{record}/edit'),
            'view'   => Pages\ViewShortUrl::route('/{record}'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ShortURLResourceStats::class,
        ];
    }
}

<?php

namespace App\Filament\Resources\AttendeeResource\Pages;

use App\Filament\Resources\{AttendeeResource,
    AttendeeResource\Widgets\AttendeeChartWidget,
    AttendeeResource\Widgets\AttendeesStatsWidget};
use Filament\{Actions, Pages\Concerns\ExposesTableToWidgets, Resources\Pages\ListRecords};

class ListAttendees extends ListRecords
{
    use ExposesTableToWidgets;
    protected static string $resource = AttendeeResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            AttendeesStatsWidget::class,
            AttendeeChartWidget::class
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\AttendeeResource\Widgets;

use App\Filament\Resources\AttendeeResource\Pages\ListAttendees;
use Filament\Widgets\{Concerns\InteractsWithPageTable, StatsOverviewWidget as BaseWidget, StatsOverviewWidget\Stat};

class AttendeesStatsWidget extends BaseWidget
{
    use InteractsWithPageTable;

    protected function getTablePage(): string
    {
        return ListAttendees::class;
    }

    protected function getColumns(): int
    {
        return 2;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Attendees Count', $this->getPageTableQuery()->count())
                ->description('Total number of attendees')
                ->descriptionIcon('heroicon-o-user-group'),
//                ->color('success')
//                ->chart([1,2,3,4,5,4,1,1]),
            Stat::make('Total Revenue', $this->getPageTableQuery()->sum('ticket_cost')/100),
        ];
    }
}
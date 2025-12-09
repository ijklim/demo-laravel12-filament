<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Member;
use App\Models\CourtBooking;

class DashboardStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
             Stat::make('Energy Saved', (CourtBooking::count() * 5) . ' kWh')
                ->description('5 kWh saved per booking by using natural light')
                ->descriptionIcon('heroicon-m-bolt')
                ->color('success'),
            Stat::make('Active Members', Member::where('active', true)->count())
                ->description('Members currently active')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
            Stat::make('Total Bookings', CourtBooking::count())
                 ->description('All time bookings')
                 ->color('info'),
        ];
    }
}

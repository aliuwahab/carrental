<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Vehicles', Vehicle::count())
                ->description('Available for rental')
                ->descriptionIcon('heroicon-o-truck')
                ->color('success'),
            
            Stat::make('Total Bookings', Booking::count())
                ->description('All time bookings')
                ->descriptionIcon('heroicon-o-calendar-days')
                ->color('info'),
            
            Stat::make('Confirmed Bookings', Booking::where('status', 'confirmed')->count())
                ->description('Active rentals')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),
            
            Stat::make('Pending Bookings', Booking::where('status', 'pending')->count())
                ->description('Awaiting payment')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),
            
            Stat::make('Total Customers', User::where('role', 'customer')->count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-o-users')
                ->color('info'),
            
            Stat::make('Revenue', '$' . number_format(Booking::where('status', 'confirmed')->sum('total_amount'), 2))
                ->description('From confirmed bookings')
                ->descriptionIcon('heroicon-o-currency-dollar')
                ->color('success'),
        ];
    }
}

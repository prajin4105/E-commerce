<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $failedOrders = Order::where('status', 'failed')->count();

        return [
            Stat::make('Total Orders', $totalOrders)
                ->description('All time orders')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('gray'),

            Stat::make('Total Revenue', '₹' . number_format($totalRevenue, 2))
                ->description('Revenue from paid orders')
                ->descriptionIcon('heroicon-m-currency-rupee')
                ->color('success'),

            Stat::make('Pending Orders', $pendingOrders)
                ->description('Orders awaiting processing')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Processing Orders', $processingOrders)
                ->description('Orders being processed')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('info'),

            Stat::make('Completed Orders', $completedOrders)
                ->description('Successfully delivered orders')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Failed Orders', $failedOrders)
                ->description('Failed or cancelled orders')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
} 
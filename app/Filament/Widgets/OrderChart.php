<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class OrderChart extends ChartWidget
{
    protected static ?string $heading = 'Order Trends';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = $this->getOrdersPerDay();

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data['orders'],
                    'borderColor' => '#10B981',
                    'backgroundColor' => '#10B981',
                ],
                [
                    'label' => 'Revenue',
                    'data' => $data['revenue'],
                    'borderColor' => '#3B82F6',
                    'backgroundColor' => '#3B82F6',
                ],
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }

    private function getOrdersPerDay(): array
    {
        $days = collect(range(6, 0))->map(function ($day) {
            return Carbon::now()->subDays($day);
        });

        $orders = $days->mapWithKeys(function ($date) {
            return [
                $date->format('M d') => [
                    'orders' => Order::whereDate('created_at', $date)->count(),
                    'revenue' => Order::whereDate('created_at', $date)
                        ->where('payment_status', 'paid')
                        ->sum('total_amount'),
                ],
            ];
        });

        return [
            'labels' => $orders->keys()->toArray(),
            'orders' => $orders->pluck('orders')->toArray(),
            'revenue' => $orders->pluck('revenue')->toArray(),
        ];
    }
} 
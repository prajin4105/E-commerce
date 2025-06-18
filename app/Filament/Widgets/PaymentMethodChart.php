<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\ChartWidget;

class PaymentMethodChart extends ChartWidget
{
    protected static ?string $heading = 'Payment Methods';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $payments = Payment::selectRaw('payment_method, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Payment Methods',
                    'data' => $payments->pluck('count')->toArray(),
                    'backgroundColor' => [
                        '#10B981', // Green
                        '#3B82F6', // Blue
                        '#F59E0B', // Yellow
                        '#EF4444', // Red
                        '#8B5CF6', // Purple
                    ],
                ],
            ],
            'labels' => $payments->pluck('payment_method')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
            ],
        ];
    }
} 
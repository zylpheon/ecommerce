<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue by Status';
    protected static ?int $sort = 3;
    public ?string $filter = 'all';
    protected function getFilters(): ?array
    {
        return [
            'all' => 'All Time',
            'today' => 'Today',
            'week' => 'Last Week',
            'month' => 'Last Month',
            'year' => 'This Year',
        ];
    }
    protected function getData(): array
    {
        $activeFilter = $this->filter;
        $query = Order::query();
        match ($activeFilter) {
            'today' => $query->whereDate('created_at', Carbon::today()),
            'week' => $query->where('created_at', '>=', Carbon::now()->subWeek()),
            'month' => $query->where('created_at', '>=', Carbon::now()->subMonth()),
            'year' => $query->whereYear('created_at', Carbon::now()->year),
            default => $query,
        };
        $statuses = ['Pending', 'Processing', 'Shipped', 'Completed', 'Cancelled'];
        $data = [];
        foreach ($statuses as $status) {
            $data[] = $query->clone()->where('status', $status)->sum('total');
        }
        return [
            'datasets' => [
                [
                    'label' => 'Revenue ($)',
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(251, 191, 36, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(139, 92, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                    ],
                    'borderColor' => [
                        'rgb(251, 191, 36)',
                        'rgb(59, 130, 246)',
                        'rgb(139, 92, 246)',
                        'rgb(34, 197, 94)',
                        'rgb(239, 68, 68)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $statuses,
        ];
    }
    protected function getType(): string
    {
        return 'bar';
    }
    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return "$" + value.toLocaleString(); }',
                    ],
                ],
            ],
        ];
    }
}

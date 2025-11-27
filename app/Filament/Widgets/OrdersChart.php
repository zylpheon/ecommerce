<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Orders Trend';
    protected static ?int $sort = 2;
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
        $perPeriod = match ($activeFilter) {
            'today' => 'perHour',
            'week' => 'perDay',
            'month' => 'perDay',
            'year' => 'perMonth',
            default => 'perMonth',
        };
        $minDate = Order::min('created_at');
        $startDate = match ($activeFilter) {
            'today' => Carbon::today(),
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            'year' => Carbon::now()->startOfYear(),
            default => $minDate ? Carbon::parse($minDate) : Carbon::now()->subYear(),
        };
        $data = Trend::query($query)
            ->between(
                start: $startDate,
                end: Carbon::now(),
            )
            ->$perPeriod()
            ->count();
        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                    'borderColor' => 'rgb(59, 130, 246)',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }
    protected function getType(): string
    {
        return 'line';
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
                        'precision' => 0,
                    ],
                ],
            ],
        ];
    }
}

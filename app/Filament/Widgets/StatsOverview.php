<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Order;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Categories', Category::count())
                ->description('All categories in the system')
                ->descriptionIcon('heroicon-m-tag')
                ->color('success'),
            Stat::make('Total Products', Product::count())
                ->description('Products available')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),
            Stat::make('Total Customers', Customer::count())
                ->description('Registered customers')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
            Stat::make('Total Orders', Order::count())
                ->description('Orders placed')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('danger'),
        ];
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        return [
            Stat::make('Pedidos', Order::count())
                ->description('Total Pedidos')
                ->descriptionIcon('heroicon-m-document')
                ->color('primary'),

            Stat::make('Productos', Product::count())
                ->description('Total de Productos')
                ->descriptionIcon('heroicon-m-circle-stack')
                ->color('primary'),

            Stat::make('Usuarios', User::count())
                ->description('Total de Usuarios')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),
        ];
    }
}

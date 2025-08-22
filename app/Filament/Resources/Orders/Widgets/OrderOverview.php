<?php

namespace App\Filament\Resources\Orders\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pedidos Pendientes', Order::where('status', 'Procesando')->count())
                ->description('Procesando')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('primary'),

            Stat::make('Pedidos Enviados', Order::where('status', 'En Camino')->count())
                ->description('En Camino')
                ->descriptionIcon('heroicon-m-truck')
                ->color('warning'),

            Stat::make('Pedidos Completados', Order::where('status', 'Entregado')->count())
                ->description('Entregados')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success'),

            Stat::make('Pedidos Incompletos', Order::where('status', 'No Entregado')->count())
                ->description('No Entregados')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('danger'),
        ];
    }
}

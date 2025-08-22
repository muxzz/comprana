<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('', User::where('role', 'ADMINISTRADOR')->count())
                ->description('Administradores')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('primary'),

            Stat::make('', User::where('role', 'EDITOR')->count())
                ->description('Editores')
                ->descriptionIcon('heroicon-m-pencil-square')
                ->color('success'),

            Stat::make('', User::where('role', 'USUARIO')->count())
                ->description('Usuarios')
                ->descriptionIcon('heroicon-m-user')
                ->color('gray'),

            Stat::make('', User::where('role', 'DESPACHADOR')->count())
                ->description('Despachadores')
                ->descriptionIcon('heroicon-m-cube')
                ->color('warning'),

            Stat::make('', User::where('role', 'REPARTIDOR')->count())
                ->description('Repartidores')
                ->descriptionIcon('heroicon-m-truck')
                ->color('warning'),
        ];
    }
}

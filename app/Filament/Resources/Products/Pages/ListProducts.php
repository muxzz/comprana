<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected ?string $heading = 'Productos';

    protected static ?string $title = 'Productos';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Crear Producto'),
        ];
    }
}

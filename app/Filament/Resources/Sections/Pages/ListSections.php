<?php

namespace App\Filament\Resources\Sections\Pages;

use App\Filament\Resources\Sections\SectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSections extends ListRecords
{
    protected static string $resource = SectionResource::class;

    protected static ?string $title = 'Secciones';

    protected ?string $heading = 'Secciones';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Crear Sección'),
        ];
    }
}

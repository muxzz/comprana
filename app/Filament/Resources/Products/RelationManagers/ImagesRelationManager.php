<?php

namespace App\Filament\Resources\Products\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ImagesRelationManager extends RelationManager
{
    protected static string $relationship = 'images';

    protected ?string $heading = 'Imágenes';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('name')
                    ->label('Imágenes')
                    ->disk('images')
                    ->image()
                    ->imageEditor()
                    ->reorderable()
                    ->preserveFilenames()
                    ->maxFiles(1)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ImageColumn::make('name')
                    ->label('imágenes')
                    ->size(100)
                    ->disk('images'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Agregar Imagen')
                    ->using(function (array $data, string $model): Model {
                        return $model::create([
                            'product_id' => $this->ownerRecord->id,
                            'name' => $data['name'],
                        ]);
                    })
                    ->hidden(fn (): bool => $this->ownerRecord->images()->count() >= 3),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\ManageOrders;
use App\Models\Order;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationLabel = 'Pedidos';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Procesando' => 'primary',
                        'En Camino' => 'warning',
                        'Entregado' => 'success',
                        'No Entregado' => 'danger',
                    }),
                TextEntry::make('total')
                    ->label('Total')
                    ->prefix('$')
                    ->numeric(),
                ViewEntry::make('invoice')
                    ->label('Factura')
                    ->view('filament.column-invoice'),
                TextEntry::make('phone')
                    ->label('Teléfono'),
                TextEntry::make('address')
                    ->label('Dirección'),
                TextEntry::make('created_at')
                    ->label('Fecha'),
                Section::make('Cliente')->schema([
                    TextEntry::make('user.name')
                        ->label('Nombre Cliente'),
                    TextEntry::make('user.phone')
                        ->label('Teléfono Cliente'),
                    TextEntry::make('user.address')
                        ->label('Dirección Cliente'),
                ])->columns('3'),
                Section::make('Personal Comprana')->schema([
                    TextEntry::make('dispatcher.name')
                        ->label('Nombre Despachador'),
                    TextEntry::make('delivery.name')
                        ->label('Nombre Repartidor'),
                ])->columns('2'),

            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('status')
                    ->label('Estado')
                    ->options(
                        fn (): array => Auth::user()->isDispatcher() ?
                        ['Procesando' => 'Procesando',
                            'En Camino' => 'En Camino',
                        ] :
                        ['Entregado' => 'Entregado',
                            'No Entregado' => 'No Entregado',
                        ]
                    )
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Procesando' => 'primary',
                        'En Camino' => 'warning',
                        'Entregado' => 'success',
                        'No Entregado' => 'danger',
                    })
                    ->sortable(),
                TextColumn::make('payment_id')
                    ->label('id de pago'),
                TextColumn::make('payment_status')
                    ->label('Estado de pago')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                    }),
                TextColumn::make('total')
                    ->prefix('$')
                    ->numeric(),
                ViewColumn::make('invoice')
                    ->view('filament.column-invoice'),
                TextColumn::make('user.name')
                    ->label('Cliente')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->since(),
                TextColumn::make('address')
                    ->label('Dirección')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')
                    ->label('Teléfono')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('dispatcher.name')
                    ->label('Despachador')
                    ->default('XXXXX')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('delivery.name')
                    ->label('Repartidor')
                    ->default('XXXXX')
                    ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->defaultSort('status', 'Procesando')
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make()
                        ->using(function (Order $record, array $data): Model {
                            $user = Auth::user();

                            if ($user->isDispatcher()) {
                                $data['dispatcher_id'] = $user->id;
                            } elseif ($user->isDelivery()) {
                                $data['delivery_id'] = $user->id;
                            }

                            $record->update($data);

                            return $record;
                        })
                        ->visible(function (Order $record) {
                            $user = Auth::user();
                            if ($user->isDispatcher()) {
                                return $record->status === 'Procesando';
                            } elseif ($user->isDelivery()) {
                                return $record->status === 'En Camino';
                            }

                            return false;
                        }),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //     Tables\Actions\DeleteBulkAction::make(),
                    ExportBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageOrders::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'Procesando')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', 'Procesando')->count() == 0 ? 'gray' : 'primary';
    }
}

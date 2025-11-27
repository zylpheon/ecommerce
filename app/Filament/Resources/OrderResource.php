<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('order_number')
                    ->required()
                    ->default(fn() => '#ORD-' . rand(1000, 9999))
                    ->disabled()
                    ->dehydrated()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('product_id')
                    ->relationship('product', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(
                        fn($state, Forms\Set $set) =>
                        $set('total', \App\Models\Product::find($state)?->price ?? 0)
                    ),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->minValue(1)
                    ->reactive()
                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                        $productId = $get('product_id');
                        if ($productId) {
                            $product = \App\Models\Product::find($productId);
                            $set('total', $product->price * $state);
                        }
                    }),
                Forms\Components\TextInput::make('total')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\Select::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Processing' => 'Processing',
                        'Shipped' => 'Shipped',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->default('Pending'),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'Pending',
                        'warning' => 'Processing',
                        'info' => 'Shipped',
                        'success' => 'Completed',
                        'danger' => 'Cancelled',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Processing' => 'Processing',
                        'Shipped' => 'Shipped',
                        'Completed' => 'Completed',
                        'Cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('customer')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}

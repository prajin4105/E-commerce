<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\PaymentRelationManager;
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

    protected static ?string $navigationGroup = 'Sales';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Information')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('total_amount')
                            ->required()
                            ->numeric()
                            ->prefix('₹'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'placed' => 'Order Placed',
                                'on_the_way' => 'On the Way',
                                'delivered' => 'Delivered',
                                'return_requested' => 'Return Requested',
                                'return_approved' => 'Return Approved',
                                'returned' => 'Returned',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                            ])
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Contact Information')
                    ->schema([
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Address Information')
                    ->schema([
                        Forms\Components\Textarea::make('shipping_address')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('billing_address')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Additional Information')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Order Status')
                    ->colors([
                        'primary' => 'placed',
                        'warning' => 'on_the_way',
                        'success' => 'delivered',
                        'info' => 'return_requested',
                        'secondary' => 'return_approved',
                        'gray' => 'returned',
                        'danger' => 'cancelled',
                    ])
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'placed' => 'Order Placed',
                        'on_the_way' => 'On the Way',
                        'delivered' => 'Delivered',
                        'return_requested' => 'Return Requested',
                        'return_approved' => 'Return Approved',
                        'returned' => 'Returned',
                        'cancelled' => 'Cancelled',
                        default => ucfirst($state),
                    }),
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'placed' => 'Order Placed',
                        'on_the_way' => 'On the Way',
                        'delivered' => 'Delivered',
                        'return_requested' => 'Return Requested',
                        'return_approved' => 'Return Approved',
                        'returned' => 'Returned',
                        'cancelled' => 'Cancelled',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                    ]),
            ])  
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PaymentRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Sales';
    }
}

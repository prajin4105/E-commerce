<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Sales';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Payment Information')
                    ->schema([
                        Forms\Components\Select::make('order_id')
                            ->relationship('order', 'order_number')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\TextInput::make('payment_id')
                            ->label('Razorpay Payment ID'),
                        Forms\Components\Select::make('payment_method')
                            ->options([
                                'razorpay' => 'Razorpay',
                                'cod' => 'Cash on Delivery',
                                'bank_transfer' => 'Bank Transfer',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('₹'),
                        Forms\Components\TextInput::make('currency')
                            ->default('INR')
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('transaction_id')
                            ->label('Razorpay Order ID'),
                        Forms\Components\DateTimePicker::make('paid_at'),
                    ])->columns(2),

                Forms\Components\Section::make('Razorpay Details')
                    ->schema([
                        Forms\Components\KeyValue::make('payment_details')
                            ->keyLabel('Field')
                            ->valueLabel('Value')
                            ->columnSpanFull()
                            ->default([
                                'razorpay_payment_id' => '',
                                'razorpay_order_id' => '',
                                'razorpay_signature' => '',
                                'bank' => '',
                                'wallet' => '',
                                'vpa' => '',
                                'email' => '',
                                'contact' => '',
                                'method' => '',
                                'card_id' => '',
                                'bank_transaction_id' => '',
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order.order_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money('INR')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'processing',
                        'success' => 'completed',
                        'danger' => 'failed',
                        'secondary' => 'refunded',
                    ]),
                Tables\Columns\TextColumn::make('payment_id')
                    ->label('Razorpay Payment ID')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('Razorpay Order ID')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('payment_details.bank')
                    ->label('Bank')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('payment_details.method')
                    ->label('Payment Method')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'razorpay' => 'Razorpay',
                        'cod' => 'Cash on Delivery',
                        'bank_transfer' => 'Bank Transfer',
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Sales';
    }
} 
<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentRelationManager extends RelationManager
{
    protected static string $relationship = 'payment';

    protected static ?string $recordTitleAttribute = 'payment_id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('payment_id')
                    ->label('Razorpay Payment ID'),
                Forms\Components\TextInput::make('payment_method'),
                Forms\Components\TextInput::make('amount')
                    ->prefix('₹'),
                Forms\Components\TextInput::make('status'),
                Forms\Components\TextInput::make('transaction_id')
                    ->label('Razorpay Order ID'),
                Forms\Components\DateTimePicker::make('paid_at'),
                Forms\Components\KeyValue::make('payment_details')
                    ->label('Razorpay Details')
                    ->keyLabel('Field')
                    ->valueLabel('Value'),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment_id')
                    ->label('Razorpay Payment ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
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
                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('Razorpay Order ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('paid_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
} 
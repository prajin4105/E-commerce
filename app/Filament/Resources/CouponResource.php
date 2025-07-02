<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CouponResource\Pages;
use App\Filament\Resources\CouponResource\RelationManagers;
use App\Models\Coupon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationLabel = 'Coupons';
    protected static ?string $pluralModelLabel = 'Coupons';
    protected static ?string $modelLabel = 'Coupon';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('code')
                ->required()
                ->unique(),
            Forms\Components\Textarea::make('description'),
            Forms\Components\Select::make('discount_type')
                ->options([
                    'fixed' => 'Fixed Amount',
                    'percent' => 'Percentage',
                ])->required(),
            Forms\Components\TextInput::make('discount_value')
                ->numeric()
                ->required(),
            Forms\Components\TextInput::make('minimum_cart_value')
                ->numeric()
                ->label('Minimum Cart Value'),
            Forms\Components\TextInput::make('max_uses')
                ->numeric()
                ->label('Max Uses'),
            Forms\Components\TextInput::make('per_user_limit')
                ->numeric()
                ->label('Per User Limit'),
            Forms\Components\DateTimePicker::make('valid_from'),
            Forms\Components\DateTimePicker::make('valid_to'),
            Forms\Components\Toggle::make('is_active'),
            Forms\Components\Toggle::make('show_to_user')->label('Show to User'),
            Forms\Components\TextInput::make('user_id')
                ->numeric()
                ->label('User ID (optional)'),
            Forms\Components\Textarea::make('product_ids')
                ->label('Product IDs (JSON array)'),
            Forms\Components\Textarea::make('category_ids')
                ->label('Category IDs (JSON array)'),
            Forms\Components\Toggle::make('free_shipping'),
            Forms\Components\Toggle::make('exclusive'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('code')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('discount_type')->sortable(),
            Tables\Columns\TextColumn::make('discount_value')->sortable(),
            Tables\Columns\TextColumn::make('minimum_cart_value')->sortable(),
            Tables\Columns\TextColumn::make('max_uses')->sortable(),
            Tables\Columns\TextColumn::make('used_count')->sortable(),
            Tables\Columns\TextColumn::make('per_user_limit')->sortable(),
            Tables\Columns\TextColumn::make('valid_from')->dateTime()->sortable(),
            Tables\Columns\TextColumn::make('valid_to')->dateTime()->sortable(),
            Tables\Columns\IconColumn::make('is_active')->boolean()->sortable(),
            Tables\Columns\IconColumn::make('show_to_user')->boolean()->label('Show to User')->sortable(),
            Tables\Columns\IconColumn::make('free_shipping')->boolean()->sortable(),
            Tables\Columns\IconColumn::make('exclusive')->boolean()->sortable(),
            ])
            ->filters([
            Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Coupons';
    }
}

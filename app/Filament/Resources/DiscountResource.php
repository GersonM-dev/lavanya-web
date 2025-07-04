<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DiscountResource\Pages;
use App\Filament\Resources\DiscountResource\RelationManagers;
use App\Models\Discount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DiscountResource extends Resource
{
    protected static ?string $model = Discount::class;

    protected static ?string $navigationIcon = 'heroicon-s-banknotes';
    protected static ?string $navigationLabel = 'Diskon';
    protected static ?string $plurallabel = 'Diskon';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Discount Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('Description'),

                        Forms\Components\TextInput::make('percentage')
                            ->label('Percentage (%)')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->nullable(),

                        Forms\Components\TextInput::make('amount')
                            ->label('Fixed Amount (IDR)')
                            ->numeric()
                            ->nullable(),

                        Forms\Components\DatePicker::make('start_date')
                            ->label('Start Date')
                            ->nullable(),

                        Forms\Components\DatePicker::make('end_date')
                            ->label('End Date')
                            ->nullable(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
                Forms\Components\Section::make('Apply To')
                    ->schema([
                        Forms\Components\Select::make('vendors')
                            ->label('Vendors')
                            ->multiple()
                            ->relationship('vendors', 'nama')
                            ->searchable(),

                        Forms\Components\Select::make('caterings')
                            ->label('Caterings')
                            ->multiple()
                            ->relationship('caterings', 'nama')
                            ->searchable(),

                        Forms\Components\Select::make('venues')
                            ->label('Venues')
                            ->multiple()
                            ->relationship('venues', 'nama')
                            ->searchable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('percentage')->label('Percent')->sortable(),
                Tables\Columns\TextColumn::make('amount')->label('Amount')->sortable()->money('IDR'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('start_date')->date(),
                Tables\Columns\TextColumn::make('end_date')->date(),
                Tables\Columns\BadgeColumn::make('vendors')
                    ->label('Vendors')
                    ->getStateUsing(fn($record) => $record->vendors->pluck('nama')->implode(', ')),
                Tables\Columns\BadgeColumn::make('caterings')
                    ->label('Caterings')
                    ->getStateUsing(fn($record) => $record->caterings->pluck('nama')->implode(', ')),
                Tables\Columns\BadgeColumn::make('venues')
                    ->label('Venues')
                    ->getStateUsing(fn($record) => $record->venues->pluck('nama')->implode(', ')),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListDiscounts::route('/'),
            'create' => Pages\CreateDiscount::route('/create'),
            'edit' => Pages\EditDiscount::route('/{record}/edit'),
        ];
    }
}

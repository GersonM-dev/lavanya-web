<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Referral;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ReferralResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ReferralResource\RelationManagers;

class ReferralResource extends Resource
{
    protected static ?string $model = Referral::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('referral_code')
                ->required()
                ->unique(ignoreRecord: true),

            Select::make('venues')
                ->label('Venues')
                ->multiple()
                ->relationship('venues', 'nama'),

            Select::make('vendors')
                ->label('Vendors')
                ->multiple()
                ->relationship('vendors', 'nama'),

            Select::make('caterings')
                ->label('Caterings')
                ->multiple()
                ->relationship('caterings', 'nama'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('referral_code')->searchable(),
                Tables\Columns\TextColumn::make('slug')->prefix('lavanyaenterprise.id/')->searchable()->copyable()
                    ->copyMessage('Link Referral disalin')->copyableState(fn (string $state): string => "lavanyaenterprise.id/{$state}")
                    ->copyMessageDuration(1500),
            ])
            ->filters([
                //
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
            'index' => Pages\ListReferrals::route('/'),
            'create' => Pages\CreateReferral::route('/create'),
            'view' => Pages\ViewReferral::route('/{record}'),
            'edit' => Pages\EditReferral::route('/{record}/edit'),
        ];
    }
}

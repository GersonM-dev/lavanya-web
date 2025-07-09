<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Venue;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\VenueResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VenueResource\RelationManagers;

class VenueResource extends Resource
{
    protected static ?string $model = Venue::class;

    protected static ?string $navigationIcon = 'heroicon-s-building-office-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->label('Tipe Wedding Venue')
                    ->required()
                    ->options([
                        'Indoor' => 'Indoor',
                        'Outdoor' => 'Outdoor',
                        'Semi-Outdoor' => 'Semi-Outdoor',
                    ]),

                TextInput::make('capacity')
                    ->label('Kapasitas Tamu')
                    ->numeric()
                    ->default(0)
                    ->suffix('orang')
                    ->required(),
                    
                TextInput::make('harga')
                    ->label('Harga')
                    ->numeric()
                    ->required(),
                TextInput::make('portofolio_link')
                    ->label('Link Instagram')
                    ->url()
                    ->maxLength(255),
                FileUpload::make('image1')
                    ->label('Gambar 1')
                    ->image()
                    ->columnSpanFull()
                    ->directory('venues'),
                FileUpload::make('image2')
                    ->label('Gambar 2')
                    ->image()
                    ->directory('venues'),
                FileUpload::make('image3')
                    ->label('Gambar 3')
                    ->image()
                    ->directory('venues'),
                RichEditor::make('deskripsi')
                    ->label('Deskripsi')
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(false)
                    ->hidden(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Tipe Wedding Venue')
                    ->sortable(),
                TextColumn::make('capacity')
                    ->label('Kapasitas Tamu')
                    ->sortable()
                    ->suffix('orang'),
                TextColumn::make('harga')
                    ->label('Harga')
                    ->sortable()
                    ->money('IDR', divideBy: 100),
                ToggleColumn::make('is_active')
                    ->label('Active'),
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
            'index' => Pages\ListVenues::route('/'),
            'create' => Pages\CreateVenue::route('/create'),
            'edit' => Pages\EditVenue::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Catering;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use function Laravel\Prompts\select;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use App\Filament\Resources\CateringResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CateringResource\RelationManagers;

class CateringResource extends Resource
{
    protected static ?string $model = Catering::class;

    protected static ?string $navigationIcon = 'heroicon-s-cake';
    protected static ?string $pluralLabel = 'Caterings';
    protected static ?string $navigationGroup = 'Vendor Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')->label('Nama Catering')->required(),
                Select::make('type')
                    ->options([
                        'Hotel' => 'Hotel',
                        'Resto' => 'Resto',
                        'Basic' => 'Basic',
                    ])
                    ->required()->reactive(),
                Checkbox::make('is_all_venue')
                    ->label('Tersedia di Semua Venue')
                    ->reactive(),

                CheckboxList::make('venues')
                    ->label('Daftar Venue')
                    ->relationship('venues', 'nama')
                    ->columns(2)
                    ->visible(fn($get) => !$get('is_all_venue')),

                TextInput::make('buffet_price')
                    ->label('Harga Buffet')
                    ->numeric()
                    ->nullable()
                    ->visible(fn($get) => in_array($get('type'), ['Hotel', 'Resto'])),

                TextInput::make('gubugan_price')
                    ->label('Harga Gubugan')
                    ->numeric()
                    ->nullable()
                    ->visible(fn($get) => in_array($get('type'), ['Hotel', 'Resto'])),

                TextInput::make('dessert_price')
                    ->label('Harga Dessert')
                    ->numeric()
                    ->nullable()
                    ->visible(fn($get) => in_array($get('type'), ['Hotel', 'Resto'])),

                TextInput::make('base_price')
                    ->label('Harga Base')
                    ->numeric()
                    ->nullable()
                    ->visible(fn($get) => $get('type') === 'Basic'),

                RichEditor::make('deskripsi')->required()->columnSpanFull(),

                FileUpload::make('image1')->image()->label('Gambar 1')->columnSpanFull(),
                FileUpload::make('image2')->image()->label('Gambar 2'),
                FileUpload::make('image3')->image()->label('Gambar 3'),
                TextInput::make('portofolio_link')->label('Portfolio URL (Instagram)')
                    ->url()
                    ->nullable(),
                Toggle::make('is_active')->default(true)->hidden(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->label('Nama Catering')->searchable(),
                Tables\Columns\TextColumn::make('type')->label('Tipe'),
                Tables\Columns\TextColumn::make('buffet_price')->label('Harga Buffet')->money('IDR')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('gubugan_price')->label('Harga Gubugan')->money('IDR')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('dessert_price')->label('Harga Dessert')->money('IDR')->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('base_price')->label('Harga Base')->money('IDR')->sortable()->toggleable(),
                Tables\Columns\ToggleColumn::make('is_active')->label('Aktif')->sortable(),
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
            'index' => Pages\ListCaterings::route('/'),
            'create' => Pages\CreateCatering::route('/create'),
            'edit' => Pages\EditCatering::route('/{record}/edit'),
        ];
    }
}

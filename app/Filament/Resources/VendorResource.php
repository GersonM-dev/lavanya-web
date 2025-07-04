<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Tables;
use App\Models\Vendor;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use App\Filament\Resources\VendorResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VendorResource\RelationManagers;

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static ?string $navigationIcon = 'heroicon-s-building-storefront';
    protected static ?string $navigationGroup = 'Vendor Management';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->label('Nama Vendor')
                    ->required(),

                Select::make('vendor_category_id')
                    ->label('Kategori Vendor')
                    ->relationship('vendorCategory', 'name')
                    ->required(),

                TextInput::make('harga')
                    ->label('Harga')
                    ->numeric()
                    ->required(),

                TextInput::make('portofolio_link')
                    ->url()
                    ->label('Portfolio Link'),

                Checkbox::make('is_all_venue')
                    ->label('Available for All Venues')
                    ->reactive(),

                CheckboxList::make('venues')
                    ->label('Available Venues')
                    ->relationship('venues', 'nama')
                    ->columns(2)
                    ->visible(fn($get) => !$get('is_all_venue')),

                RichEditor::make('deskripsi')
                    ->label('Description')
                    ->columnSpanFull()
                    ->required(),

                FileUpload::make('image1')->image()->directory('vendors')->label('Gambar 1'),
                FileUpload::make('image2')->image()->directory('vendors')->label('Gambar 2'),
                FileUpload::make('image3')->image()->directory('vendors')->label('Gambar 3'),

                Toggle::make('is_active')
                    ->label('Active')
                    ->hidden()
                    ->default(false),
                Toggle::make('is_mandatory')
                    ->label('Mandatory')
                    ->hidden()
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')->label('Nama Vendor')->searchable(),
                Tables\Columns\TextColumn::make('vendorCategory.name')->label('Kategori Vendor')->sortable(),
                Tables\Columns\TextColumn::make('harga')->label('Harga')->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')->label('Aktif'),
                Tables\Columns\ToggleColumn::make('is_mandatory')->label('Wajib'),
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
            'index' => Pages\ListVendors::route('/'),
            'create' => Pages\CreateVendor::route('/create'),
            'edit' => Pages\EditVendor::route('/{record}/edit'),
        ];
    }
}

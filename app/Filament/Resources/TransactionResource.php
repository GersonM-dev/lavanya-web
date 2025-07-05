<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Log;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $pluralLabel = 'Wedding Plans';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Customer Info')
                    ->description('Informasi pelanggan di bawah ini')
                    ->schema([
                        Forms\Components\Select::make('customer_id')
                            ->label('Pelanggan')
                            ->options(\App\Models\Customer::all()->pluck('grooms_name', 'id'))
                            ->searchable()
                            ->hidden()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $customer = \App\Models\Customer::find($state);
                                if ($customer) {
                                    $set('customer.grooms_name', $customer->grooms_name);
                                    $set('customer.brides_name', $customer->brides_name);
                                    $set('customer.phone_number', $customer->phone_number);
                                    $set('customer.refferal_code', $customer->refferal_code ?? null);
                                    $set('customer.guest_count', $customer->guest_count ?? null);
                                    $set('customer.wedding_date', $customer->wedding_date ?? null);
                                } else {
                                    $set('customer.grooms_name', null);
                                    $set('customer.brides_name', null);
                                    $set('customer.phone_number', null);
                                    $set('customer.refferal_code', null);
                                    $set('customer.guest_count', null);
                                    $set('customer.wedding_date', null);
                                }
                            })
                            ->afterStateHydrated(function ($state, callable $set) {
                                if (!$state) {
                                    return;
                                }
                                $customer = \App\Models\Customer::find($state);
                                if ($customer) {
                                    $set('customer.grooms_name', $customer->grooms_name);
                                    $set('customer.brides_name', $customer->brides_name);
                                    $set('customer.phone_number', $customer->phone_number);
                                    $set('customer.refferal_code', $customer->refferal_code ?? null);
                                    $set('customer.guest_count', $customer->guest_count ?? null);
                                    $set('customer.wedding_date', $customer->wedding_date ?? null);
                                }
                            }),

                        Forms\Components\TextInput::make('customer.grooms_name')
                            ->label('Nama Pengantin Pria')
                            ->required(),

                        Forms\Components\TextInput::make('customer.brides_name')
                            ->label('Nama Pengantin Wanita')
                            ->required(),

                        Forms\Components\TextInput::make('customer.phone_number')
                            ->label('Nomor HP')
                            ->required(),

                        Forms\Components\TextInput::make('customer.refferal_code')
                            ->label('Kode Referral')
                            ->nullable(),

                        Forms\Components\TextInput::make('customer.guest_count')
                            ->label('Jumlah Tamu')
                            ->numeric()
                            ->suffix(' orang')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $catering = \App\Models\Catering::find($get('catering_id'));
                                if (!$catering)
                                    return;

                                $guestCount = (int) $state;

                                $buffet = $catering->buffet_price ?? 0;
                                $gubugan = $catering->gubugan_price ?? 0;
                                $dessert = $catering->dessert_price ?? 0;
                                $base = $catering->base_price ?? 0;

                                $totalBuffet = 0;
                                $totalGubugan = 0;
                                $totalDessert = 0;
                                $totalCatering = 0;

                                if ($catering->type === 'Hotel') {
                                    $totalFood = $guestCount * 3;
                                    $totalBuffet = $buffet * ($guestCount * 0.5);
                                    $totalGubugan = $gubugan * ($totalFood - ($guestCount * 0.5));
                                    $totalDessert = $dessert * ($guestCount * 0.5);
                                    $totalCatering = $totalBuffet + $totalGubugan + $totalDessert;
                                } elseif ($catering->type === 'Resto') {
                                    $totalBuffet = $buffet * $guestCount;
                                    $totalGubugan = $gubugan * $guestCount;
                                    $totalDessert = $dessert * ($guestCount * 0.5);
                                    $totalCatering = $totalBuffet + $totalGubugan + $totalDessert;
                                } elseif ($catering->type === 'Basic') {
                                    $totalCatering = $base * $guestCount;
                                }

                                $set('total_buffet_price', $totalBuffet);
                                $set('total_gubugan_price', $totalGubugan);
                                $set('total_dessert_price', $totalDessert);
                                $set('catering_total_price', $totalCatering);

                                // Hitung total keseluruhan
                                $venuePrice = \App\Models\Venue::find($get('venue_id'))?->harga ?? 0;
                                $vendors = $get('vendors') ?? [];
                                $vendorTotal = collect($vendors)->sum('estimated_price');

                                $total = $venuePrice + $vendorTotal + $totalCatering;

                                // get selected discount IDs
                                $discountIds = $get('discounts') ?? [];
                                $discounts = \App\Models\Discount::whereIn('id', $discountIds)->get();

                                $discountedTotal = self::calculateDiscountedPrice($total, $discounts);
                                $set('total_estimated_price', $discountedTotal);
                            }),

                        Forms\Components\DatePicker::make('customer.wedding_date')
                            ->label('Tanggal Pernikahan')
                            ->required(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Venue Info')
                    ->description('Pilih venue untuk acara pernikahan')
                    ->schema([
                        Forms\Components\Select::make('venue_type')
                            ->label('Tipe Venue')
                            ->options([
                                'Indoor' => 'Indoor',
                                'Outdoor' => 'Outdoor',
                                'Semi-Outdoor' => 'Semi-Outdoor',
                            ])
                            ->reactive()
                            ->required()
                            ->dehydrated(false),

                        Forms\Components\Select::make('venue_id')
                            ->label('Venue')
                            ->options(function ($get) {
                                $type = $get('venue_type');
                                $selectedVenueId = $get('venue_id');

                                $query = \App\Models\Venue::query();

                                if ($type) {
                                    $query->where('type', $type);
                                }

                                $venues = $query->pluck('nama', 'id')->toArray();

                                // Always include the selected venue if not in options
                                if ($selectedVenueId && !array_key_exists($selectedVenueId, $venues)) {
                                    $venue = \App\Models\Venue::find($selectedVenueId);
                                    if ($venue) {
                                        $venues[$venue->id] = $venue->nama;
                                    }
                                }

                                return $venues;
                            })
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                // Recalculate total if venue changes
                                $vendors = $get('vendors') ?? [];
                                $venue = \App\Models\Venue::find($state);
                                $venuePrice = $venue?->harga ?? 0;
                                $vendorTotal = collect($vendors)->sum('estimated_price');
                                $total = $venuePrice + $vendorTotal + ($get('catering_total_price') ?? 0);
                                $discountIds = $get('discounts') ?? [];
                                $discounts = \App\Models\Discount::whereIn('id', $discountIds)->get();
                                $discountedTotal = self::calculateDiscountedPrice($total, $discounts);
                                $set('total_estimated_price', $discountedTotal);
                            })
                            ->disabled(fn($get) => !$get('venue_type')),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Catering Info')
                    ->description('Pilih vendor catering untuk acara pernikahan')
                    ->schema([
                        Select::make('catering_id')
                            ->label('Vendor Catering')
                            ->options(function ($get) {
                                $venueId = $get('venue_id');
                                if (!$venueId)
                                    return [];

                                return \App\Models\Catering::where(function ($query) use ($venueId) {
                                    $query->whereHas('venues', function ($sub) use ($venueId) {
                                        $sub->where('venues.id', $venueId);
                                    })->orWhere('is_all_venue', true);
                                })->pluck('nama', 'id');
                            })
                            ->searchable()
                            ->columnSpanFull()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $catering = \App\Models\Catering::find($state);
                                $guestCount = $get('customer.guest_count') ?? 0;

                                // Initialize all
                                $totalBuffetPrice = 0;
                                $totalGubuganPrice = 0;
                                $totalDessertPrice = 0;
                                $totalCateringPrice = 0;

                                Log::info('--- Catering Price Calculation Debug ---', [
                                    'selected_catering' => $catering?->nama,
                                    'catering_type' => $catering?->type,
                                    'guest_count' => $guestCount,
                                    'buffet_price' => $catering?->buffet_price,
                                    'gubugan_price' => $catering?->gubugan_price,
                                    'dessert_price' => $catering?->dessert_price,
                                    'base_price' => $catering?->base_price,
                                ]);

                                if ($catering && $guestCount) {
                                    if ($catering->type === 'Hotel') {
                                        $totalFood = $guestCount * 3;
                                        $totalBuffetPrice = $catering->buffet_price * ($guestCount * 0.5);
                                        $totalGubuganPrice = $catering->gubugan_price * ($totalFood - ($guestCount * 0.5));
                                        $totalDessertPrice = $catering->dessert_price * ($guestCount * 0.5);
                                        $totalCateringPrice = $totalBuffetPrice + $totalGubuganPrice + $totalDessertPrice;
                                        Log::info('[Hotel] Calculation', [
                                            'totalFood' => $totalFood,
                                            'totalBuffetPrice' => $totalBuffetPrice,
                                            'totalGubuganPrice' => $totalGubuganPrice,
                                            'totalDessertPrice' => $totalDessertPrice,
                                            'totalCateringPrice' => $totalCateringPrice,
                                        ]);
                                    } elseif ($catering->type === 'Resto') {
                                        $totalBuffetPrice = $catering->buffet_price * $guestCount;
                                        $totalGubuganPrice = $catering->gubugan_price * $guestCount;
                                        $totalDessertPrice = $catering->dessert_price * ($guestCount * 0.5);
                                        $totalCateringPrice = $totalBuffetPrice + $totalGubuganPrice + $totalDessertPrice;
                                        Log::info('[Resto] Calculation', [
                                            'totalBuffetPrice' => $totalBuffetPrice,
                                            'totalGubuganPrice' => $totalGubuganPrice,
                                            'totalDessertPrice' => $totalDessertPrice,
                                            'totalCateringPrice' => $totalCateringPrice,
                                        ]);
                                    } elseif ($catering->type === 'Basic') {
                                        $totalCateringPrice = $guestCount * $catering->base_price;
                                        Log::info('[Basic] Calculation', [
                                            'totalCateringPrice' => $totalCateringPrice,
                                        ]);
                                    }
                                }
                                $set('total_buffet_price', $totalBuffetPrice);
                                $set('total_gubugan_price', $totalGubuganPrice);
                                $set('total_dessert_price', $totalDessertPrice);
                                $set('catering_total_price', $totalCateringPrice);

                                // Update total_estimated_price
                                $vendors = $get('vendors') ?? [];
                                $venueId = $get('venue_id');
                                $venue = \App\Models\Venue::find($venueId);
                                $venuePrice = $venue?->harga ?? 0;
                                $totalVendors = collect($vendors)->sum('estimated_price');
                                $total = $venuePrice + $totalVendors + $totalCateringPrice;
                                $discountIds = $get('discounts') ?? [];
                                $discounts = \App\Models\Discount::whereIn('id', $discountIds)->get();
                                $discountedTotal = self::calculateDiscountedPrice($total, $discounts);
                                $set('total_estimated_price', $discountedTotal);
                            }),
                        Forms\Components\TextInput::make('total_buffet_price')
                            ->label('Harga Buffet')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->default(0)
                            ->prefix('IDR'),


                        Forms\Components\TextInput::make('total_gubugan_price')
                            ->label('Harga Gubugan')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->default(0)
                            ->prefix('IDR'),

                        Forms\Components\TextInput::make('total_dessert_price')
                            ->label('Harga Dessert')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->default(0)
                            ->prefix('IDR'),

                        Forms\Components\TextInput::make('catering_total_price')
                            ->label('Total Biaya Catering')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->default(0)
                            ->prefix('IDR'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Vendor Info')
                    ->description('Pilih vendor untuk acara pernikahan')
                    ->schema([
                        Forms\Components\Repeater::make('vendors')
                            ->label('Pilih Vendor')
                            ->relationship('vendors')
                            ->schema([
                                Forms\Components\Select::make('vendor_id')
                                    ->label('Vendor')
                                    ->options(function ($get) {
                                        $venueId = $get('../../venue_id');
                                        if (!$venueId)
                                            return [];

                                        return \App\Models\Vendor::where(function ($query) use ($venueId) {
                                            $query->whereHas('venues', function ($q) use ($venueId) {
                                                $q->where('venues.id', $venueId);
                                            })->orWhere('is_all_venue', true);
                                        })->pluck('nama', 'id');
                                    })
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $vendor = \App\Models\Vendor::find($state);
                                        $set('estimated_price', $vendor?->harga ?? null);

                                        $vendors = $get('../../vendors') ?? [];
                                        $venueId = $get('../../venue_id');
                                        $venue = \App\Models\Venue::find($venueId);
                                        $venuePrice = $venue?->harga ?? 0;
                                        $cateringPrice = $get('../../catering_total_price') ?? 0;

                                        $totalVendor = collect($vendors)->sum('estimated_price');
                                        $total = $venuePrice + $cateringPrice + $totalVendor;
                                        $discountIds = $get('../../discounts') ?? [];
                                        $discounts = \App\Models\Discount::whereIn('id', $discountIds)->get();
                                        $discountedTotal = self::calculateDiscountedPrice($total, $discounts);
                                        $set('../../total_estimated_price', $discountedTotal);

                                    }),

                                Forms\Components\TextInput::make('estimated_price')
                                    ->label('Estimated Price')
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        $vendors = $get('../../vendors') ?? [];
                                        $venueId = $get('../../venue_id');
                                        $venue = \App\Models\Venue::find($venueId);
                                        $venuePrice = $venue?->harga ?? 0;
                                        $cateringPrice = $get('../../catering_total_price') ?? 0;

                                        $totalVendor = collect($vendors)->sum('estimated_price');
                                        $set('../../total_estimated_price', $venuePrice + $cateringPrice + $totalVendor);
                                    }),
                            ])
                            ->columnSpanFull()
                            ->collapsible()
                            ->defaultItems(2)
                            ->minItems(1)
                            ->grid(2)
                            ->itemLabel(function (array $state): ?string {
                                if (isset($state['vendor_id'])) {
                                    $vendor = \App\Models\Vendor::find($state['vendor_id']);
                                    return $vendor?->nama;
                                }
                                return null;
                            })
                            ->createItemButtonLabel('Tambah Vendor'),
                    ]),

                Forms\Components\Section::make('Discounts')
                    ->description('Pilih diskon yang berlaku sesuai pilihan vendor, venue, dan catering')
                    ->schema([
                        Forms\Components\Select::make('discounts')
                            ->label('Discounts')
                            ->multiple()
                            ->relationship('discounts', 'name')
                            ->options(function (callable $get) {
                                // Fetch selected IDs
                                $venueId = $get('venue_id');
                                $cateringId = $get('catering_id');
                                $vendorStates = $get('vendors') ?? [];
                                $vendorIds = collect($vendorStates)->pluck('vendor_id')->filter()->unique();

                                // Query: discounts related to any of the selected venue, catering, vendor
                                $query = \App\Models\Discount::query()
                                    ->where(function ($q) use ($venueId, $cateringId, $vendorIds) {
                                    $q->when(
                                        $venueId,
                                        fn($q2) =>
                                        $q2->whereHas('venues', fn($qq) => $qq->where('venues.id', $venueId))
                                    )
                                        ->when(
                                            $cateringId,
                                            fn($q2) =>
                                            $q2->orWhereHas('caterings', fn($qq) => $qq->where('caterings.id', $cateringId))
                                        )
                                        ->when(
                                            $vendorIds->count(),
                                            fn($q2) =>
                                            $q2->orWhereHas('vendors', fn($qq) => $qq->whereIn('vendors.id', $vendorIds))
                                        );
                                });

                                return $query->pluck('name', 'id');
                            })
                            ->searchable()
                            ->reactive()
                            ->preload()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                $venuePrice = \App\Models\Venue::find($get('venue_id'))?->harga ?? 0;
                                $catering = \App\Models\Catering::find($get('catering_id'));
                                $guestCount = (int) ($get('customer.guest_count') ?? 0);
                                $buffet = $catering?->buffet_price ?? 0;
                                $gubugan = $catering?->gubugan_price ?? 0;
                                $dessert = $catering?->dessert_price ?? 0;
                                $base = $catering?->base_price ?? 0;
                                $totalBuffet = $totalGubugan = $totalDessert = $totalCatering = 0;
                                if ($catering) {
                                    if ($catering->type === 'Hotel') {
                                        $totalFood = $guestCount * 3;
                                        $totalBuffet = $buffet * ($guestCount * 0.5);
                                        $totalGubugan = $gubugan * ($totalFood - ($guestCount * 0.5));
                                        $totalDessert = $dessert * ($guestCount * 0.5);
                                        $totalCatering = $totalBuffet + $totalGubugan + $totalDessert;
                                    } elseif ($catering->type === 'Resto') {
                                        $totalBuffet = $buffet * $guestCount;
                                        $totalGubugan = $gubugan * $guestCount;
                                        $totalDessert = $dessert * ($guestCount * 0.5);
                                        $totalCatering = $totalBuffet + $totalGubugan + $totalDessert;
                                    } elseif ($catering->type === 'Basic') {
                                        $totalCatering = $base * $guestCount;
                                    }
                                }
                                $vendors = $get('vendors') ?? [];
                                $vendorTotal = collect($vendors)->sum('estimated_price');
                                $total = $venuePrice + $vendorTotal + $totalCatering;

                                $discountIds = $state ?? [];
                                $discounts = \App\Models\Discount::whereIn('id', $discountIds)->get();

                                $discountedTotal = self::calculateDiscountedPrice($total, $discounts);
                                $set('total_estimated_price', $discountedTotal);
                            })
                    ]),

                Forms\Components\Section::make('Transaction Info')
                    ->description('Informasi transaksi dan total harga')
                    ->schema([
                        Forms\Components\TextInput::make('total_estimated_price')
                            ->label('Total Harga Seluruh')
                            ->numeric()
                            ->disabled()
                            ->dehydrated()
                            ->default(0)
                            ->prefix('IDR'),

                        Forms\Components\DateTimePicker::make('transaction_date')
                            ->label('Transaction Date')
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->default(now()),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->nullable()
                            ->columnSpanFull(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('draft')
                            ->hidden()
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.grooms_name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('venue.nama')
                    ->label('Venue')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer.wedding_date')
                    ->label('Wedding Date')
                    ->date()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('discounts')
                    ->label('Discounts')
                    ->getStateUsing(fn($record) => $record->discounts->pluck('name')->implode(', ')),


                Tables\Columns\TextColumn::make('total_estimated_price')
                    ->label('Estimated Price')
                    ->money('idr')
                    ->sortable(),

                Tables\Columns\SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->sortable(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    public static function calculateDiscountedPrice($total, $discounts)
    {
        $discountedTotal = $total;
        foreach ($discounts as $discount) {
            if ($discount->percentage) {
                $discountedTotal -= ($discount->percentage / 100) * $discountedTotal;
            }
            if ($discount->amount) {
                $discountedTotal -= $discount->amount;
            }
        }
        return max($discountedTotal, 0); // Never less than zero
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        // Handle customer data
        if (isset($data['customer'])) {
            $customerData = $data['customer'];
            // If editing, get current transaction and its customer
            $transaction = request()->route('record');
            if ($transaction && $transaction->customer) {
                $customer = $transaction->customer;
                $customer->fill($customerData);
                $customer->save();
            } else {
                // New transaction or no customer attached yet
                $customer = new \App\Models\Customer($customerData);
                $customer->save();
            }
            // Set the customer_id on the transaction
            $data['customer_id'] = $customer->id;
            unset($data['customer']);
        }
        return $data;
    }

    // protected static ?string $recordTitleAttribute = 'customer.grooms_name';

    // public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    // {
    //     return $record->customer?->grooms_name . ' & ' . $record->customer?->brides_name;
    // }

    // public static function getGlobalSearchResultDetails(Model $record): array
    // {
    //     return [
    //         'Venue' => $record->venue?->nama,
    //         'Wedding Date' => $record->customer?->wedding_date,
    //         'Estimated Price' => number_format($record->total_estimated_price, 0, ',', '.'),
    //     ];
    // }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['customer', 'venue', 'discounts']);
    }

}

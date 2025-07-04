<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Transaction;
use App\Models\Venue;
use App\Models\Vendor;
use App\Models\VendorRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Overview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalTransactions = Transaction::count();
        $newThisWeek = Transaction::where('created_at', '>=', now()->startOfWeek())->count();

        $mostVenueId = Transaction::select('venue_id', DB::raw('count(*) as total'))
            ->groupBy('venue_id')
            ->orderByDesc('total')
            ->value('venue_id');
        $mostVenueName = $mostVenueId
            ? Venue::find($mostVenueId)?->nama ?? '-'
            : '-';
        $mostVenueCount = Transaction::where('venue_id', $mostVenueId)->count();

        // Use VendorRecord instead of the pivot
        $mostVendor = VendorRecord::select('vendor_id', DB::raw('count(*) as total'))
            ->groupBy('vendor_id')
            ->orderByDesc('total')
            ->first();
        $mostVendorName = $mostVendor ? Vendor::find($mostVendor->vendor_id)?->nama : '-';
        $mostVendorCount = $mostVendor->total ?? 0;

        $today = Carbon::today()->toDateString();

        $afterTodayCount = Transaction::whereHas('customer', function ($q) {
            $q->where('wedding_date', '>', now()->toDateString());
        })->count();

        return [
            Stat::make('Total Transactions', $totalTransactions)
                ->description("{$newThisWeek} added this week")
                ->descriptionIcon('heroicon-m-arrow-trending-up'),

            Stat::make('Most Chosen Venue', $mostVenueName)
                ->description("{$mostVenueCount} times")
                ->descriptionIcon('heroicon-m-building-library'),

            Stat::make('Most Chosen Vendor', $mostVendorName)
                ->description("{$mostVendorCount} times")
                ->descriptionIcon('heroicon-m-user-group'),

            Stat::make('Upcoming Weddings', $afterTodayCount)
                ->description('Weddings after today')
                ->descriptionIcon('heroicon-m-calendar-days'),
        ];
    }
}

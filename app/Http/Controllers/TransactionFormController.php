<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use App\Models\Catering;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Jobs\CalculateTransactionTotal;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\VendorCategories;

class TransactionFormController extends Controller
{
    public function create()
    {
        return view('transaction.form', [
            'venues' => Venue::all(),
            'caterings' => Catering::all(),
            'vendorCategories' => VendorCategories::with('vendors')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $customer = Customer::create($data['customer']);

        $transaction = Transaction::create([
            'customer_id' => $customer->id,
            'venue_id' => $data['venue_id'],
            'catering_id' => $data['catering_id'],
            'transaction_date' => now(),
            'notes' => $data['notes'] ?? null,
            'status' => 'draft',
        ]);

        $transaction->vendors()->attach($data['vendors'] ?? []);

        // Optional: Jalankan perhitungan harga total di job/logic sendiri
        dispatch(new CalculateTransactionTotal($transaction));

        return redirect()->route('transaction.pdf', $transaction->id);
    }

    public function pdf($id)
    {
        $transaction = Transaction::with(['customer', 'venue', 'catering', 'vendors'])->findOrFail($id);
        $pdf = Pdf::loadView('transaction.pdf', compact('transaction'))->setPaper('a4');

        return $pdf->stream('transaksi-wedding-'.$transaction->id.'.pdf');
    }
}

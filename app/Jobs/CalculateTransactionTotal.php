<?php
namespace App\Jobs;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class CalculateTransactionTotal implements ShouldQueue
{
    use Queueable;

    public function __construct(public Transaction $transaction) {}

    public function handle(): void
    {
        $c = $this->transaction->catering;
        $g = $this->transaction->customer->guest_count;
        $v = $this->transaction->venue;

        $total = 0;
        if ($c && $g) {
            if ($c->type === 'Hotel') {
                $total = ($c->buffet_price * ($g * 0.5)) + 
                         ($c->gubugan_price * ($g * 1.5)) + 
                         ($c->dessert_price * ($g * 0.5));
            } elseif ($c->type === 'Resto') {
                $total = ($c->buffet_price + $c->gubugan_price) * $g + ($c->dessert_price * ($g * 0.5));
            } elseif ($c->type === 'Basic') {
                $total = $c->base_price * $g;
            }
        }

        $vendorTotal = $this->transaction->vendors->sum('harga');
        $this->transaction->update([
            'catering_total_price' => $total,
            'total_estimated_price' => $total + ($v->harga ?? 0) + $vendorTotal,
        ]);
    }
}

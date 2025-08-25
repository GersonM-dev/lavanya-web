<?php

namespace App\Jobs;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Job untuk mengirim pesan follow‑up ke customer ketika transaksi masih berstatus pending.
 *
 * Job ini akan mengirim pesan WhatsApp ke nomor customer menggunakan API Fonnte
 * dan menjadwalkan dirinya sendiri lagi setelah 3 hari selama status transaksi
 * tetap `pending` dan belum melebihi tiga kali percobaan.
 */
class FollowUpWeddingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var int */
    protected $transactionId;
    /** @var int */
    protected $attemptNumber;

    /**
     * Create a new job instance.
     *
     * @param int $transactionId  ID transaksi yang akan di‑follow‑up.
     * @param int $attemptNumber  Hitungan keberapa reminder ini (1–3).
     */
    public function __construct(int $transactionId, int $attemptNumber = 1)
    {
        $this->transactionId = $transactionId;
        $this->attemptNumber = $attemptNumber;
    }

    /**
     * Execute the job.
     *
     * Jika status transaksi masih `pending`, kirim pesan ke customer dengan
     * detail transaksi. Jika masih pending dan jumlah reminder belum mencapai
     * batas, jadwalkan reminder berikutnya dengan delay 3 hari.
     *
     * @return void
     */
    public function handle(): void
    {
        $transaction = Transaction::with('customer')->find($this->transactionId);
        if (!$transaction) {
            Log::warning("FollowUpWeddingJob: transaksi {$this->transactionId} tidak ditemukan");
            return;
        }

        // Hentikan job jika status sudah bukan pending
        if ($transaction->status !== 'pending') {
            return;
        }

        $customer = $transaction->customer;
        if (!$customer) {
            Log::warning("FollowUpWeddingJob: customer untuk transaksi {$this->transactionId} tidak ditemukan");
            return;
        }

        // Format nomor telepon: ganti awalan 0 dengan 62 jika perlu
        $phone = $customer->phone_number;
        $target = (substr($phone, 0, 1) === '0') ? '62' . substr($phone, 1) : $phone;

        // Susun pesan follow‑up
        $msg = "Halo {$customer->grooms_name} & {$customer->brides_name},\n"
            . "Kami belum menerima update pembayaran untuk pesanan pernikahan Anda.\n"
            . "Tanggal: {$customer->wedding_date}\n"
            . "Jumlah Tamu: {$customer->guest_count}\n"
            . "Mohon konfirmasi pembayaran atau hubungi admin jika ada pertanyaan.\n"
            . "Terima kasih!";

        // Kirim pesan via Fonnte API
        try {
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => [
                    'target' => $target,
                    'message' => $msg,
                    'countryCode' => '62',
                ],
                // Ganti token berikut dengan token Fonnte yang valid
                CURLOPT_HTTPHEADER => ['Authorization: p6myug9nJRGFYESwMDRz'],
            ]);
            $response = curl_exec($curl);
            if (curl_errno($curl)) {
                Log::error('FollowUpWeddingJob: Fonnte error: ' . curl_error($curl));
            }
            curl_close($curl);
            Log::info('FollowUpWeddingJob: Fonnte response: ' . $response);
        } catch (\Throwable $e) {
            Log::error('FollowUpWeddingJob: WhatsApp notification failed: ' . $e->getMessage());
        }

        // Jika belum mencapai batas reminder dan masih pending, jadwalkan reminder berikutnya
        if ($this->attemptNumber < 3 && $transaction->status === 'pending') {
            static::dispatch($this->transactionId, $this->attemptNumber + 1)
                ->delay(now()->addDays(3));
        }
    }
}
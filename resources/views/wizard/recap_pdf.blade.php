<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Recap Wedding Plan</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            background: #fff;
            color: #2d2d2d;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 24px auto;
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 2px 16px rgba(0, 0, 0, 0.06);
            border: 1.5px solid #fde68a;
            padding: 32px 30px 24px 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 32px;
        }

        .header h1 {
            color: #92400e;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .header .desc {
            color: #aaa;
            font-size: 1rem;
            margin-bottom: 4px;
        }

        .header .thanks {
            color: #d97706;
            font-size: 0.95rem;
        }

        .section-title {
            color: #b45309;
            font-size: 1.1rem;
            font-weight: bold;
            border-top: 2px solid #f59e42;
            padding-top: 16px;
            margin-top: 28px;
            margin-bottom: 7px;
            letter-spacing: .02em;
        }

        .info-grid {
            display: flex;
            flex-wrap: wrap;
            font-size: 0.97rem;
            margin-bottom: 10px;
        }

        .info-grid div {
            flex: 1 1 230px;
            margin-bottom: 4px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
            margin-bottom: 10px;
        }

        .table th,
        .table td {
            padding: 8px 10px;
            border: 1px solid #fde68a;
        }

        .table th {
            background: #fff8e1;
            color: #b45309;
            font-weight: 700;
            text-align: left;
        }

        .img-thumb {
            max-width: 130px;
            max-height: 90px;
            border-radius: 7px;
            border: 1.5px solid #fde68a;
        }

        .total-budget {
            border-top: 2px solid #fde68a;
            margin-top: 28px;
            padding-top: 22px;
            text-align: center;
        }

        .total-budget h2 {
            color: #92400e;
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 0.4rem;
        }

        .total-budget .amount {
            color: #d97706;
            font-size: 2.1rem;
            font-weight: 800;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Recap Wedding Plan</h1>
            <div class="desc">Lavanya Java Heritage</div>
            <div class="thanks">Terima kasih telah mempercayakan momen spesial Anda kepada kami.</div>
        </div>

        <!-- Customer Info -->
        <div class="section-title">Data Pengantin</div>
        <div class="info-grid">
            <div><b>Pengantin Pria:</b> {{ $customer->grooms_name ?? '-' }}</div>
            <div><b>Pengantin Wanita:</b> {{ $customer->brides_name ?? '-' }}</div>
            <div><b>Jumlah Tamu:</b> {{ $customer->guest_count ? $customer->guest_count . ' orang' : '-' }}</div>
            <div><b>Tanggal Pernikahan:</b>
                {{ \Carbon\Carbon::parse($customer->wedding_date ?? now())->isoFormat('D MMMM Y') }}
            </div>
            <div><b>Nomor Telepon:</b> {{ $customer->phone_number ?? '-' }}</div>
            @if(!empty($customer->referral_code))
                <div><b>Kode Referral:</b> <span style="color:#b45309;">{{ $customer->referral_code }}</span></div>
            @endif
        </div>

        <!-- Venue Info -->
        <div class="section-title">Venue</div>
        <table class="table">
            <thead>
                <tr>
                    @if($venue->gambar ?? false)
                        <th>Gambar</th>
                    @endif
                    <th>Nama</th>
                    <th>Tipe</th>
                    @if($venue->kapasitas ?? false)
                        <th>Kapasitas</th>
                    @endif
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    @if($venue->gambar ?? false)
                        <td>
                            <img src="{{ $venue->gambar }}" alt="Venue" class="img-thumb">
                        </td>
                    @endif
                    <td>{{ $venue->nama ?? '-' }}</td>
                    <td>{{ $venue->type ?? '-' }}</td>
                    @if($venue->kapasitas ?? false)
                        <td>{{ $venue->kapasitas }}</td>
                    @endif
                    <td>{!! str($venue->deskripsi ?? '-')->sanitizeHtml() !!}</td>
                </tr>
            </tbody>
        </table>

        <!-- Catering Info -->
        @if($catering)
            <div class="section-title">Catering</div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $catering->nama ?? '-' }}</td>
                        <td>{{ $catering->type ?? '-' }}</td>
                        <td>{!! str($catering->deskripsi ?? '-')->sanitizeHtml() !!}</td>
                    </tr>
                </tbody>
            </table>
        @endif

        <!-- Vendors -->
        <div class="section-title">Vendors</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Deskripsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vendors as $v)
                    <tr>
                        <td>{{ $v->vendor->nama ?? '-' }}</td>
                        <td>{!! str($v->vendor->deskripsi ?? '-')->sanitizeHtml() !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total Budget -->
        <div class="total-budget" style="text-align:center; margin-top:36px;">
            <h2 style="font-size:20px; font-weight:700; color:#b45309; margin-bottom:10px;">Total Budget</h2>
            @if(isset($total_before_discount) && $total_before_discount > 0 && $total_before_discount != $total)
                <div style="font-size:16px; color:#888; margin-bottom:2px;">
                    <span style="text-decoration: line-through;">
                        IDR {{ number_format($total_before_discount, 0, ',', '.') }}
                    </span>
                </div>
                <div class="amount" style="font-size:26px; font-weight:700; color:#ea580c; margin-bottom:12px;">
                    IDR {{ number_format($total, 0, ',', '.') }}
                </div>
            @else
                <div class="amount" style="font-size:26px; font-weight:700; color:#ea580c; margin-bottom:12px;">
                    IDR {{ number_format($total, 0, ',', '.') }}
                </div>
            @endif
            <div style="font-size:12px; color:#888888; margin-top:10px;">
                *Harga dapat berubah sewaktu-waktu
            </div>
        </div>

    </div>
</body>

</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #2563eb; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        h1 { color: #333; margin-bottom: 20px; }
        .transaksi-wrapper { background: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .transaksi-item { padding: 20px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
        .transaksi-item:last-child { border-bottom: none; }
        .transaksi-info h3 { color: #333; margin-bottom: 8px; }
        .transaksi-info p { color: #6b7280; font-size: 14px; margin-bottom: 5px; }
        .transaksi-info .price { font-size: 20px; font-weight: bold; color: #059669; margin-top: 10px; }
        .status { padding: 6px 12px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; }
        .status.pending { background: #fef3c7; color: #92400e; }
        .status.success { background: #d1fae5; color: #065f46; }
        .status.failed { background: #fee2e2; color: #991b1b; }
        .btn-detail { padding: 8px 16px; background: #2563eb; color: white; text-decoration: none; border-radius: 4px; }
        .btn-detail:hover { background: #1d4ed8; }
        .empty-transaksi { text-align: center; padding: 60px 20px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('home') }}" class="back-link">← Kembali ke Homepage</a>

        <h1>📜 Riwayat Transaksi</h1>

        <div class="transaksi-wrapper">
            @forelse($transaksi as $t)
                <div class="transaksi-item">
                    <div class="transaksi-info">
                        <h3>Transaksi #{{ $t['id_transaksi'] }}</h3>
                        <p>Tanggal: {{ date('d M Y H:i', strtotime($t['created_at'])) }}</p>
                        <div class="price">Rp {{ number_format($t['total_harga'], 0, ',', '.') }}</div>
                    </div>
                    <div style="text-align: right;">
                        <span class="status {{ $t['status_pembayaran'] }}">{{ ucfirst($t['status_pembayaran']) }}</span>
                        <br><br>
                        <a href="{{ route('transaksi.detail', $t['id_transaksi']) }}" class="btn-detail">Lihat Detail</a>
                    </div>
                </div>
            @empty
                <div class="empty-transaksi">
                    <h2>Belum Ada Transaksi</h2>
                    <p>Anda belum memiliki riwayat transaksi.</p>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #2563eb; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        h1 { color: #333; margin-bottom: 20px; }
        .alert { padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .alert-success { background: #d1fae5; color: #065f46; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        .keranjang-wrapper { background: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .keranjang-item { display: flex; gap: 20px; padding: 20px; border-bottom: 1px solid #e5e7eb; align-items: center; }
        .keranjang-item:last-child { border-bottom: none; }
        .keranjang-item img { width: 150px; height: 100px; object-fit: cover; border-radius: 4px; }
        .item-info { flex: 1; }
        .item-info h3 { color: #333; margin-bottom: 8px; }
        .item-info .price { font-size: 20px; font-weight: bold; color: #059669; margin-top: 10px; }
        .btn-hapus { padding: 8px 16px; background: #dc2626; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn-hapus:hover { background: #b91c1c; }
        .empty-cart { text-align: center; padding: 60px 20px; color: #6b7280; }
        .empty-cart h2 { margin-bottom: 10px; }
        .summary { background: #f9fafb; padding: 20px; border-radius: 8px; margin-top: 20px; }
        .summary-row { display: flex; justify-content: space-between; padding: 10px 0; font-size: 18px; }
        .summary-row.total { border-top: 2px solid #e5e7eb; margin-top: 10px; padding-top: 15px; font-weight: bold; font-size: 24px; color: #059669; }
        .btn-checkout { display: block; width: 100%; padding: 15px; background: #2563eb; color: white; text-align: center; text-decoration: none; border-radius: 4px; font-size: 18px; margin-top: 20px; border: none; cursor: pointer; }
        .btn-checkout:hover { background: #1d4ed8; }
        .btn-checkout:disabled { background: #9ca3af; cursor: not-allowed; }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('home') }}" class="back-link">← Kembali ke Homepage</a>

        <h1>🛒 Keranjang Belanja</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="keranjang-wrapper">
           @forelse($keranjang as $item)
                <div class="keranjang-item">
                    @if(!empty($item['kelas']['thumbnail']))
                        <img src="{{ asset('storage/' . $item['kelas']['thumbnail']) }}" alt="{{ $item['kelas']['nama_kelas'] }}">
                    @else
                        <img src="https://via.placeholder.com/150x100?text=No+Image" alt="No Image">
                    @endif

                    <div class="item-info">
                        <h3>{{ $item['kelas']['nama_kelas'] }}</h3>
                        <p>Ditambahkan: {{ date('d M Y', strtotime($item['created_at'])) }}</p>
                        <div class="price">Rp {{ number_format($item['kelas']['harga'], 0, ',', '.') }}</div>
                    </div>

                    <form action="{{ route('keranjang.hapus', $item['id_keranjang']) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus item ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-hapus">🗑️ Hapus</button>
                    </form>
                </div>
            @empty
                <div class="empty-cart">
                    <h2>Keranjang Anda Kosong</h2>
                    <p>Belum ada kelas yang ditambahkan ke keranjang.</p>
                    <a href="{{ route('home') }}" class="back-link" style="margin-top: 20px; display: inline-block;">Mulai Belanja</a>
                </div>
            @endforelse

            @if(count($keranjang) > 0)
                <div class="summary">
                    <div class="summary-row">
                        <span>Jumlah Item:</span>
                        <span>{{ count($keranjang) }} kelas</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total Harga:</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <form action="{{ route('transaksi.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-checkout">Lanjutkan ke Pembayaran</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</body>
</html><!DOCTYPE html>
<html lang="id">
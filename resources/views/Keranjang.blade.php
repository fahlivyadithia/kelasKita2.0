<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f5f5f5; color: #333; }
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        
        /* Tombol Kembali */
        .back-link { display: inline-flex; align-items: center; margin-bottom: 20px; color: #4b5563; text-decoration: none; font-weight: 600; transition: color 0.2s; }
        .back-link:hover { color: #2563eb; }
        
        h1 { font-size: 28px; font-weight: 800; color: #111827; margin-bottom: 24px; }
        
        /* Alert Styles */
        .alert { padding: 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 500; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        
        /* Wrapper Keranjang */
        .keranjang-wrapper { background: white; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); overflow: hidden; }
        
        /* Item Keranjang */
        .keranjang-item { display: flex; gap: 24px; padding: 24px; border-bottom: 1px solid #f3f4f6; align-items: center; transition: background 0.2s; }
        .keranjang-item:hover { background: #fafafa; }
        .keranjang-item:last-child { border-bottom: none; }
        
        .keranjang-item img { width: 120px; height: 80px; object-fit: cover; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        
        .item-info { flex: 1; }
        .item-info h3 { font-size: 18px; font-weight: 700; color: #1f2937; margin-bottom: 6px; }
        .item-info .date { font-size: 14px; color: #6b7280; margin-bottom: 12px; }
        .item-info .price { font-size: 18px; font-weight: 700; color: #2563eb; }
        
        /* Tombol Hapus */
        .btn-hapus { display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.2s; }
        .btn-hapus:hover { background: #dc2626; color: white; }
        
        /* Empty State */
        .empty-cart { text-align: center; padding: 80px 20px; }
        .empty-cart h2 { font-size: 24px; color: #374151; margin-bottom: 12px; }
        .empty-cart p { color: #6b7280; margin-bottom: 24px; }
        .btn-shop { display: inline-block; padding: 12px 24px; background: #2563eb; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; transition: background 0.2s; }
        .btn-shop:hover { background: #1d4ed8; }
        
        /* Summary Section */
        .summary { background: #f9fafb; padding: 24px; border-top: 1px solid #e5e7eb; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 16px; color: #4b5563; }
        .summary-row.total { margin-top: 20px; padding-top: 20px; border-top: 1px dashed #d1d5db; font-size: 20px; font-weight: 800; color: #111827; }
        
        .btn-checkout { display: block; width: 100%; padding: 16px; background: #10b981; color: white; border: none; border-radius: 8px; font-size: 18px; font-weight: 700; cursor: pointer; transition: background 0.2s; margin-top: 24px; }
        .btn-checkout:hover { background: #059669; }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ url('/') }}" class="back-link">← Kembali ke Homepage</a>

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
                    @if(!empty($item->kelas->thumbnail))
                        <img src="{{ asset('storage/' . $item->kelas->thumbnail) }}" alt="{{ $item->kelas->nama_kelas }}">
                    @else
                        <img src="https://via.placeholder.com/150x100?text=No+Image" alt="No Image">
                    @endif

                    

                    <div class="item-info">
                        <h3>{{ $item->kelas->nama_kelas ?? 'Kelas tidak ditemukan' }}</h3>
                        <p class="date">Ditambahkan: {{ $item->created_at->format('d M Y') }}</p>
                        <div class="price">Rp {{ number_format($item->kelas->harga ?? 0, 0, ',', '.') }}</div>
                    </div>

                    <form action="{{ route('keranjang.hapus', $item->id_keranjang) }}" method="POST" onsubmit="return confirm('Hapus kelas ini dari keranjang?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-hapus">
                            🗑️ Hapus
                        </button>
                    </form>
                </div>
            @empty
                <div class="empty-cart">
                    <h2>Keranjang Anda Kosong</h2>
                    <p>Sepertinya Anda belum memilih kelas apapun untuk dipelajari.</p>
                    <a href="{{ url('/') }}" class="btn-shop">Mulai Jelajahi Kelas</a>
                </div>
            @endforelse

            @if($keranjang->count() > 0)
                <div class="summary">
                    <div class="summary-row">
                        <span>Jumlah Item</span>
                        <span>{{ $keranjang->count() }} Kelas</span>
                    </div>
                    
                    <div class="summary-row total">
                        <span>Total Pembayaran</span>
                        <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>

                    <form action="{{ route('transaksi.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-checkout">Bayar Sekarang 👉</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
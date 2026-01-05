<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $kelas['nama_kelas'] }}</title>
    <style>
        body { font-family: sans-serif; background: #f9f9f9; margin: 0; padding: 0; }
        
        /* NAVBAR SEDERHANA (Solusi Icon Hilang) */
        .navbar { background: white; padding: 15px 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;}
        .navbar a { text-decoration: none; color: #333; font-weight: bold; }
        .cart-btn { background: #2563eb; color: white !important; padding: 8px 15px; border-radius: 5px; }

        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom: 20px; }
        
        h1 { margin-top: 0; color: #333; }
        .badges { display: flex; gap: 10px; margin-bottom: 20px; }
        .badge { padding: 5px 10px; border-radius: 4px; font-size: 14px; }
        .bg-yellow { background: #fef3c7; color: #92400e; }
        .bg-blue { background: #dbeafe; color: #1e40af; }
        
        .price { font-size: 28px; font-weight: bold; color: #059669; margin: 20px 0; }
        
        /* NOTIFIKASI */
        .alert { padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #10b981; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #ef4444; }

        /* TOMBOL BELI */
        .btn-add { background: #2563eb; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-size: 16px; cursor: pointer; width: 100%; display: block; }
        .btn-add:hover { background: #1d4ed8; }

        .materi-list { list-style: none; padding: 0; }
        .materi-item { background: #f3f4f6; padding: 15px; margin-bottom: 10px; border-left: 4px solid #2563eb; display: flex; align-items: center; }
        .number { background: #2563eb; color: white; width: 30px; height: 30px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-right: 15px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="navbar">
        <a href="{{ url('/') }}">← Kembali ke Home</a>
        
        <a href="{{ route('keranjang.index') }}" class="cart-btn">
            🛒 Lihat Keranjang
        </a>
    </div>

    <div class="container">
        
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">⚠️ {{ session('error') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-error">ℹ️ {{ session('info') }}</div>
        @endif

        <div class="card">
            @if(!empty($kelas['thumbnail']))
                <img src="{{ asset('storage/'.$kelas['thumbnail']) }}" style="width:100%; max-height:300px; object-fit:cover; border-radius:8px; margin-bottom:20px;">
            @endif

            <h1>{{ $kelas['nama_kelas'] }}</h1>

            <div class="badges">
                <span class="badge bg-yellow">📂 {{ $kelas['kategori'] ?? 'Umum' }}</span>
                <span class="badge bg-blue">📊 {{ $kelas['status_publikasi'] ?? 'Aktif' }}</span>
            </div>

            <div style="background: #f9fafb; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <strong>Mentor:</strong> {{ $kelas['mentor']['user']['first_name'] ?? 'Admin' }}<br>
                <small>Keahlian: Tour Guide</small>
            </div>

            <p>{{ $kelas['description'] ?? 'Deskripsi kelas belum tersedia.' }}</p>

            <div class="price">
                Rp {{ number_format($kelas['harga'], 0, ',', '.') }}
            </div>

            <form action="{{ route('keranjang.tambah') }}" method="POST">
                @csrf
                <input type="hidden" name="id_kelas" value="{{ $kelas['id_kelas'] }}">
                
                <button type="submit" class="btn-add">
                    + Tambah ke Keranjang
                </button>
            </form>
        </div>

        <h3>Materi Pembelajaran</h3>
        <ul class="materi-list">
            @forelse($materi as $m)
                <li class="materi-item">
                    <span class="number">{{ $loop->iteration }}</span>
                    {{ $m['judul_materi'] }}
                </li>
            @empty
                <p>Belum ada materi untuk kelas ini.</p>
            @endforelse
        </ul>

    </div>
</body>
</html> 
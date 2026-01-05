<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $kelas['nama_kelas'] }} - Detail Kelas</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #2563eb; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        .kelas-header { background: white; border-radius: 8px; padding: 30px; margin-bottom: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .kelas-header img { width: 100%; max-height: 400px; object-fit: cover; border-radius: 8px; margin-bottom: 20px; }
        .kelas-header h1 { color: #333; margin-bottom: 15px; }
        .kelas-info { display: flex; gap: 20px; margin: 15px 0; flex-wrap: wrap; }
        .kelas-info span { background: #e0e7ff; padding: 8px 15px; border-radius: 4px; font-size: 14px; }
        .mentor-info { background: #f9fafb; padding: 15px; border-radius: 4px; margin: 15px 0; }
        .mentor-info h4 { color: #333; margin-bottom: 5px; }
        .price { font-size: 32px; font-weight: bold; color: #059669; margin: 20px 0; }
        .btn-keranjang { display: inline-block; padding: 15px 30px; background: #2563eb; color: white; text-decoration: none; border-radius: 4px; font-size: 16px; border: none; cursor: pointer; }
        .btn-keranjang:hover { background: #1d4ed8; }
        .materi-section { background: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .materi-section h2 { color: #333; margin-bottom: 20px; }
        .materi-item { border-left: 4px solid #2563eb; padding: 15px; margin-bottom: 15px; background: #f9fafb; }
        .materi-item h3 { color: #2563eb; margin-bottom: 8px; font-size: 18px; }
        .materi-item .urutan { display: inline-block; background: #2563eb; color: white; padding: 4px 10px; border-radius: 50%; font-size: 12px; margin-right: 10px; min-width: 28px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('home') }}" class="back-link">← Kembali ke Homepage</a>

        <div class="kelas-header">
            @if(!empty($kelas['thumbnail']))
                <img src="{{ asset('storage/' . $kelas['thumbnail']) }}" alt="{{ $kelas['nama_kelas'] }}">
            @else
                <img src="https://via.placeholder.com/1000x400?text=No+Image" alt="No Image">
            @endif

            <h1>{{ $kelas['nama_kelas'] }}</h1>
            
            <div class="kelas-info">
                @if(isset($kelas['kategori']))
                    <span>📂 Kategori: {{ ucfirst($kelas['kategori']) }}</span>
                @endif
                @if(isset($kelas['status_publikasi']))
                    <span>📊 Status: {{ ucfirst($kelas['status_publikasi']) }}</span>
                @endif
            </div>

            @if(isset($kelas['mentor']['user']))
                <div class="mentor-info">
                    <h4>👨‍🏫 Mentor: {{ $kelas['mentor']['user']['name'] }}</h4>
                    @if(isset($kelas['mentor']['keahlian']))
                        <p>Keahlian: {{ $kelas['mentor']['keahlian'] }}</p>
                    @endif
                </div>
            @endif

            <p>{{ $kelas['description'] ?? 'Tidak ada deskripsi.' }}</p>

            <div class="price">Rp {{ number_format($kelas['harga'], 0, ',', '.') }}</div>

            <form action="{{ route('keranjang.tambah') }}" method="POST" style="display: inline;">
                @csrf
                <input type="hidden" name="kelas_id" value="{{ $kelas['id_kelas'] }}">
                <button type="submit" class="btn-keranjang">+ Tambah ke Keranjang</button>
            </form>
        </div>

        <div class="materi-section">
            <h2>📚 Materi Pembelajaran</h2>
            @forelse($materi as $m)
                <div class="materi-item">
                    <h3>
                        <span class="urutan">{{ $m['urutan'] }}</span>
                        {{ $m['judul_materi'] }}
                    </h3>
                </div>
            @empty
                <p>Belum ada materi tersedia untuk kelas ini.</p>
            @endforelse
        </div>
    </div>
</body>
</html>
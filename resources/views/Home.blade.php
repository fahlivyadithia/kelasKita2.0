<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage - Platform Kursus</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        h1, h2 { color: #333; margin-bottom: 20px; }
        .kelas-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .kelas-card { background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .kelas-card img { width: 100%; height: 200px; object-fit: cover; border-radius: 4px; margin-bottom: 10px; }
        .kelas-card h3 { color: #2563eb; margin-bottom: 10px; }
        .kelas-card .price { font-size: 18px; font-weight: bold; color: #059669; margin-top: 10px; }
        .mentor-list, .review-list { display: flex; gap: 20px; overflow-x: auto; margin-bottom: 40px; }
        .mentor-card, .review-card { background: white; border-radius: 8px; padding: 15px; min-width: 250px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .mentor-card img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin-bottom: 10px; }
        .review-card .rating { color: #f59e0b; font-size: 18px; margin-bottom: 5px; }
        .btn { display: inline-block; padding: 10px 20px; background: #2563eb; color: white; text-decoration: none; border-radius: 4px; margin-top: 10px; }
        .btn:hover { background: #1d4ed8; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Selamat Datang di Platform Kursus</h1>

        <section>
            <h2>Daftar Kelas</h2>
            <div class="kelas-grid">
                 @forelse($kelas as $k)
                    <div class="kelas-card">
                        @if(!empty($k->thumbnail))
                            <img src="{{ asset('storage/' . $k->thumbnail) }}" alt="{{ $k->nama_kelas }}">
                        @else
                            <img src="https://via.placeholder.com/300x200?text=No+Image" alt="No Image">
                        @endif
                        <h3>{{ $k->nama_kelas }}</h3>
                        <p>{{ Str::limit($k->deskripsi ?? '', 100) }}</p>
                        <p><small>Kategori: {{ $k->kategori }}</small></p>
                        @if(isset($k->mentor->user))
                            <p><small>Mentor: {{ $k->mentor->user->first_name }} {{ $k->mentor->user->last_name }}</small></p>
                        @endif
                        <div class="price">Rp {{ number_format($k->harga, 0, ',', '.') }}</div>
                        <a href="{{ route('kelas.detail', $k->id_kelas) }}" class="btn">Lihat Detail</a>
                    </div>
                @empty
                    <p>Belum ada kelas tersedia.</p>
                @endforelse
            </div>
        </section>

        <section>
            <h2>Mentor Kami</h2>
            <div class="mentor-list">
                @forelse($mentors as $mentor)
                    <div class="mentor-card">
                        @if(isset($mentor->user->foto_profil) && !empty($mentor->user->foto_profil))
                            <img src="{{ asset('storage/' . $mentor->user->foto_profil) }}" alt="Mentor">
                        @else
                            <img src="https://via.placeholder.com/80?text=Mentor" alt="Mentor">
                        @endif
                        <h4>{{ $mentor->user->first_name ?? '' }} {{ $mentor->user->last_name ?? '' }}</h4>
                        <p>{{ $mentor->keahlian }}</p>
                    </div>
                @empty
                    <p>Belum ada mentor tersedia.</p>
                @endforelse
            </div>
        </section>

        <section>
            <h2>Review dari Pengguna</h2>
            <div class="review-list">
                @forelse($reviews as $review)
                    <div class="review-card">
                        <h4>{{ $review->user->first_name ?? 'Anonymous' }} {{ $review->user->last_name ?? '' }}</h4>
                        <div class="rating">
                            @for($i = 0; $i < $review->bintang; $i++)
                                ★
                            @endfor
                        </div>
                        <p>{{ Str::limit($review->isi_review, 80) }}</p>
                        <small>Kelas: {{ $review->kelas->nama_kelas ?? '' }}</small><br>
                        <small>{{ date('d M Y', strtotime($review->created_at)) }}</small>
                    </div>
                @empty
                    <p>Belum ada review.</p>
                @endforelse
            </div>
        </section>
    </div>
</body>
</html>
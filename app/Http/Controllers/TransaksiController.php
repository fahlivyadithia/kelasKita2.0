<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Keranjang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function process(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized. Silakan login.'], 401);
            }
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // 2. AMBIL DATA KERANJANG (Logika Web vs API)
        $cart = [];

        if ($request->wantsJson() || $request->is('api/*')) {
            // [API] Ambil dari JSON Body (Raw Data)
            // Format JSON harus: { "items": [ { "id_kelas": 1, "harga": 125000 } ] }
            $cart = $request->input('items'); 
        } else {
            
            $cart = session('cart');
        }

        if (!$cart || count($cart) <= 0) {
            $pesan = 'Keranjang Anda kosong!';
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['status' => 'error', 'message' => $pesan], 400);
            }
            return redirect()->route('cart.index')->with('error', $pesan);
        }

        // 3. Hitung Total Harga
        $totalHarga = 0;
        foreach ($cart as $item) {
            // Support format array maupun object (jika dari API kadang bentuknya array)
            $harga = is_array($item) ? $item['harga'] : $item->harga; 
            $totalHarga += $harga;
        }

        // 4. MULAI TRANSAKSI DATABASE
        DB::beginTransaction();
        try {
            // A. Insert ke Tabel 'transaksi'
            $idTransaksi = DB::table('transaksi')->insertGetId([
                'id_user'       => $user->id_user ?? $user->id,
                'kode_invoice'  => 'INV-' . strtoupper(uniqid()),
                'total_harga'   => $totalHarga,
                'status'        => 'pending',
                'tgl_transaksi' => now(),
                'created_at'    => now(),
                'updated_at'    => now()
            ]);

            // B. Insert ke Tabel 'detail_transaksi'
            foreach ($cart as $item) {
                // Pastikan akses key array aman untuk API & Session
                $idKelas = is_array($item) ? $item['id_kelas'] : $item->id_kelas;
                $harga   = is_array($item) ? $item['harga'] : $item->harga;

                DB::table('detail_transaksi')->insert([
                    'id_transaksi'    => $idTransaksi,
                    'id_kelas'        => $idKelas,
                    'harga_saat_beli' => $harga,
                    'created_at'      => now(),
                    'updated_at'      => now()
                ]);
            }

            // Commit
            DB::commit();

            // 5. Hapus Session Cart (Khusus Web)
            if (!($request->wantsJson() || $request->is('api/*'))) {
                session()->forget('cart');
            }

            // --- RESPONSE API ---
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Transaksi berhasil dibuat',
                    'data' => [
                        'id_transaksi' => $idTransaksi,
                        'total_harga' => $totalHarga,
                        'invoice' => 'INV-' . time() // Dummy invoice code preview
                    ]
                ], 201);
            }

            // --- RESPONSE WEB ---
            return redirect()->route('transaksi.show', $idTransaksi)->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Gagal memproses transaksi: ' . $e->getMessage());
        }
    }

    public function show(Request $request, $id)
    {
        $transaksi = DB::table('transaksi')->where('id_transaksi', $id)->first();

        if (!$transaksi) {
            $pesan = 'Transaksi tidak ditemukan';
            if ($request->wantsJson() || $request->is('api/*')) {
                return response()->json(['status' => 'error', 'message' => $pesan], 404);
            }
            abort(404, $pesan);
        }

        $items = DB::table('detail_transaksi')
            ->join('kelas', 'detail_transaksi.id_kelas', '=', 'kelas.id_kelas')
            ->select('detail_transaksi.*', 'kelas.nama_kelas', 'kelas.thumbnail')
            ->where('detail_transaksi.id_transaksi', $id)
            ->get();

        $metodePembayaran = DB::table('metode_pembayaran')->where('is_active', 1)->get();

        // --- RESPONSE API ---
        if ($request->wantsJson() || $request->is('api/*')) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'transaksi' => $transaksi,
                    'items' => $items,
                    'metode_pembayaran' => $metodePembayaran
                ]
            ], 200);
        }

        return view('Transaksi', compact('transaksi', 'items', 'metodePembayaran'));
    }

    // Method untuk menambahkan item ke cart (add to cart)
    public function addToCart(Request $request)
    {
        $request->validate([
            'id_kelas' => 'required|exists:kelas,id_kelas',
            'harga' => 'required|numeric',
        ]);

        // Ambil cart dari session, jika tidak ada buat array kosong
        $cart = session('cart', []);
        
        // Tambahkan item baru ke cart
        $cart[] = [
            'id_kelas' => $request->id_kelas,
            'harga' => $request->harga,
            // data lain bisa ditambahkan disini jika diperlukan
        ];
        
        // Simpan kembali ke session
        session(['cart' => $cart]);

        return redirect()->back()->with('success', 'Kelas berhasil ditambahkan ke keranjang');
    }

    // Method untuk proses pembayaran
    public function bayar(Request $request)
    {
        $request->validate([
            'id_transaksi' => 'required|exists:transaksi,id_transaksi',
            'id_metode_pembayaran' => 'required',
            'bukti_bayar' => 'nullable|image|max:2048',
        ]);
        
        try {
            DB::table('transaksi')
                ->where('id_transaksi', $request->id_transaksi)
                ->update([
                    'status' => 'menunggu_konfirmasi',
                    'id_metode_pembayaran' => $request->id_metode_pembayaran,
                    'bukti_bayar' => $request->file('bukti_bayar') 
                        ? $request->file('bukti_bayar')->store('bukti_bayar', 'public') 
                        : null,
                    'updated_at' => now()
                ]);
            
            return redirect()->back()->with('success', 'Pembayaran berhasil diupload');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal upload pembayaran: ' . $e->getMessage());
        }
    }
}
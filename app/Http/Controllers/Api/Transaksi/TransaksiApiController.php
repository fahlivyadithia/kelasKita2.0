<?php

namespace App\Http\Controllers\Api\Transaksi;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\Keranjang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiApiController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->query('user_id');

        // Pastikan kolom 'status_pembayaran' benar (atau ganti 'status' jika di DB namanya status)
        $transaksi = Transaksi::where('id_user', $userId)
            ->select('id_transaksi', 'id_user', 'total_harga', 'status_pembayaran', 'created_at') 
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transaksi
        ]);
    }

    public function show($id)
    {
        $transaksi = Transaksi::with(['transaksiDetail.kelas:id_kelas,nama_kelas,harga'])
            ->where('id_transaksi', $id)
            ->select('id_transaksi', 'id_user', 'total_harga', 'status_pembayaran', 'created_at')
            ->first();

        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $transaksi
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer'
        ]);

        $userId = $request->user_id;

        // Ambil data keranjang
        $keranjang = Keranjang::with('kelas:id_kelas,nama_kelas,harga')
            ->where('id_user', $userId)
            ->get();

        if ($keranjang->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang kosong'
            ], 400);
        }

        // Hitung total
        $totalHarga = $keranjang->sum(function($item) {
            return $item->kelas->harga ?? 0;
        });

        // --- PERBAIKAN: Generate Kode Invoice ---
        // Contoh Output: INV-20260106-5923
        $kodeInvoice = 'INV-' . date('Ymd') . '-' . mt_rand(1000, 9999);

        DB::beginTransaction();
        try {
            // Buat transaksi
            $transaksi = Transaksi::create([
                'id_user' => $userId,
                'kode_invoice' => $kodeInvoice, // <--- WAJIB DITAMBAHKAN
                'total_harga' => $totalHarga,
                'status_pembayaran' => 'pending' // Pastikan nama kolom di DB 'status_pembayaran' atau 'status'
            ]);

            // Buat transaksi detail
            foreach ($keranjang as $item) {
                TransaksiDetail::create([
                    'id_transaksi' => $transaksi->id_transaksi,
                    'id_kelas' => $item->id_kelas,
                    'harga_saat_beli' => $item->kelas->harga
                ]);
            }

            // Hapus keranjang
            Keranjang::where('id_user', $userId)->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat',
                'data' => $transaksi
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}
<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransaksiController extends Controller
{
    public function index()
    {
        $apiBaseUrl = config('app.url') . '/api';
        $userId = auth()->id() ?? 1; // Asumsikan user sudah login

        $response = Http::get("{$apiBaseUrl}/transaksi", [
            'user_id' => $userId
        ]);

        $transaksi = $response->successful() ? $response->json()['data'] : [];

        return view('transaksi', compact('transaksi'));
    }

        public function checkout()
    {
        $apiBaseUrl = config('app.url') . '/api';
        $userId = auth()->id() ?? 1; // Asumsikan user sudah login

        $response = Http::post("{$apiBaseUrl}/transaksi/checkout", [
            'user_id' => $userId
        ]);

        if ($response->successful()) {
            $transaksiId = $response->json()['data']['id_transaksi'];
            return redirect()->route('transaksi.detail', $transaksiId)
                ->with('success', 'Transaksi berhasil dibuat! Silakan lanjutkan pembayaran.');
        }

        return redirect()->route('keranjang.index')
            ->with('error', $response->json()['message'] ?? 'Gagal membuat transaksi');
    }

        public function show($id)
    {
        $apiBaseUrl = config('app.url') . '/api';

        $response = Http::get("{$apiBaseUrl}/transaksi/{$id}");

        if (!$response->successful()) {
            abort(404, 'Transaksi tidak ditemukan');
        }

        $transaksi = $response->json()['data'];

        return view('detail_transaksi', compact('transaksi'));
    }
}
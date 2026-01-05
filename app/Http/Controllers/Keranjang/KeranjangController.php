<?php

namespace App\Http\Controllers\Keranjang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KeranjangController extends Controller
{
    public function index()
    {
        $apiBaseUrl = config('app.url') . '/api';
        $userId = auth()->id() ?? 1; // Asumsikan user sudah login

        $response = Http::get("{$apiBaseUrl}/keranjang", [
            'user_id' => $userId
        ]);

        $keranjang = $response->successful() ? $response->json()['data'] : [];
        $total = $response->successful() ? $response->json()['total'] : 0;

        return view('keranjang', compact('keranjang', 'total'));
    }

    public function tambah(Request $request)
    {
        $apiBaseUrl = config('app.url') . '/api';
        $userId = auth()->id() ?? 1; // Asumsikan user sudah login

        $response = Http::post("{$apiBaseUrl}/keranjang/tambah", [
            'user_id' => $userId,
            'kelas_id' => $request->kelas_id
        ]);

        if ($response->successful()) {
            return redirect()->route('keranjang.index')
                ->with('success', 'Kelas berhasil ditambahkan ke keranjang');
        }

        return redirect()->back()
            ->with('error', $response->json()['message'] ?? 'Gagal menambahkan ke keranjang');
    }

    public function hapus($id)
    {
        $apiBaseUrl = config('app.url') . '/api';

        $response = Http::delete("{$apiBaseUrl}/keranjang/{$id}");

        if ($response->successful()) {
            return redirect()->route('keranjang.index')
                ->with('success', 'Item berhasil dihapus dari keranjang');
        }

        return redirect()->back()
            ->with('error', 'Gagal menghapus item');
    }
}
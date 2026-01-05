<?php

namespace App\Http\Controllers\Api\Keranjang;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use Illuminate\Http\Request;

class KeranjangApiController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->query('user_id');

        $keranjang = Keranjang::with(['kelas:id_kelas,nama_kelas,harga,thumbnail'])
            ->where('id_user', $userId)
            ->select('id_keranjang', 'id_user', 'id_kelas', 'created_at')
            ->get();

        $total = $keranjang->sum(function($item) {
            return $item->kelas->harga ?? 0;
        });

        return response()->json([
            'success' => true,
            'data' => $keranjang,
            'total' => $total
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'kelas_id' => 'required|integer'
        ]);

        $exists = Keranjang::where('id_user', $request->user_id)
            ->where('id_kelas', $request->kelas_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Kelas sudah ada di keranjang'
            ], 400);
        }

        $keranjang = Keranjang::create([
            'id_user' => $request->user_id,
            'id_kelas' => $request->kelas_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan ke keranjang',
            'data' => $keranjang
        ], 201);
    }

    public function destroy($id)
    {
        $keranjang = Keranjang::where('id_keranjang', $id)->first();

        if (!$keranjang) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan'
            ], 404);
        }

        $keranjang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus dari keranjang'
        ]);
    }
}
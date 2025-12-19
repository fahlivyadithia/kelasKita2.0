<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('metode_pembayaran', function (Blueprint $table) {
            $table->id('id_mp');
            $table->string('nama_metode'); // Contoh: Bank BCA, Dana
            $table->string('nomor_rekening');
            $table->string('nama_pemilik'); // Nama Admin/Perusahaan
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            // Hapus foreignId('id_user') agar tidak campur dengan data user
        });
    }

    public function down()
    {
        Schema::dropIfExists('metode_pembayaran');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('mentors', function (Blueprint $table) {
            $table->id('id_mentor');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->string('keahlian')->nullable();
            $table->text('deskripsi_mentor')->nullable();

            // Tambahkan kolom untuk rekening pencairan dana mentor
            $table->string('bank_penerima')->nullable();
            $table->string('nomor_rekening_mentor')->nullable();
            $table->string('nama_rekening_mentor')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mentors');
    }
};

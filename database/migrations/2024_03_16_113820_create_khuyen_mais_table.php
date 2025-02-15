<?php

use App\Models\KhuyenMai;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('khuyen_mais', function (Blueprint $table) {
            $table->id();
            $table->string('ten_khuyen_mai', 255);
            $table->foreignId('loai_phong_id')->constrained('loai_phongs')->onDelete('cascade');
            $table->string('ma_giam_gia', 255);
            $table->boolean('loai_giam_gia');
            $table->decimal('gia_tri_giam');
            // $table->string('mo_ta', 255);
            // $table->integer('so_luong');
            $table->dateTime('ngay_bat_dau');
            $table->dateTime('ngay_ket_thuc');
            $table->integer('trang_thai');
            // $table->boolean('trang_thai')->default(KhuyenMai::DANG_AP_DUNG);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khuyen_mais');
    }
};

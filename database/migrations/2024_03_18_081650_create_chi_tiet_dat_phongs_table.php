<?php

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
        Schema::create('chi_tiet_dat_phongs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dat_phong_id');
            $table->foreign('dat_phong_id')->references('id')->on('dat_phongs');
            $table->float('thanh_tien')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_dat_phongs');
    }
};

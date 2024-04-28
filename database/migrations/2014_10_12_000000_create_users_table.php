<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('ten_nguoi_dung');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('anh')->nullable();
            $table->string('dia_chi')->nullable();
            $table->string('so_dien_thoai');
            $table->string('gioi_tinh')->nullable();
            $table->date('ngay_sinh')->nullable();
            $table->unsignedBigInteger('id_vai_tro');
            // $table->foreign('id_vai_tro')->constrained('vai_tros');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('id_vai_tro')->references('id')->on('vai_tros');
        });
        $password = Hash::make('admin');
        DB::table('users')->insert([
            'ten_nguoi_dung' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => $password,
            'so_dien_thoai' => '1234567890',
            'id_vai_tro' => 2,
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

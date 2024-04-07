<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatPhongLoaiPhong extends Model
{
    use HasFactory;
    protected $fillable = [
        'dat_phong_id', // Thêm trường dat_phong_id vào fillable
        'loai_phong_id',
        'so_luong_phong',
    ];

}

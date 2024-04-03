<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class KhuyenMai extends Model
{
    use HasFactory,
    SoftDeletes;
    //lOẠI GIẢM GIÁ
    const GIAM_THEO_PHAN_TRAM = 1;
    const GIAM_THEO_VND = 0; 
    //TRẠNG THÁI
    const CHUA_AP_DUNG = 0;
    const DANG_AP_DUNG = 1;
    const KET_THUC = 2; 
   
protected $fillable = [
    'ten_khuyen_mai',
    'loai_phong_id',
    'ma_giam_gia',
    'loai_giam_gia',
    'gia_tri_giam',
    'mo_ta',
    // 'so_luong',
    'ngay_bat_dau',
    'ngay_ket_thuc',
    'trang_thai',
];

// protected $dates = ['ngay_bat_dau', 'ngay_ket_thuc'];

public function checkStatus()
{
    $now = Carbon::now();

    if ($this->ngay_bat_dau > $now) {
        return 0; // Chưa áp dụng
    } elseif ($this->ngay_ket_thuc < $now) {
        return 2; // Kết thúc
    } else {
        return 1; // Đang áp dụng
    }
}


public function loai_phong()
{
    return $this->belongsTo(Loai_phong::class);
}

}

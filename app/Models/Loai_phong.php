<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // add soft delete

class Loai_phong extends Model
{
    use HasFactory,
        SoftDeletes;

    const CON_PHONG = 1;
    const HET_PHONG = 0;
    protected $fillable = [
        'id',
        'ten',
        'anh',
        'gia',
        'gia_ban_dau',
        'gioi_han_nguoi',
        'so_luong',
        'mo_ta_ngan',
        'mo_ta_dai',
        'trang_thai',
    ];

    public function anhPhong(){
        return $this->hasMany('App\Models\Anh_phong');
    }

    public function Phong(){
        return $this->hasMany(Phong::class);
    }
    public function datPhongs()
    {
        return $this->belongsToMany(DatPhong::class, 'dat_phong_loai_phongs', 'loai_phong_id', 'dat_phong_id')->withPivot('so_luong_phong');
    }

    public function Loai_phong()
    {
        return $this->belongsTo(Loai_phong::class, 'id');
    }

}

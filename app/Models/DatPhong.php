<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;


class DatPhong extends Model
{
    use HasFactory, SoftDeletes;

    const DA_XAC_NHAN = 1;
    const CHO_XAC_NHAN = 0;

    protected $table = 'dat_phongs';
    protected $fillable = [
        'user_id',
        'loai_phong_id',
        'phong_id',
        'phong_ids',
        'don_gia',
        'so_luong_nguoi',
        'so_luong_phong',
        'thoi_gian_den',
        'thoi_gian_di',
        'dich_vu_id',
        'khuyen_mai_id',
        'tong_tien',
        'payment',
        'trang_thai',
        'ghi_chu',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // 'user_id' là khóa ngoại trong bảng DatPhong tham chiếu đến id trong bảng User
    }
    public function phongs()
    {
        return $this->belongsToMany(Phong::class, 'dat_phong_noi_phongs', 'dat_phong_id', 'phong_id');
    }
    public function dichvus()
    {
        return $this->belongsToMany(Phong::class, 'dat_phong_dich_vus', 'dat_phong_id', 'dich_vu_id');
    }
    protected function loai_phong()
    {
        // return $this->belongsTo('App\Models\Loai_phong','loai_phong_id','id');
        return $this->belongsTo(Loai_phong::class);
    }
    public function khuyen_mai()
    {
        return $this->belongsTo(KhuyenMai::class, 'khuyen_mai_id'); // 'user_id' là khóa ngoại trong bảng DatPhong tham chiếu đến id trong bảng User
    }
    protected function dich_vu()
    {
        // return $this->belongsTo('App\Models\Loai_phong','loai_phong_id','id');
        return $this->belongsTo(DichVu::class);
    }

    protected $phongIdTemp;

    // Phương thức để gán phong_id tạm thời
    public function setPhongIdTemp($phongId)
    {
        $this->phongIdTemp = $phongId;
        return $this;
    }

    // Phương thức để lấy phong_id tạm thời
    public function getPhongIdTemp()
    {
        return $this->phongIdTemp;
    }
    protected $dichVuIdTemp;

    // Phương thức để gán phong_id tạm thời
    public function setDichVuIdTemp($dichVuId)
    {
        $this->dichVuIdTemp = $dichVuId;
        return $this;
    }

    // Phương thức để lấy phong_id tạm thời
    public function getDichVuIdTemp()
    {
        return $this->dichVuIdTemp;
    }

    public function loaiPhongs() {
        return $this->belongsToMany(Loai_phong::class)->withPivot('so_luong');
    }


    // public function getPhongIdsAttribute($value)
    // {
    //     return explode(',', $value);
    // }

    // public function setPhongIdsAttribute($value)
    // {
    //     $this->attributes['phongIds'] = implode(',', $value);
    // }

   
    public function DatPhong()
    {
        return $this->belongsTo(DatPhong::class); // Giả sử có mối quan hệ many-to-one với model Room
    }
}

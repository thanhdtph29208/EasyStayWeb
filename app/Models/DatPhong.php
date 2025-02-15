<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class DatPhong extends Model
{
    use HasFactory, SoftDeletes;

    const DA_XAC_NHAN = 1;
    const CHO_XAC_NHAN = 0;

    protected $table = 'dat_phongs';
    protected $fillable = [
        'user_id',
        'loai_phong_id',
        'email',
        'ho_ten',
        'so_dien_thoai',
        'phong_id',
        'don_gia',
        'so_luong_nguoi',
        'so_luong_phong',
        'thoi_gian_den',
        'thoi_gian_di',
        'dich_vu_id',
        'khuyen_mai_id',
        'invoice',
        'tong_tien',
        'payment',
        'trang_thai',
        'ghi_chu',
    ];

    protected $dates = [
        'thoi_gian_den',
        'thoi_gian_di',
    ];

    // Accessors
    public function getThoiGianDenAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d 14:00:00');
    }

    public function getThoiGianDiAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d 12:00:00');
    }

    // Mutators
    public function setThoiGianDenAttribute($value)
{
    $this->attributes['thoi_gian_den'] = Carbon::parse($value)->startOfDay()->addHours(14);
}

public function setThoiGianDiAttribute($value)
{
    $this->attributes['thoi_gian_di'] = Carbon::parse($value)->startOfDay()->addHours(12);
}


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // 'user_id' là khóa ngoại trong bảng DatPhong tham chiếu đến id trong bảng User
    }


    public function Phong()
    {
        // return $this->belongsTo('App\Models\Loai_phong','loai_phong_id','id');
        return $this->belongsTo(Phong::class);
    }


    public function phongs()
    {
        return $this->belongsToMany(Phong::class, 'dat_phong_noi_phongs', 'dat_phong_id', 'phong_id');
    }

    public function dichVu()
    {
        // return $this->belongsTo('App\Models\Loai_phong','loai_phong_id','id');
        return $this->belongsTo(DichVu::class);
    }

    public function dichVus()
    {
        return $this->belongsToMany(DichVu::class, 'dat_phong_dich_vus', 'dat_phong_id', 'dich_vu_id')->withPivot('so_luong');
    }

    public function loaiPhong()
    {
        // return $this->belongsTo('App\Models\Loai_phong','loai_phong_id','id');
        return $this->belongsTo(Loai_phong::class);
    }

    public function loaiPhongs()
    {
        return $this->belongsToMany(Loai_phong::class, 'dat_phong_loai_phongs', 'dat_phong_id', 'loai_phong_id')->withPivot('so_luong_phong');
    }

    public function Loai_phong()
    {
        return $this->belongsTo(Loai_phong::class, 'loai_phong_id'); // 'user_id' là khóa ngoại trong bảng DatPhong tham chiếu đến id trong bảng User
    }


    public function khuyen_mai()
    {
        return $this->belongsTo(KhuyenMai::class, 'khuyen_mai_id'); // 'user_id' là khóa ngoại trong bảng DatPhong tham chiếu đến id trong bảng User
    }

    public $loaiPhongIdTemp;
    public function setLoaiPhongIdTemp($loaiPhongId)
    {
        $this->loaiPhongIdTemp = $loaiPhongId;
        return $this;
    }

    // Phương thức để lấy phong_id tạm thời
    public function getLoaiPhongIdTemp()
    {
        return $this->loaiPhongIdTemp;
    }

    public $phongIdTemp;

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
    public $dichVuIdTemp;

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

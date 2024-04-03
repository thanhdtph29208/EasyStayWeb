<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatPhongNoiPhong extends Model
{
    use HasFactory;
    protected $fillable = [
        'dat_phong_id','phong_id',
    ];

    protected $table = 'dat_phong_noi_phongs'; 

    public function phong()
    {
        return $this->belongsTo('App\Models\Phong', 'phong_id', 'id');
    }

}

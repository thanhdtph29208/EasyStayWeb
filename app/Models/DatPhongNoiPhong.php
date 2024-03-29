<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatPhongNoiPhong extends Model
{
    use HasFactory;
    protected $table = 'dat_phong_noi_phongs';
    protected $fillable = [
        'phong_id',
        'dat_phong_id',
    ];

}

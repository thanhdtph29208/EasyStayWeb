<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class comments extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','bai_viet_id','content','created_at','updated_at'];
    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function bai_viet(){
        return $this->hasOne(Bai_viet::class,'id','bai_viet_id');
    }
}

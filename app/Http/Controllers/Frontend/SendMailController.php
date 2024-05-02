<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\DatPhong;
use App\Models\Hotel;
use App\Models\DatPhongLoaiPhong;
use App\Models\Loai_phong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendMailController extends Controller
{
    public function sendMail(){
        $ttNguoiDung = auth()->user();
        $ttKhachSan = Hotel::all();
        $datPhong = DatPhong::where('user_id',$ttNguoiDung->id)->latest()->first();
        $loai_phong_ids =DatPhongLoaiPhong::where('dat_phong_id', $datPhong->id)->pluck('loai_phong_id');
        $so_luong_phong =DatPhongLoaiPhong::where('dat_phong_id', $datPhong->id)->pluck('so_luong_phong');
        $tenLoaiPhongs = collect();
        $giaLoaiPhongs = collect();
        foreach($loai_phong_ids as $loai_phong_id){
            $tenLoaiPhong = Loai_phong::where('id', $loai_phong_id)->pluck('ten');
            $tenLoaiPhongs = $tenLoaiPhongs->merge($tenLoaiPhong);
        };
        $loaiPhong = $tenLoaiPhongs -> zip($so_luong_phong);
        // dd($datPhong);
        Mail::send('client.emails.form', compact('ttNguoiDung','ttKhachSan','loaiPhong','datPhong'), function($email) use ($ttNguoiDung,$ttKhachSan,$datPhong) {
            $email->to($datPhong->email, $ttNguoiDung->ten_nguoi_dung);
            $email->subject('Xác nhận đặt phòng khách sạn ' . $ttKhachSan[0]->ten . ' thành công.');
        });
        return view('client.payment_success');

    }


    
}

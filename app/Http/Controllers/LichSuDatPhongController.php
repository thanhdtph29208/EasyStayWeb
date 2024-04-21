<?php

namespace App\Http\Controllers;

use App\Models\ChiTietDatPhong;
use Illuminate\Http\Request;
use App\Models\DatPhong;
use App\Models\DatPhongLoaiPhong;
use App\Models\DatPhongNoiPhong;
use App\Models\KhuyenMai;
use App\Models\Loai_phong;
use App\Models\Phong;
use App\Models\User;
use Carbon\Carbon;




class LichSuDatPhongController extends Controller
{

    public function userBookingHistory(Request $request)
    {




        $userBookings = DatPhong::where('user_id', auth()->id())->get();
        $loaiPhongIds = collect();
        foreach ($userBookings as $userBook){
            $loaiPhongId = DatPhongLoaiPhong::where('dat_phong_id',$userBook->id)->pluck('loai_phong_id');
            $loaiPhongIds = $loaiPhongIds->merge($loaiPhongId);
        }
        $ten_loai_phongs=collect();
        foreach($loaiPhongIds as $loaiPhong){
            $ten_loai_phong=Loai_phong::where('id', $loaiPhong)->pluck('ten');
            $ten_loai_phongs = $ten_loai_phongs->merge($ten_loai_phong);
        }

        // dd($userBookings,$loaiPhongIds);
        // Lấy thông tin của người dùng đang đăng nhập
        $user = auth()->user();



        return view('client.pages.lich_su_dat_phong', compact('userBookings','ten_loai_phongs'));
    }

    // Trong hàm show của controller
    public function show($id)
    {
        $userBooking = DatPhong::findOrFail($id);
        // dd($userBooking->id);
        $loaiPhongId = DatPhongLoaiPhong::where('dat_phong_id',$userBooking->id)->pluck('loai_phong_id');
        $phongId = DatPhongNoiPhong::where('dat_phong_id',$userBooking->id)->pluck('phong_id');
        // dd($phongId);
        $ten_loai_phongs = collect();
        foreach($loaiPhongId as $loaiPhong){
            $ten_loai_phong=Loai_phong::where('id', $loaiPhong)->pluck('ten');
            $ten_loai_phongs = $ten_loai_phongs->merge($ten_loai_phong);
        }
        $so_luong_phong =DatPhongLoaiPhong::where('dat_phong_id', $id)->pluck('so_luong_phong');
        $loaiPhong = $loaiPhongId->zip($ten_loai_phongs,$so_luong_phong);
        $khuyen_mai = KhuyenMai::where('id', $userBooking->khuyen_mai_id)->get();
        // dd($khuyen_mai[0]);
        $thanh_tien = ChiTietDatPhong::where('dat_phong_id', $userBooking->id)->pluck('thanh_tien');
        return view('client.pages.chi_tiet_lsphong', compact('userBooking','loaiPhongId','ten_loai_phongs','so_luong_phong','loaiPhong','khuyen_mai','thanh_tien'));
    }




}

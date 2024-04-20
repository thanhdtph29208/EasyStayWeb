<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatPhong;
use App\Models\Loai_phong;
use App\Models\Phong;
use App\Models\User;
use Carbon\Carbon;




class LichSuDatPhongController extends Controller
{
 
    public function userBookingHistory(Request $request)
    {




        $userBookings = DatPhong::where('user_id', auth()->id())->get();

        // Lấy thông tin của người dùng đang đăng nhập
        $user = auth()->user();



        return view('client.pages.lich_su_dat_phong', compact('userBookings'));
    }
 
    // Trong hàm show của controller
    public function show($id)
    {
        $userBooking = DatPhong::findOrFail($id);
        // Chuyển đổi thời gian đến thành đối tượng Carbon
        $thoi_gian_den = Carbon::parse($userBooking->thoi_gian_den);
        $thoi_gian_di = Carbon::parse($userBooking->thoi_gian_di);
    



    
        return view('client.pages.chi_tiet_lsphong', compact('userBooking', 'thoi_gian_den', 'thoi_gian_di'));
    }
    

    
    
}

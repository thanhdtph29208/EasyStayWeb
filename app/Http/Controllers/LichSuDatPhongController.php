<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatPhong; 
use App\Models\Loai_phong;
use App\Models\Phong;
use App\Models\User; 



class LichSuDatPhongController extends Controller
{

    public function userBookingHistory(Request $request)
    {

       
      

        // Lấy lịch sử đặt phòng của người dùng hiện tại
        $userBookings = DatPhong::where('user_id', auth()->id())
        ->with('DatPhong') // Load thông tin người dùng
        ->get();

  


        return view('client.pages.lich_su_dat_phong', compact('userBookings'));
    }
}

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

       
      

        $userBookings = DatPhong::where('user_id', auth()->id())->get();

        // Lấy thông tin của người dùng đang đăng nhập
        $user = auth()->user();
  


        return view('client.pages.lich_su_dat_phong', compact('userBookings'));
    }
}

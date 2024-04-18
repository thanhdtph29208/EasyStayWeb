<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Anh_phong;
use App\Models\DanhGia;
use App\Models\Hotel;
use App\Models\Loai_phong;
use App\Models\Phong;
use App\Models\User;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;



use PHPUnit\Event\TestSuite\Loaded;

class ChiTietLoaiPhongController extends Controller
{
    public function detail(string $id)
    {
        $detail = Loai_phong::find($id);
        $loaiPhongIds = $detail->phongs->pluck('loai_phong_id')->unique();
        $khach_sans = Hotel::all();
        $danh_gias = DanhGia::where('loai_phong_id', $id)->get();
        $khach_hangs = User::all();
        $trang_thai = Loai_phong::all();
     
        return view('client.pages.loai_phong.chitietloaiphong', compact('detail','khach_sans','danh_gias','khach_hangs','trang_thai', 'loaiPhongIds'));
    }

    public function allRoom()
    {
        $rooms = Loai_phong::all();
        return view('client.pages.loai_phong.loai_phong', compact('rooms'));
    }


    public function addCTLS(Request $request)
    {

        $loai_phong = Loai_phong::findOrFail($request->id);
        $weight = Phong::where('loai_phong_id', $loai_phong->id)->whereDoesntHave('datPhongs', function ($query) {
            // $query->where('thoi_gian_den', '<', Carbon::now())->where('thoi_gian_di', '>', Carbon::now());
        })->count();

        $phongs = $request->input('phong');
        shuffle($phongs);
        // dd($phongs);
        // $ten_phong = collect($phongs)->pluck('ten_phong')->toArray();
        // dd($ten_phong);

        $so_luong = $request->so_luong;

        $random_rooms = array_slice($phongs, 0, $so_luong);
        // dd($random_rooms);

        $cartData = [];
        $cartData['id'] = $loai_phong->id;
        $cartData['name'] = $loai_phong->ten;
        $cartData['price'] = $loai_phong->gia;
        $cartData['qty'] = $request->so_luong;
        $cartData['weight'] = $weight;
        $cartData['ngay_bat_dau'] = $request->ngayBatDau;
        $cartData['ngay_ket_thuc'] = $request->ngayKetThuc;
        $cartData['so_luong_nguoi'] = $request->so_luong_nguoi;
        $cartData['phong'] = $random_rooms;
        $cartData['options']['image'] = $loai_phong->anh;
    
        // dd($cartData);
        // Kiểm tra nếu số lượng phòng trống đủ để thêm vào giỏ hàng
        if ($cartData['qty'] <= $weight) {
            Cart::add($cartData);
            return redirect()->route('client.pages.loai_phong.chitietloaiphong', ['id' => $loai_phong->id])
            ->with(['status' => 'success', 'message' => 'Thêm vào giỏ hàng thành công']);        
        } else {
            return redirect()->route('kiem_tra_phong', ['loai_phong_id' => $loai_phong->id])
                            ->with(['status' => 'error', 'message' => 'Không đủ phòng trống để thêm vào giỏ hàng']);
        }
    }

    

   
}

<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\KhuyenMai;
use App\Models\Loai_phong;
use App\Models\Phong;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
// use Illuminate\Support\Carbon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

class CartController extends Controller
{

    public function addToCart(Request $request)
    {
        $loai_phong = Loai_phong::findOrFail($request->id);
        $weight = Phong::where('loai_phong_id', $loai_phong->id)->whereDoesntHave('datPhongs', function ($query) {
            // $query->where('thoi_gian_den', '<', Carbon::now())->where('thoi_gian_di', '>', Carbon::now());
        })->count();

        $phongs = $request->input('phong');

        $countPhongTrong = count($phongs);
        // dd($countPhongTrong);

        if($countPhongTrong < $request->so_luong){
            return response(['status' => 'error','message' => 'Số lượng phòng không đủ']);
        }
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

        // Kiểm tra nếu số lượng phòng trống đủ để thêm vào giỏ hàng
        // if ($cartData['qty'] <= $weight) {
            Cart::add($cartData);
            return response(['status' => 'success', 'message' => 'Thêm vào giỏ hàng thành công']);
            // return Redirect::route('kiem_tra_phong')->with(['status' => 'success', 'message' => 'Thêm vào giỏ hàng thành công']);
        // } else {
            // return Redirect::route('kiem_tra_phong')->with(['status' => 'error', 'message' => 'Không đủ phòng trống để thêm vào giỏ hàng']);
        // }
    }






    public function cartDetail(Request $request)
    {
        $cartItems = Cart::content();
        // dd($cartItems);
        $total = $this->getCartTotal();

        // $ngayBatDau = $request->session()->get('ngayBatDau');
        // $ngayKetThuc = $request->session()->get('ngayKetThuc');

        $ngayBatDau = $cartItems->min('ngay_bat_dau');
        // dd($ngayBatDau);
        $ngayKetThuc = $cartItems->max('ngay_ket_thuc');
        // dd($ngayKetThuc);
        $soNgay = Carbon::parse($ngayKetThuc)->diffInDays(Carbon::parse($ngayBatDau));

        $soNgays = $soNgay + 1;
        // dd($soNgays);

        // $ngayBatDau = $cartItems->min(function ($item) {
        //     return Carbon\Carbon::parse($item->ngay_bat_dau);
        // });

        // $ngayKetThuc = $cartItems->max(function ($item) {
        //     return Carbon\Carbon::parse($item->ngay_ket_thuc);
        // });

        // if ($ngayBatDau && $ngayKetThuc) {
        //     $soNgay = $ngayKetThuc->diffInDays($ngayBatDau);
        // } else {
        //     $soNgay = 0;
        // }


        return view('client.pages.cart-detail', compact('total', 'cartItems','ngayBatDau','ngayKetThuc','soNgays'));
    }

    public function updateRoomQuantity(Request $request)
    {
        $id = Cart::get($request->rowId)->id;
        $loai_phong = Loai_phong::findOrFail($id);
        if ($loai_phong->qty < $request->so_luong) {
            return response(['status' => 'error', 'message' => 'Quá số lượng phòng không đủ']);
        }
        Cart::update($request->rowId, $request->so_luong);
        $phongTotal = $this->getRoomTotal($request->rowId);
        $total = $this->getCartTotal();
        return response([
            'status' => 'success',
            'message' => 'Số lượng phòng được cập nhật',
            'phong_total' => $phongTotal,
            'total' => $total
        ]);
    }

    public function removeRoom($rowId)
    {
        Cart::remove($rowId);
        return redirect()->back();
    }

    public function clearCart()
    {
        Cart::destroy();
        return response(['status' => 'success', 'message' => 'Cart cleared successfully']);
    }


    public function getRoomTotal($rowId)
    {
        $loai_phong = Cart::get($rowId);    
        $total = $loai_phong->price * $loai_phong->qty;
        return $total;
    }

    public function getCartCount()
    {
        return Cart::content()->count();
    }

    function getCartTotal()
    {
        $total = 0;
        foreach (Cart::content() as $loai_phong) {
            $total += $loai_phong->price * $loai_phong->qty;
        }
        return $total;
    }

    public function applyCoupon(Request $request){
        dd(123);
        dd($request->all());
        if($request -> coupon_code == null){
            return response([
                'status' => 'error',
                'message' => 'Khuyến mãi bắt buộc'
            ]);
        }

        $coupon  = KhuyenMai::where(['ma_giam_gia' => $request->coupon_code, 'trang_thai' => 1])->first();
        if($coupon == null){
            return response([
                'status' => 'error',
                'message' => 'Khuyến mãi không tồn tại',
            ]);
        }else if($coupon->ngay_bat_dau > Date('Y-m-d')){
            return response([
                'status' => 'error',
                'message'=> 'Khuyến mãi đã hết thời hạn',
            ]);
        }else if($coupon->ngay_ket_thuc < Date('Y-m-d')){
            return response([
                'status' => 'error',
                'message' => 'Khuyến mãi chưa đến ngày'
            ]);
        }

        if($coupon->loai_giam_gia == 1){
            Session::put('coupon',[
                'ten_khuyen_mai' => $coupon->ten_khuyen_mai,
                'ma_giam_gia' => $coupon->ma_giam_gia,
                'loai_giam_gia' => 1,
                'gia_tri_giam' => $coupon->gia_tri_giam,
            ]);
        }else if($coupon->loai_giam_gia == 2){
            Session::put('coupon',[
                'ten_khuyen_mai' => $coupon->ten_khuyen_mai,
                'ma_giam_gia' => $coupon->ma_giam_gia,
                'loai_giam_gia' => 1,
                'gia_tri_giam' => $coupon->gia_tri_giam,
            ]);
        }

        return response([
            'status' => 'success',
            'message' => 'Đã áp dụng khuyến mãi',
        ]);
    }

    public function couponCalc(){
        if(Session::has('coupon')){
            $khuyen_mai = Session::get('coupon');

            if($khuyen_mai['loai_giam_gia'] == 1){
                $total = (double)$this->getCartTotal() - (double)$khuyen_mai['gia_tri_giam'];
                return response([
                    'status' => 'success',
                    'cart_total' => (double)$total,
                    'discount' => $khuyen_mai['gia_tri_giam'],
                ]);
            }else if($khuyen_mai['loai_khuyen_mai'] == 2){
                $khuyen_mai = (double)$this->getCartCount() * (double)$khuyen_mai['gia_tri_giam'] / 100;
                $total = (double)$this->getCartCount() - (double)$khuyen_mai;
                return response([
                    'status' => 'success',
                    'cart_total' => (double)$total,
                    'gia_tri_giam' => $khuyen_mai,
                ]);
            }else{
                return 0;
            }
        }
    }
}

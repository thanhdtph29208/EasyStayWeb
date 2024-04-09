<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Loai_phong;
use App\Models\Phong;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Carbon;
// use Carbon;


class CartController extends Controller
{

    public function addToCart(Request $request)
    {

        $loai_phong = Loai_phong::findOrFail($request->id);
        $weight = Phong::where('loai_phong_id', $loai_phong->id)->whereDoesntHave('datPhongs', function ($query) {
            // $query->where('thoi_gian_den', '<', Carbon::now())->where('thoi_gian_di', '>', Carbon::now());
        })->count();
    
        $cartData = [];
        $cartData['id'] = $loai_phong->id;
        $cartData['name'] = $loai_phong->ten;
        $cartData['price'] = $loai_phong->gia;
        $cartData['qty'] = 1;
        $cartData['weight'] = $weight;
        $cartData['ngay_bat_dau'] = $request->ngayBatDau;
        $cartData['ngay_ket_thuc'] = $request->ngayKetThuc;
        $cartData['options']['image'] = $loai_phong->anh;
    
        // Kiểm tra nếu số lượng phòng trống đủ để thêm vào giỏ hàng
        if ($cartData['qty'] <= $weight) {
            Cart::add($cartData);
            return Redirect::route('kiem_tra_phong')->with(['status' => 'success', 'message' => 'Thêm vào giỏ hàng thành công']);
        } else {
            return Redirect::route('kiem_tra_phong')->with(['status' => 'error', 'message' => 'Không đủ phòng trống để thêm vào giỏ hàng']);
        }
    }

    public function cartDetail(Request $request)
    {
        $cartItems = Cart::content();
        $total = $this->getCartTotal();
    
        $ngayBatDau = $request->session()->get('ngayBatDau');
        $ngayKetThuc = $request->session()->get('ngayKetThuc');

        $soNgay = Carbon::parse($ngayKetThuc)->diffInDays(Carbon::parse($ngayBatDau));

        return view('client.pages.cart-detail', compact('total', 'cartItems','ngayBatDau','ngayKetThuc','soNgay'));
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
}

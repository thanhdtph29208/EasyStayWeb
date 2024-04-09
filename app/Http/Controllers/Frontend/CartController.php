<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Loai_phong;
use App\Models\Phong;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class CartController extends Controller
{

    public function addToCart(Request $request)
    {

        $loai_phong = Loai_phong::findOrFail($request->id);
        $so_luong = Phong::where('loai_phong_id', $loai_phong->id)->count();

        $cartData = [];
        $cartData['id'] = $loai_phong->id;
        $cartData['name'] = $loai_phong->ten;
        $cartData['price'] = $loai_phong->gia;
        $cartData['qty'] = $so_luong;
        $cartData['weight'] = 10;
        // $cartData['image'] = $loai_phong->anh;
        $cartData['options']['image'] = $loai_phong->anh;
        // $cartData['gia_ban_dau'] = $loai_phong->gia_ban_dau;
        // $cartData['gioi_han_nguoi'] = $loai_phong->gioi_han_nguoi;
        Cart::add($cartData);

        return Redirect::route('kiem_tra_phong')->with(['status' => 'success', 'message' => 'Thêm vào giỏ hàng thành công']);
    }

    public function cartDetail(Request $request)
    {
        $cartItems = Cart::content();
        // dd($cartItems->toArray());
        $total = $this->getCartTotal();
        // dd($total);
        return view('client.pages.cart-detail', compact('total', 'cartItems'));
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

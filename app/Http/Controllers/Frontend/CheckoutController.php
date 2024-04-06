<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Loai_phong;
use App\Models\Phong;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller{

    public function index(Request $request){
        $cartItems = Cart::content();
        $cartTotal = $request['cart_total'];
        return view('client.pages.checkout', compact('cartItems','cartTotal'));
    }
}

?>
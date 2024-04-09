<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gloudemans\Shoppingcart\Facades\Cart;


class ClearCartOnExit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // return $next($request);
        $response = $next($request);

        // Kiểm tra nếu người dùng đang ở trang thanh toán và đã thoát ra
        if ($request->is('checkout') && !$request->isMethod('post')) {
            // Xóa giỏ hàng
            Cart::destroy();
        }

        return $response;
    }

  
}

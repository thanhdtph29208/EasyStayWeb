<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ChiTietDatPhong;
use App\Models\DatPhong;
use App\Models\DatPhongNoiPhong;
use App\Models\KhuyenMai;
use App\Models\Loai_phong;
use App\Models\Phong;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Carbon;
use function Laravel\Prompts\alert;

class CheckoutController extends Controller
{

    public function index(Request $request)
    {
        $cartItems = Cart::content();
        // dd($cartItems);
        // $ngayBatDau = $cartItems->ngay_bat_dau();
        // $ngayKetThuc = $cartItems->ngay_ket_thuc();
        // $soNgay = Carbon\Carbon::parse($ngayKetThuc)->diffInDays(Carbon\Carbon::parse($ngayBatDau));
        $cartTotal = $request['cart_total'];
        return view('client.pages.checkout', compact('cartItems', 'cartTotal'));
    }

    public function pay(Request $request){
        $request['cart_total'] = (float)$request->cart_total;
        // var_dump($request->cart_total);
        $request->validate([
            'order_sdt' => ['required', 'min:9'],
        ]);

        if($request['payment'] == 1){
            return redirect()->route('vnpay_payment',[$request]);

            // return $this->checkoutSuccess1($request);
        }
        // return $this->checkoutSuccess($request);
    }

    public function vnpay_payment(Request $request)
    {
        // error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://easystayweb.test/";
        $vnp_TmnCode = "AWNZRJM5"; //Mã website tại VNPAY 
        $vnp_HashSecret = "RPKSHDUMKYBXLNABFXEZSBHTYUWBWPNS"; //Chuỗi bí mật
        $vnp_TxnRef = rand(00, 9999); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này 
        $vnp_OrderInfo = 'Thanh toán đơn hàng';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = (float)$request['cart_total'] * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
        //Add Params of 2.0.1 Version


        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef

        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        // if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
        //     $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        // }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00', 'message' => 'success', 'data' => $vnp_Url
        );
        if (isset($request['redirect'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);  
        }
    }
    public function checkoutSuccess($request){
        $this->bookOnline($request);
        $newBook = DatPhong::orderBy('created_at','desc')->first();
        Cart::destroy();

        // return view('', compact('newBook'));
    }

    public function bookOnline(Request $request){
        // $datPhong = DatPhong::create([
        //    'invoice' => $request['vnp_TxnRef'] ? $request['vnp_TxnRef'] :  uniqid(),
        //    'user_id' => Auth::user()->id,
        //    'trang_thai' => 1,
        //    'don_gia' => $request->don_gia,
        //    'thoi_gian_den' => $request->session('thoi_gian_den'),
        //    'thoi_gian_di' => $request->session('thoi_gian_di'),
        //    'tong_tien' => $request['vnp_Amount'] ? ($request['vnp_Amount'] / 100) : $request->cart_total,
        //    'payment' => $request['vnp_BankCode'] ? $request['vnp_BankCode'] : 'Momo',
    
        // ]);

        // $datPhongId = $datPhong->id();

        // //Thêm dữ liệu vào bảng đặt phòng
        // foreach ($request->loai_phong_ids as $key => $loaiPhongId){
        //     // Lấy số lượng phòng từ mảng so_luong_phong theo chỉ số tương ứng
        //     $soLuongPhong = $request->so_luong_phong[$key]['so_luong_phong'];
        //     // Thêm dữ liệu vào bảng liên kết
        //     $datPhong->loaiPhongs()->attach($loaiPhongId['id'], ['so_luong_phong' => $soLuongPhong]);
        // }

        // $phongIds = collect();
        // $tong_tien = 0;
        // foreach ($request->loai_phong_ids as $key => $loaiPhongId){
        //     $phongIdsForLoaiPhong = DB::table('phongs as p')
        //     ->leftJoin('dat_phong_noi_phongs as dp', 'p.id', '=', 'dp.phong_id')
        //     ->leftJoin('dat_phongs as d', 'dp.dat_phong_id', '=', 'd.id')
        //     // ->leftJoin('dat_phong_loai_phongs as dplp', 'd.id', '=', 'dplp.dat_phong_id')
        //     ->Where('p.loai_phong_id', $loaiPhongId)
        //     ->whereNull('dp.phong_id')
        //     ->orWhere(function($query) use ($datPhong) {
        //         $query->whereNotNull('dp.phong_id')
        //             ->where('d.thoi_gian_di', '<=', $datPhong->thoi_gian_den);
    
        //     })
        
        //     ->limit($request->so_luong_phong[$key]['so_luong_phong'])
        //     ->pluck('p.id');
        //     $phongIds = $phongIds->merge($phongIdsForLoaiPhong);
    
        //     $loaiPhong = Loai_phong::find($loaiPhongId['id']);
        //     $khuyenMai = null;
    

        $datPhong = new DatPhong();
        $datPhong->invoice = $request['vnp_TxnRef'] ? $request['vnp_TxnRef'] :  uniqid();
        $datPhong->user_id = Auth::user()->id;
        $datPhong->trang_thai = 1;
        $datPhong->don_gia = $request->don_gia;
        $datPhong->thoi_gian_den = $request->session('thoi_gian_den');
        $datPhong->thoi_gian_di = $request->session('thoi_gian_di');
        $datPhong->tong_tien = $request['vnp_Amount'] ? ($request['vnp_Amount'] / 100) : $request->cart_total;
        $datPhong->payment = $request['vnp_BankCode'] ? $request['vnp_BankCode'] : 'Momo';

        if ($request['vnp_OrderInfo']) {
            $separate = explode('~', $request['vnp_OrderInfo']);
            $datPhong->order_sdt = $separate[0];
        } else {
            $datPhong->order_sdt = $request->telephone;
        }

        $datPhong->save();
        
        dd($datPhong);

        foreach(Cart::content() as $loaiphong){
            $loaiPhongDat = new DatPhongNoiPhong(); 
            $loaiPhongDat->dat_phong_id = $datPhong->id;
            $loaiPhongDat->so_luong_phong = $datPhong->so_luong_phong;
        }


        foreach(Cart::content() as $phong){
            $phong = new DatPhong();
            
        }

        // $loaiPhong = Loai_phong::find($loaiPhongId['id']);
        // $khuyenMai = KhuyenMai::find($request->khuyen_mai_id);





    

    }
   
}

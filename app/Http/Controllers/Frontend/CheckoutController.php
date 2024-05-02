<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ChiTietDatPhong;
use App\Models\DatPhong;
use App\Models\DatPhongLoaiPhong;
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
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Frontend\SendMailController;

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
        $totalQty = 0;
        foreach ($cartItems as $item) {
            $totalQty += $item->qty;
        }
        $so_luong_nguoi = 0;
        foreach ($cartItems as $item) {
            $so_luong_nguoi += $item->so_luong_nguoi;
        }

        // dd($totalQty);
        return view('client.pages.checkout', compact('cartItems', 'cartTotal', 'totalQty', 'so_luong_nguoi'));
    }

    public function pay(Request $request)
    {
        $request['cart_total'] = (float)$request->cart_total;
        // var_dump($request->cart_total);
        $request->validate([
            'ho_ten' => ['required'],
            'so_dien_thoai' => ['required', 'min:10'],
            'email' => ['required', 'email'],
        ]);
        // $ho_ten = $request->input('ho_ten');
        // $so_dien_thoai = $request->input('so_dien_thoai');
        // $email = $request->input('email');

        if ($request['payment'] == 1) {

            $vnpayRequest = [
                'cart_total' => (float)$request->cart_total,
                // 'ho_ten' => $ho_ten,
                // 'so_dien_thoai' => $so_dien_thoai,
                // 'email' => $email,

            ];
            return redirect()->route('vnpay_payment', [$request])->with($vnpayRequest);
            // return $this->checkoutSuccess1($request);
        }
        if ($request['payment'] == 2) {
            return redirect()->route('momo_payment', [$request]);
        }

        // return $this->checkoutSuccess($request);
    }

    public function checkoutSuccess()
    {

        // $this->bookOnline($request);
        // $newOrder = DatPhong::orderBy('created_at', 'desc')->first();
        Cart::destroy();

        return view('client.pages.thanhcong');
    }

    public function checkoutSuccess1($request)
    {
        $this->bookOnline($request);
        $newOrder = DatPhong::orderBy('created_at', 'desc')->first();
        Cart::destroy();

        return view('client.pages.thanhcong', compact($newOrder));
    }


    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    public function momo_payment(Request $request)
    {

        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
        $orderInfo = $request->ho_ten . "~" . $request->so_dien_thoai . "~" . $request->email;
        $amount = (float)$request['cart_total'];
        $orderId = time() . "";
        $redirectUrl = "http://easystayweb.test/momo_callback";
        $ipnUrl = "http://easystayweb.test/momo_callback";
        $extraData = "";
        $requestId = time() . "";
        $requestType = "payWithATM";

        //before sign HMAC SHA256 signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = array(
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature
        );
        $result = $this->execPostRequest($endpoint, json_encode($data));

        $jsonResult = json_decode($result, true);  // decode json

        // if($jsonResult = json_decode($result, true)){
        //     // return view('admin.layouts.master');
        //     return redirect()->to($jsonResult['payUrl']);
        // }

        return redirect()->to($jsonResult['payUrl']);
    }



    // xây dựng hàm thanh toán bằng vnpay

    public function vnpay_payment(Request $request)
    {
        // error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = url('/vnpay_callback');
        $vnp_TmnCode = "AWNZRJM5"; //Mã website tại VNPAY
        $vnp_HashSecret = "RPKSHDUMKYBXLNABFXEZSBHTYUWBWPNS"; //Chuỗi bí mật
        $vnp_TxnRef = rand(00, 9999); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này
        $vnp_OrderInfo = $request->ho_ten . "~" . $request->so_dien_thoai . "~" . $request->email;
        // $vnp_OrderType = $request->address;
        // $vnp_OrderInfo = 'Thanh toán đơn hàng';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = (float)$request['cart_total'] * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_Pay = 'VNPay';
        // $vnp_BankCode = $request['bank_code'];
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

    public function vnpayCallBack(Request $request)
    {
        if ($request->get('vnp_ResponseCode') == '00') {
            $this->bookOnline($request);
            Cart::destroy();
            return redirect()->route('sendMail');
            

        }
    }

    public function momoCallBack(Request $request){
        // $isPaymentValid = $this->verifyMoMoPayment($request);
        // if($isPaymentValid){
            $this->bookOnline($request);
            Cart::destroy();
            return redirect()->route('sendMail');
        // }
    }

    public function bookOnline(Request $request)
    {
        $cartItems = Cart::content();


        $ngayBatDau = $cartItems->min('ngay_bat_dau');
        $ngayKetThuc = $cartItems->max('ngay_ket_thuc');
        $soLuongPhong = $cartItems->sum('qty');
        // dd($soLuongPhong);
        $soLuongNguoi = $cartItems->sum('so_luong_nguoi');

        $datPhong = new DatPhong();
        $datPhong->invoice = $request['vnp_TxnRef'] ? $request['vnp_TxnRef'] :  uniqid();
        $datPhong->user_id = Auth::user()->id;
        $datPhong->trang_thai = 1;
        $datPhong->thoi_gian_den = $ngayBatDau;
        $datPhong->thoi_gian_di = $ngayKetThuc;
        // $cartTotal = str_replace([' ', ',', 'VNĐ'], '', $request->cart_total);
        // $datPhong->tong_tien = (float) $cartTotal;
        $datPhong->tong_tien = $request['vnp_Amount'] ? ($request['vnp_Amount'] / 100) : ($request['amount'] ? $request['amount'] : 0);

        // $datPhong->payment = $request['vnp_BankCode'] ? $request['vnp_BankCode'] : 'Momo';
        $datPhong->payment = $request['vnp_BankCode'] ? 'VNPay' : 'Momo';


        // $datPhong->so_dien_thoai = $request->so_dien_thoai;
        // $datPhong->ho_ten = $request->ho_ten;
        // $datPhong->email = $request->email;

        $datPhong->so_luong_phong = $soLuongPhong;
        $datPhong->so_luong_nguoi = $soLuongNguoi;

        if ($request['vnp_OrderInfo']) {
            $separate = explode('~', $request['vnp_OrderInfo']);
            $datPhong->ho_ten = $separate[0];
            $datPhong->so_dien_thoai = $separate[1];
            $datPhong->email = $separate[2];
        }


        if ($request['orderInfo']) {
            $separate = explode('~', $request['orderInfo']);
            $datPhong->ho_ten = $separate[0];
            $datPhong->so_dien_thoai = $separate[1];
            $datPhong->email = $separate[2];
        }


        // dd($datPhong->so_dien_thoai);

        $datPhong->save();

        if($request['vnp_Amount']){
            $thanhtien = $request['vnp_Amount'] ? ($request['vnp_Amount'] / 100) : $request->cart_total;
        }
        if($request['amount']){
            $thanhtien = $request['amount'] ? ($request['amount'] ) : $request->cart_total;
        }



        foreach (Cart::content() as $item) {
            $loaiPhongDat = new DatPhongLoaiPhong();
            $loaiPhongDat->dat_phong_id = $datPhong->id;
            $loaiPhongDat->loai_phong_id = $item->id;
            // $loaiPhongDat->don_gia = $loaiPhong->price;
            $loaiPhongDat->so_luong_phong = $item->qty;
            $loaiPhongDat->save();

            $phongs = $item->phong;
            // dd($phongs);

            $phongAll = [];

            foreach ($phongs as $phong_id) {
                $phongAll[] = [
                    'dat_phong_id' => $datPhong->id,
                    'phong_id' => $phong_id
                ];
            }
            DatPhongNoiPhong::insert($phongAll);


            $chiTietDatPhong = new ChiTietDatPhong();
            $chiTietDatPhong->dat_phong_id = $datPhong->id;
            $chiTietDatPhong->thanh_tien = $thanhtien;
            $chiTietDatPhong->save();

        }
        return back();
    }
}

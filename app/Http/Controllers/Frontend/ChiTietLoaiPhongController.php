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
use Brian2694\Toastr\Facades\Toastr;
use PHPUnit\Event\TestSuite\Loaded;
use Illuminate\Support\Facades\Session;

class ChiTietLoaiPhongController extends Controller
{
    public function detail(string $id)
    {
        $detail = Loai_phong::find($id);
        if (!$detail) {
            // Xử lý khi không tìm thấy loại phòng
            return response()->view('errors.404', [], 404); // Hoặc bất kỳ xử lý nào phù hợp
        }
        $loaiPhongIds = $detail->phongs->pluck('loai_phong_id')->unique();
        $khach_sans = Hotel::all();
        $danh_gias = DanhGia::where('loai_phong_id', $id)->get();
        $khach_hangs = User::all();
        $trang_thai = Loai_phong::all();

        return view('client.pages.loai_phong.chitietloaiphong', compact('detail', 'khach_sans', 'danh_gias', 'khach_hangs', 'trang_thai', 'loaiPhongIds'));
    }


public function checkLoaiPhong(Request $request, $id)
{
    // Lấy thông tin về loại phòng cụ thể
    $detail = Loai_phong::find($id);
    
    // Kiểm tra nếu không tìm thấy loại phòng
    if (!$detail) {
        return response()->json(['error' => 'Loại phòng không tồn tại'], 404);
    }

    // Xử lý thời gian đến và thời gian đi
    $ngayBatDau = Carbon::parse($request->input('thoi_gian_den'))->setTime(14, 0);
    $ngayKetThuc = Carbon::parse($request->input('thoi_gian_di'))->setTime(12, 0);

       // Lưu thời gian vào session
       $request->session()->put('ngay_bat_dau', $ngayBatDau);
       $request->session()->put('ngay_ket_thuc', $ngayKetThuc);
       
    // Lấy danh sách các phòng thuộc loại phòng cụ thể
    $phongs = Phong::where('loai_phong_id', $id)->get();
    
    // Lọc các phòng không có đặt phòng nào trong khoảng thời gian đã chỉ định
    $availableRooms = $phongs->filter(function ($phong) use ($ngayBatDau, $ngayKetThuc) {
        return !$phong->datPhongs()->where('thoi_gian_den', '<', $ngayKetThuc)
                                    ->where('thoi_gian_di', '>', $ngayBatDau)
                                    ->exists();
    });

    // Trả về dữ liệu dưới dạng JSON
    return response()->json(['availableRooms' => $availableRooms]);
}
    
        
    


    

    public function addCTLS(Request $request)
    {
        $loai_phong = Loai_phong::find($request->id);
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
        $cartData['ngay_bat_dau'] = 1;
        $cartData['ngay_ket_thuc'] = 2;
        // $cartData['ngay_bat_dau'] = $request->ngayBatDau;
        // $cartData['ngay_ket_thuc'] = $request->ngayKetThuc;
        // $cartData['so_luong_nguoi'] = $request->so_luong_nguoi;
        $cartData['so_luong_nguoi'] = 3;
        $cartData['phong'] = $random_rooms;
        $cartData['options']['image'] = $loai_phong->anh;

        // dd($cartData);
        // Kiểm tra nếu số lượng phòng trống đủ để thêm vào giỏ hàng
        if ($cartData['qty'] <= $weight) {
            Cart::add($cartData);
            Toastr::success('Thêm vào giỏ hàng thành công', 'Thành công');
            return redirect()->route('client.pages.loai_phong.chitietloaiphong', ['id' => $loai_phong->id]);
            // ->with(['status' => 'success', 'message' => 'Thêm vào giỏ hàng thành công']);        
        } else {
            return redirect()->route('kiem_tra_phong', ['loai_phong_id' => $loai_phong->id])
                ->with(['status' => 'error', 'message' => 'Không đủ phòng trống để thêm vào giỏ hàng']);
        }
    }

    public function allRoom()
    {
        $rooms = Loai_phong::all();
        return view('client.pages.loai_phong.loai_phong', compact('rooms'));
    }
    public function filter(Request $request)
    {
        $query = Loai_phong::query();
    
        // Kiểm tra và áp dụng điều kiện lọc
        if ($request->has('ten_phong')) {
            $query->where('ten', 'like', '%' . $request->ten_phong . '%');
        }
    
        if ($request->has('gia_min') && $request->has('gia_max')) {
            // Lọc theo khoảng giá
            $query->whereBetween('gia', [$request->gia_min, $request->gia_max]);
        }
    
        if ($request->has('trang_thai')) {
            // Thay đổi giá trị của trường trang_thai
            $trangThai = $request->trang_thai === '1' ? 1 : 0;
            $query->where('trang_thai', '=', $trangThai);
        }
    
        // Lấy danh sách kết quả sau khi lọc
        $rooms = $query->get();
    
   
       // Kiểm tra xem có dữ liệu được tìm thấy không
       if ($rooms->isEmpty()) {
        // Nếu không có dữ liệu, trả về một phản hồi JSON với thông báo lỗi
        return response()->json(['error' => 'Không tìm thấy kết quả phù hợp.'], 404);
    }
    
        // Nếu có dữ liệu, trả về view với danh sách các phòng đã lọc
        return response()->json(['rooms' => $rooms]);
    }
    













}

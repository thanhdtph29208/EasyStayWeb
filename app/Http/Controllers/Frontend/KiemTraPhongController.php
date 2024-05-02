<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ChiTietDatPhong;
use App\Models\Anh_phong;
use App\Models\Phong;
use App\Models\DatPhong;
use App\Models\Loai_phong;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Validation\Validator;
use Carbon\Carbon;
use Exception;

class KiemTraPhongController extends Controller
{

    public function checkPhong(Request $request)
{
    // Validation rules cho dữ liệu đầu vào
    $rules = [
        'thoi_gian_den' => 'required|date',
        'thoi_gian_di' => 'required|date|after:thoi_gian_den',
    ];

    // Các thông báo lỗi tương ứng với từng loại lỗi validation
    $messages = [
        'thoi_gian_den.required' => 'Thời gian đến không được để trống',
        'thoi_gian_den.date' => 'Thời gian đến không đúng định dạng',
        'thoi_gian_di.required' => 'Thời gian đi không được để trống',
        'thoi_gian_di.date' => 'Thời gian đi không đúng định dạng',
        'thoi_gian_di.after' => 'Thời gian đi phải lớn hơn thời gian đến',
    ];

    // Validation dữ liệu từ request dựa trên rules và messages đã định nghĩa
    $validated = $request->validate($rules, $messages);

    // Xử lý thời gian đến và thời gian đi
    $ngayBatDau = Carbon::parse($request->input('thoi_gian_den'))->setTime(14, 0);
    $ngayKetThuc = Carbon::parse($request->input('thoi_gian_di'))->setTime(12, 0);

    // Lưu thời gian vào session
    $request->session()->put('ngay_bat_dau', $ngayBatDau);
    $request->session()->put('ngay_ket_thuc', $ngayKetThuc);

    // Lấy danh sách tất cả loại phòng từ cơ sở dữ liệu
    $loaiPhongs = Loai_phong::all();

    // Lọc các loại phòng dựa trên sự sẵn có của phòng trong khoảng thời gian đã chỉ định
    $availableLoaiPhongs = $loaiPhongs->filter(function ($loaiPhong) use ($ngayBatDau, $ngayKetThuc) {
        $availableRooms = Phong::where('loai_phong_id', $loaiPhong->id)
            ->whereDoesntHave('datPhongs', function ($query) use ($ngayBatDau, $ngayKetThuc) {
                $query->where('thoi_gian_den', '<', $ngayKetThuc)
                    ->where('thoi_gian_di', '>', $ngayBatDau);
            })
            ->exists();
        return $availableRooms;
    });

    // Xử lý thông tin về các phòng và loại phòng
    $phongs = $availableLoaiPhongs->map(function ($loaiPhong) use ($ngayBatDau, $ngayKetThuc) {
        // Lấy các phòng có sẵn và tất cả các phòng trong loại phòng
        $availableRooms = Phong::where('loai_phong_id', $loaiPhong->id)
            ->whereDoesntHave('datPhongs', function ($query) use ($ngayBatDau, $ngayKetThuc) {
                $query->where('thoi_gian_den', '<', $ngayKetThuc)
                    ->where('thoi_gian_di', '>', $ngayBatDau);
            })
            ->get();
        $allRooms = Phong::where('loai_phong_id', $loaiPhong->id)->get();

        // Trả về thông tin về loại phòng, các phòng có sẵn và tất cả các phòng trong loại đó
        return [
            'loai_phong' => $loaiPhong,
            'available_rooms' => $availableRooms,
            'all_rooms' => $allRooms
        ];
    });

    // Trả về view 'client.pages.checkPhong' với thông tin về các loại phòng và các phòng có sẵn
    return view('client.pages.checkPhong', compact('availableLoaiPhongs', 'phongs', 'ngayBatDau', 'ngayKetThuc', 'loaiPhongs',));
}

   



    public function addToCart(Request $request)
    {
        // Kiểm tra xem người dùng đã đặt phòng trước đó chưa
        $daDatPhong = DatPhong::where('user_id', auth()->user()->id)
            ->where('thoi_gian_den', $request->input('thoi_gian_den'))
            ->where('thoi_gian_di', $request->input('thoi_gian_di'))
            ->exists();

        if ($daDatPhong) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn đã đặt phòng trong khoảng thời gian này.'
            ]);
        }

        // Nếu chưa đặt phòng, tiếp tục thêm vào giỏ hàng
        // Code thêm phòng vào giỏ hàng ở đây

        // Sau khi thêm vào giỏ hàng thành công
        // Đánh dấu rằng người dùng đã đặt phòng
        DatPhong::create([
            'user_id' => auth()->user()->id,
            'thoi_gian_den' => $request->input('thoi_gian_den'),
            'thoi_gian_di' => $request->input('thoi_gian_di'),
            // Thêm các trường dữ liệu khác cần thiết
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Thêm giỏ hàng thành công'
        ]);
    }
}
    // function checkPhong(Request $request)
    // {
    //     try {
    //         $ngayBatDau = Carbon::parse($request->input('ngay_bat_dau'))->setTime(14, 0);
    //         $ngayKetThuc = $ngayBatDau->copy()->addDay()->setTime(12, 0);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Lỗi định dạng ngày tháng.'
    //         ], 400);
    //     }

    //     $phongs = [];

    //     $currentTime = $ngayBatDau->copy();
    //     while ($currentTime->lessThanOrEqualTo($ngayKetThuc)) {
    //         $availableLoaiPhongs = Loai_phong::all()->filter(function ($loaiPhong) use ($currentTime) {
    //             $availableRooms = Phong::where('loai_phong_id', $loaiPhong->id)
    //                 ->whereDoesntHave('datPhongs', function ($query) use ($currentTime) {
    //                     $query->where('thoi_gian_den', '<=', $currentTime)
    //                         ->where('thoi_gian_di', '>=', $currentTime->copy()->addHour());
    //                 })
    //                 ->exists();

    //             return $availableRooms;
    //         });

    //         $phongs[$currentTime->format('Y-m-d H:i')] = $availableLoaiPhongs->map(function ($loaiPhong) use ($currentTime) {
    //             $availableRooms = Phong::where('loai_phong_id', $loaiPhong->id)
    //                 ->whereDoesntHave('datPhongs', function ($query) use ($currentTime) {
    //                     $query->where('thoi_gian_den', '<=', $currentTime)
    //                         ->where('thoi_gian_di', '>=', $currentTime->copy()->addHour());
    //                 })
    //                 ->get();

    //             return [
    //                 'loai_phong' => $loaiPhong,
    //                 'available_rooms' => $availableRooms,
    //                 'all_rooms' => Phong::where('loai_phong_id', $loaiPhong->id)->get() // Lấy tất cả phòng thuộc loại phòng
    //             ];
    //         });

    //         $currentTime->addHour();
    //     }
    //     //   dd($phongs);

    //     return view('client.pages.checkPhong', compact('availableLoaiPhongs', 'phongs', 'ngayBatDau', 'ngayKetThuc'));
    // }

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
use Carbon\Carbon;
use Exception;

class KiemTraPhongController extends Controller
{

    function checkPhong(Request $request)
{
    try {
        $ngayBatDau = Carbon::parse($request->input('thoi_gian_den'));
        $ngayKetThuc = Carbon::parse($request->input('thoi_gian_di'));
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Lỗi định dạng ngày tháng.'
        ], 400);
    }

    // Khởi tạo mảng để lưu trữ thông tin về trạng thái của phòng
    $phongs = [];

    // Lặp qua từng phút từ thời gian bắt đầu đến thời gian kết thúc
    $currentTime = $ngayBatDau->copy();
    while ($currentTime->lessThanOrEqualTo($ngayKetThuc)) {
        $availableLoaiPhongs = Loai_phong::all()->filter(function ($loaiPhong) use ($currentTime) {
            // Kiểm tra tính trống của phòng tại thời điểm hiện tại
            $availableRooms = Phong::where('loai_phong_id', $loaiPhong->id)
                ->whereDoesntHave('datPhong', function ($query) use ($currentTime) {
                    $query->where('thoi_gian_den', '<=', $currentTime)
                        ->where('thoi_gian_di', '>=', $currentTime->copy()->addMinute());
                })
                ->exists();
            return $availableRooms;
        });

        // Lưu thông tin về trạng thái của phòng vào mảng phongs
        $phongs[$currentTime->format('Y-m-d H:i')] = $availableLoaiPhongs->map(function ($loaiPhong) use ($currentTime) {
            // Lấy danh sách các phòng trống tại thời điểm hiện tại
            $availableRooms = Phong::where('loai_phong_id', $loaiPhong->id)
                ->whereDoesntHave('datPhong', function ($query) use ($currentTime) {
                    $query->where('thoi_gian_den', '<=', $currentTime)
                        ->where('thoi_gian_di', '>=', $currentTime->copy()->addMinute());
                })
                ->get();

            return [
                'loai_phong' => $loaiPhong,
                'available_rooms' => $availableRooms
            ];
        });

        // Di chuyển tới phút tiếp theo
        $currentTime->addMinute();
    }

    // Trả về thông tin về trạng thái của các phòng theo từng giờ phút
    return view('client.pages.checkPhong', compact('availableLoaiPhongs','phongs', 'ngayBatDau', 'ngayKetThuc'));
}

}




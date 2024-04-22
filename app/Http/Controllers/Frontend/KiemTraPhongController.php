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
        // try {
        // $ngayBatDau = Carbon::parse($request->input('thoi_gian_den'));
        // $ngayKetThuc = Carbon::parse($request->input('thoi_gian_di'));
        $ngayBatDau = Carbon::parse($request->input('thoi_gian_den'))->setTime(14, 0);
        $ngayKetThuc = Carbon::parse($request->input('thoi_gian_di'))->setTime(12, 0);

        $request->session()->put('ngay_bat_dau', $ngayBatDau);
        $request->session()->put('ngay_ket_thuc', $ngayKetThuc);

        // } catch (Exception $e) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Lỗi định dạng ngày tháng.'
        //     ], 400);
        // }

        $loaiPhongs = Loai_phong::all();

        $availableLoaiPhongs = $loaiPhongs->filter(function ($loaiPhong) use ($ngayBatDau, $ngayKetThuc) {

            $availableRooms = Phong::where('loai_phong_id', $loaiPhong->id)
                ->whereDoesntHave('datPhongs', function ($query) use ($ngayBatDau, $ngayKetThuc) {
                    $query->where('thoi_gian_den', '<', $ngayKetThuc)
                        ->where('thoi_gian_di', '>', $ngayBatDau);
                })
                ->exists();
            return $availableRooms;
        });

        $phongs = $availableLoaiPhongs->map(function ($loaiPhong) use ($ngayBatDau, $ngayKetThuc) {
            $availableRooms = Phong::where('loai_phong_id', $loaiPhong->id)
                ->whereDoesntHave('datPhongs', function ($query) use ($ngayBatDau, $ngayKetThuc) {
                    $query->where('thoi_gian_den', '<', $ngayKetThuc)
                        ->where('thoi_gian_di', '>', $ngayBatDau);
                })
                ->get();
            $allRooms = Phong::where('loai_phong_id', $loaiPhong->id)->get();

            return [
                'loai_phong' => $loaiPhong,
                'available_rooms' => $availableRooms,
                'all_rooms' => $allRooms
            ];
        });


        return view('client.pages.checkPhong', compact('availableLoaiPhongs', 'phongs', 'ngayBatDau', 'ngayKetThuc', 'loaiPhongs'));
    }

    public function checkLoaiPhong(Request $request)
    {
        $thoiGianDen = Carbon::parse($request->input('thoi_gian_den'))->setTime(14, 0);
        $thoiGianDi = Carbon::parse($request->input('thoi_gian_di'))->setTime(12, 0);
        $loaiPhongId = $request->input('loai_phong_id');
        // dd($loaiPhongId);

        $phongsTrong = Phong::where('loai_phong_id', $loaiPhongId)
            ->whereDoesntHave('datPhongs', function ($query) use ($thoiGianDen, $thoiGianDi) {
                $query->where(function ($subQuery) use ($thoiGianDen, $thoiGianDi) {
                    $subQuery->where('thoi_gian_den', '>=', $thoiGianDen)
                        ->where('thoi_gian_di', '<=', $thoiGianDi)
                        ->orWhere(function ($innerQuery) use ($thoiGianDen, $thoiGianDi) {
                            $innerQuery->where('thoi_gian_den', '<=', $thoiGianDen)
                                ->where('thoi_gian_di', '>=', $thoiGianDen);
                        })
                        ->orWhere(function ($innerQuery) use ($thoiGianDen, $thoiGianDi) {
                            $innerQuery->where('thoi_gian_den', '<=', $thoiGianDi)
                                ->where('thoi_gian_di', '>=', $thoiGianDi);
                        });
                });
            })
            ->get();
            // dd($phongsTrong);
            
       
        
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

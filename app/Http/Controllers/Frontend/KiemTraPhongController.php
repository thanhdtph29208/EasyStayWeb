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
            $ngayBatDau =  Carbon::parse($request->input('thoi_gian_den'));
            $ngayKetThuc = Carbon::parse($request->input('thoi_gian_di'));

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

        // dd($phongs);
        // return response()->json([
        //     'success' => true,
        //     'available_loai_phongs' => $phongs,
        //     'ngay_bat_dau' => $ngayBatDau,
        //     'ngay_ket_thuc' => $ngayKetThuc
        // ]);

        return view('client.pages.checkPhong', compact('availableLoaiPhongs', 'phongs', 'ngayBatDau', 'ngayKetThuc', 'loaiPhongs'));

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

}

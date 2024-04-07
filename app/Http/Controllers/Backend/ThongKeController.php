<?php

namespace App\Http\Controllers\Backend;
use App\Models\DatPhong;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;


class ThongKeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   

    public function index(Request $request)
    {
        $startDate = request('start_date');
  $endDate = request('end_date');

  $datPhongs = DatPhong::whereBetween('thoi_gian_den', [$startDate, $endDate])
    ->selectRaw('COUNT(*) AS total_bookings, DATE(thoi_gian_den) AS date')
    ->groupBy('date')
    ->get();
// $datPhongs = DatPhong::select('thoi_gian_den', DB::raw('count(*) as total_orders'))
// ->whereBetween('thoi_gian_den', [Carbon::now()->subDays(365), Carbon::now()])
// ->groupBy('thoi_gian_den')
// ->get();

// Chuẩn bị dữ liệu cho biểu đồ
$labels = [];
$data = [];
foreach ($datPhongs as $datPhong) {
$labels[] = $datPhong->date;
$data[] = $datPhong->total_bookings;
}



         
       
        return view('admin.thongke', compact('labels','data'));
        
    }
   


}


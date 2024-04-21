<?php

namespace App\Http\Controllers\Backend;
use App\Models\DatPhong;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Phong;
use DB;



class ThongKeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   

    public function index(Request $request)
    {
   

  $locNam=Carbon::parse($request->loc_doanh_thu)->year;
  
  $thang=Carbon::parse($request->loc_doanh_thu)->month;
  $today=Carbon::parse($request->loc_doanh_thu)->day;


  $nam = DatPhong::selectRaw('YEAR(created_at) as nam, SUM(tong_tien) as doanh_thu')
  ->whereYear('created_at', $locNam)
  ->groupBy('nam')
  ->get();

  $doanh_thu_thang_nay = DatPhong::selectRaw('YEAR(created_at) as nam, MONTH(created_at) as thang, SUM(tong_tien) as doanh_thu')
    ->whereBetween('created_at', [Carbon::parse($request->loc_doanh_thu)->startOfMonth(), Carbon::parse($request->loc_doanh_thu)->endOfMonth()])
    ->groupBy(['nam', 'thang'])
    ->get();

    $doanh_thu_tuan_nay = DatPhong::selectRaw('YEAR(created_at) as nam, WEEK(created_at) as tuan, SUM(tong_tien) as doanh_thu')
    ->whereBetween('created_at', [Carbon::parse($request->loc_doanh_thu)->startOfWeek(), Carbon::parse($request->loc_doanh_thu)->endOfWeek()])
    ->groupBy(['nam', 'tuan'])
    ->get();

    $doanh_thu_hom_nay = DatPhong::selectRaw('SUM(tong_tien) as doanh_thu')
    ->whereDate('created_at', Carbon::parse($request->loc_doanh_thu))
    ->get();

  $datPhongs = DatPhong::whereBetween('thoi_gian_den',[Carbon::parse($request->start_date)->startOfDay(), Carbon::parse($request->end_date)->endOfDay()])
    ->selectRaw('COUNT(*) AS total_bookings, DATE(thoi_gian_den) AS date')
    ->groupBy('date')
    ->get();

  
    $tong_so_phong_trong = Phong::select(DB::raw('COUNT(*) as tong_so_phong_trong'))
    ->whereNotIn('id', function ($query) {
        $query->select('phong_id')
            ->from('Dat_phongs')
            ->where('thoi_gian_di', '>=', DB::raw('CURDATE()'));
    })
    ->get()
    ->first()
    ->tong_so_phong_trong;
  

    $tong_so_phong = Phong::select(DB::raw('COUNT(*) as tong_so_phong'))
    ->get()
    ->first()
    ->tong_so_phong;

    $tong_so_phong_da_dat=$tong_so_phong-$tong_so_phong_trong;
 
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



         
       
        return view('admin.thongke', compact('labels','data','nam','tong_so_phong_da_dat','tong_so_phong','doanh_thu_thang_nay','doanh_thu_tuan_nay','doanh_thu_hom_nay','locNam','thang','today'));
        
    }
   


}


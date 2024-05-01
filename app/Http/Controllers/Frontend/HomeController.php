<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Bai_viet;
use App\Models\Banner;
use App\Models\Hotel;
use App\Models\Loai_phong;
use Illuminate\Http\Request;


class HomeController extends Controller{
    public function home(){
        $loai_phongs = Loai_phong::take(6)->get();
        $khach_sans = Hotel::all();
        $banners = Banner::where('trang_thai',1)->get();
        $bai_viets = Bai_viet::where('trang_thai', 1)
        ->orderBy('created_at', 'desc')
        ->take(3)
        ->get();

        return view('client.pages.home',compact('loai_phongs','khach_sans','banners','bai_viets'));
    }
}
?>
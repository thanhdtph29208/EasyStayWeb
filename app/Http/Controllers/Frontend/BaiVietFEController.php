<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Bai_viet;
use App\Models\Hotel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\comments;
class BaiVietFEController extends Controller
{
    public function index(){
        $tintuc = Bai_viet::where('trang_thai',1)->get();
        return view('client.pages.bai_viet.index', compact('tintuc'));
    }

    public function show(string $id){
        $detail = Bai_viet::where('trang_thai',1)->where('id',$id)->first();
        $khach_sans = Hotel::all();
        $bai_viets = Bai_viet::all();
        $comments = comments::with(['user','bai_viet'])->where('bai_viet_id',$id)->get();
        return view('client.pages.bai_viet.show', compact('detail','khach_sans','bai_viets','comments','id'));
    }

    public function comment(Request $request){
        $request->validate([
            "content" => 'required'
        ],[
            'content.required' => "Không để trống nội dung comment"
        ]);

        comments::create([
            "user_id" => $request->id_user,
            "bai_viet_id" => $request->id_post,
            "content" => $request->input('content'),
            "created_at" => Carbon::now()
        ]);
        return redirect()->back();
    }
}

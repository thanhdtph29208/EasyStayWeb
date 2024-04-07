<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\DatPhongDataTable;
use App\DataTables\ChiTietDatPhongDataTable;
use App\Http\Controllers\Controller;
use App\Models\DatPhong;
use Illuminate\Http\Request;
use App\Models\DichVu;
use App\Models\User;
use App\Models\Loai_phong;
use App\Models\Phong;
use App\Models\KhuyenMai;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use App\Models\ChiTietDatPhong;
use App\Models\DatPhongLoaiPhong;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use function Laravel\Prompts\alert;
use function Livewire\store;

class DatPhongController extends Controller
{
    const PATH_VIEW = 'admin.dat_phong.';


    public function index(Request $request, DatPhongDataTable $datatables )
    {
        // $datphong = DatPhong::query()->latest()->paginate(7);
        return $datatables->render(self::PATH_VIEW . __FUNCTION__);
    }
    public function create(DatPhong $datPhong)
    {
        // $loai_phongs = Loai_phong::where('trang_thai',1)->with('phongs')->get();
        // $loai_phongs = Loai_phong::where('trang_thai',1)->get();

        // $phongs = Phong::where('trang_thai',1)->get();
        // $loai_phongs = $request->input('loai_phongs', []);
        // $loai_phongs = Phong::whereIn('loai_phong_id', $loai_phongs)
        //             ->where('trang_thai', 1)
        //             ->get();
        // return response()->json(['loai_phongs' => $loai_phongs]);
        // return view(self::PATH_VIEW . __FUNCTION__, ['loai_phongs'=>$loai_phongs]);
        $i=0;
        $so_luong_loai_phong = Loai_phong::count();
        $user = User::query()->pluck('email','id')->toArray();
        $loai_phong = Loai_phong::query()->pluck('ten','id')->toArray();
        $phong = Phong::query()->pluck('ten_phong','id')->toArray();
        $khuyen_mai = KhuyenMai::query()->pluck('ten_khuyen_mai','id')->toArray();
        return view(self::PATH_VIEW . __FUNCTION__,compact('user','datPhong','loai_phong','phong','khuyen_mai','so_luong_loai_phong','i'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request , User $user, Loai_phong $loaiPhong, KhuyenMai $khuyenMai): RedirectResponse
    {
        if (! Gate::allows('create-A&NV', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }
        dd($request);
        // $request->validate([
        //     'loai_phong_ids' => 'required|array',
        //     'loai_phong_ids.*.id' => 'required|numeric',
        //     'loai_phong_ids.*.so_luong_phong' => 'required|numeric|min:0',
        //     'ghi_chu' => 'nullable|string',
        // ]);


        $datPhong=DatPhong::create([
            'user_id'=> $request->user_id,
            'so_luong_nguoi'=>$request->so_luong_nguoi,
            'thoi_gian_den'=>$request->thoi_gian_den,
            'thoi_gian_di'=>$request->thoi_gian_di,
            'khuyen_mai_id'=>$request->khuyen_mai_id,
            'payment'=>$request->payment,
            'trang_thai'=> 1,
            'ghi_chu'=>$request->ghi_chu,
        ]);
        $datPhongId = $datPhong->id;
        // Thêm dữ liệu vào bảng DatPhongLoaiPhong
        foreach ($request->loai_phong_ids as $key => $loaiPhongId) {
            // Lấy số lượng phòng từ mảng so_luong_phong theo chỉ số tương ứng
            $soLuongPhong = $request->so_luong_phong[$key]['so_luong_phong'];

            // Thêm dữ liệu vào bảng liên kết
            $datPhong->loaiPhongs()->attach($loaiPhongId['id'], ['so_luong_phong' => $soLuongPhong]);
        }

        $phongIds = collect();
        $tong_tien=0;
        foreach ($request->loai_phong_ids as $key => $loaiPhongId){
        $phongIdsForLoaiPhong = DB::table('phongs as p')
        ->leftJoin('dat_phong_noi_phongs as dp', 'p.id', '=', 'dp.phong_id')
        ->leftJoin('dat_phongs as d', 'dp.dat_phong_id', '=', 'd.id')
        // ->leftJoin('dat_phong_loai_phongs as dplp', 'd.id', '=', 'dplp.dat_phong_id')
        ->Where('p.loai_phong_id', $loaiPhongId)
        ->whereNull('dp.phong_id')
        ->orWhere(function($query) use ($datPhong) {
            $query->whereNotNull('dp.phong_id')
                ->where('d.thoi_gian_di', '<=', $datPhong->thoi_gian_den);

        })

        ->limit($request->so_luong_phong[$key]['so_luong_phong'])
        ->pluck('p.id');
        $phongIds = $phongIds->merge($phongIdsForLoaiPhong);

        $loaiPhong = Loai_phong::find($loaiPhongId['id']);
        $khuyenMai = KhuyenMai::find($request->khuyen_mai_id);

        $ngay_bat_dau = strtotime($request->thoi_gian_den);
        $ngay_ket_thuc = strtotime($request->thoi_gian_di);
        $thoi_gian_o= round(($ngay_ket_thuc-$ngay_bat_dau)/ (60 * 60 * 24));
        var_dump($loaiPhongId['id'],$khuyenMai->loai_phong_id);
        if($khuyenMai->loai_phong_id == $loaiPhongId['id'] && $khuyenMai->loai_giam_gia == 1)
        {
            $tinh_tien = ($loaiPhong->gia * $request->so_luong_phong[$key]['so_luong_phong'] * $thoi_gian_o)-(($loaiPhong->gia * $request->so_luong_phong[$key]['so_luong_phong'] * $thoi_gian_o)*$khuyenMai->gia_tri_giam/100);
        }else if($khuyenMai->loai_phong_id != $loaiPhongId['id']){
            $tinh_tien = ($loaiPhong->gia * $request->so_luong_phong[$key]['so_luong_phong'] * $thoi_gian_o);
        }else if($khuyenMai->loai_phong_id == $loaiPhongId['id'] && $khuyenMai->loai_giam_gia == 0){
            $tinh_tien = ($loaiPhong->gia * $request->so_luong_phong[$key]['so_luong_phong'] * $thoi_gian_o)-$khuyenMai->gia_tri_giam;
        }
        $tong_tien = $tinh_tien+$tong_tien;
        }
        $phongIds = $phongIds->take(array_sum(array_column($request->so_luong_phong, 'so_luong_phong')));
        $datPhong->phongs()->attach($phongIds);

        $datPhong->update([
            'tong_tien' => $tong_tien
        ]);


        if($datPhong->dich_vu_id != null){

            $dichVuIds = explode(',', $datPhong->dich_vu_id);

            $tongTienDatPhong = $datPhong->tong_tien;

            // Tính toán tổng giá trị của các dịch vụ từ các ID
            $tongDichVu = 0;
            foreach ($dichVuIds as $dichVuId) {
                $dichVu = DichVu::find($dichVuId);
                if ($dichVu) {
                    $tongDichVu += $dichVu->gia;
                }
            }

            // Tính tổng tiền mới cho chi tiết đặt phòng
            $tongTienMoi = $tongTienDatPhong + $tongDichVu;

        }else{
            $tongTienDatPhong = $datPhong->tong_tien;

            $tongTienMoi = $tongTienDatPhong;
        }
        // Tạo một chi tiết đặt phòng và lưu tổng giá trị của các dịch vụ
        ChiTietDatPhong::create([
            'dat_phong_id' => $datPhong->id,
            'thanh_tien' => $tongTienMoi, // Tổng tiền mới cho chi tiết đặt phòng
        ]);

        return redirect()->route('admin.dat_phong.index')->with('success', 'Thêm mới dịch vụ thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(DatPhong $datPhong, ChiTietDatPhongDataTable $datatables)
    {
        //
        return $datatables->render('admin.dat_phong.show', compact('datPhong'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DatPhong $datPhong)
    {
        $so_luong_dich_vu = DichVu::count();
        $loai_phong = Loai_phong::query()->pluck('ten', 'id')->toArray();
        $user = User::query()->pluck('ten_nguoi_dung', 'id')->toArray();
        $dich_vus = DichVu::all();
        return view(self::PATH_VIEW . __FUNCTION__, compact('loai_phong', 'datPhong', 'user', 'dich_vus','so_luong_dich_vu'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DatPhong $datPhong, User $user): RedirectResponse
    {

        if (! Gate::allows('update-A&NV', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }

        $request->validate([
            'dich_vu_ids' => 'required|array',
            'dich_vu_ids.*.id' => 'required|numeric',
            'dich_vu_ids.*.so_luong' => 'required|numeric|min:0',
            'ghi_chu' => 'nullable|string',
        ]);
        // Tính toán tổng tiền cho các dịch vụ
        $tongTienDichVu = 0;
        foreach ($request->dich_vu_ids as $dichVuData) {
            $dichVu = DichVu::find($dichVuData['id']);
            if ($dichVu) {
                $tongTienDichVu += $dichVu->gia * $dichVuData['so_luong'];
            }
        }

        // Tính tổng tiền mới cho chi tiết đặt phòng
        $tongTienMoi = $datPhong->tong_tien + $tongTienDichVu;

        // Tạo một chi tiết đặt phòng mới hoặc cập nhật nếu đã tồn tại
        $chiTietDatPhong = ChiTietDatPhong::updateOrCreate(
            ['dat_phong_id' => $datPhong->id],
            ['thanh_tien' => $tongTienMoi]
        );

        // Lưu ghi chú vào đối tượng DatPhong
        $datPhong->ghi_chu = $request->ghi_chu;
        $datPhong->save();

        // Xóa các dịch vụ hiện có của DatPhong trước khi thêm mới
        $datPhong->dichVus()->detach();

        // Lưu dữ liệu vào bảng trung gian
        foreach ($request->dich_vu_ids as $dichVuData) {
            $dichVu = DichVu::find($dichVuData['id']);
            if ($dichVu) {
                $datPhong->dichVus()->attach($dichVu->id, ['so_luong' => $dichVuData['so_luong']]);
            }
        }
        return back()->with('msg', 'Cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DatPhong $datPhong, User $user): RedirectResponse
    {
        if (! Gate::allows('delete-A&NV', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }
        $datPhong->delete();
        return response(['trang_thai' => 'success']);
    }
}

// ----------------------------------------------------------------Form của create add nhiều loại phòng cùng lúc--------------------------------------------------------------------------

// <div id="dynamic-form">
//                                 <div class="form-group mt-3 mx-auto" style="display: inline-block; width:629px">
//                                     <label for="loai_phong_ids_{{$i}}">Loại Phòng</label>
//                                 <input type="text" name="loai_phong_ids[{{$i}}][id]" id="loai_phong_ids_{{$i}}" class="form-control" list="loai_phong">
//                                     <datalist id="loai_phong">
//                                         @foreach ($loai_phong as $id => $ten)
//                                             <option value="{{$id}}">{{$ten}}</option>
//                                         @endforeach
//                                     </datalist>
//                                     <span class="text-danger error-loai_phong_id_{{$i}}"></span>
//                                 </div>

//                                 <div class="form-group mt-3 mx-auto" style="display: inline-block; width:629px">
//                                     <label for="so_luong_phong_{{$i}}">Số Lượng phòng</label>
//                                     <input type="number" class="form-control" name="so_luong_phong[{{$i}}][so_luong_phong]" id="so_luong_phong_{{$i}}" value="0" min="0">
//                                     <span class="text-danger error-so_luong_phong"></span>
//                                 </div>
//                             </div> -->

//                             <!-- <button type="button" id="add-button" class="btn btn-primary">Thêm</button>



//--------------------------------------------------------------------Js của form trên---------------------------------------------------------------------------

// <script>
//     document.getElementById('add-button').addEventListener('click', function() {
//         var form = document.getElementById('dynamic-form');
//         var html = `
//             <div class="form-group mt-3 mx-auto" style="display: inline-block; width:629px">
//                 <label for="loai_phong_ids_{{$i}}">Loại Phòng</label>
//                 <input type="text" name="loai_phong_ids[{{$i}}][id]" id="loai_phong_ids_{{$i}}" class="form-control" list="loai_phong">
//                 <datalist id="loai_phong">
//                     @foreach ($loai_phong as $id => $ten)
//                         <option value="{{$id}}">{{$ten}}</option>
//                     @endforeach
//                 </datalist>
//                 <span class="text-danger error-loai_phong_ids_{{$i}}"></span>
//             </div>

//             <div class="form-group mt-3 mx-auto" style="display: inline-block; width:629px">
//                 <label for="so_luong_phong_{{$i}}">Số Lượng phòng</label>
//                 <input type="number" class="form-control" name="so_luong_phong[{{ $i }}][so_luong_phong]" id="so_luong_phong_{{ $i }}" value="0" min="0">
//                 <span class="text-danger error-so_luong_phong"></span>
//             </div>
//         `;

//         form.insertAdjacentHTML('beforeend', html);
//     });
// </script>

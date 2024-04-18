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
use App\Models\DatPhongDichVu;
use App\Models\DatPhongLoaiPhong;
use Illuminate\Support\Facades\DB;
use App\Models\DatPhongNoiPhong;
use App\Models\Hotel;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\OLE\PPS;

use function Laravel\Prompts\alert;
use function Livewire\store;
use Yajra\DataTables\DataTables;

class DatPhongController extends Controller
{
    const PATH_VIEW = 'admin.dat_phong.';


    public function index(Request $request, DatPhongDataTable $datatables)
    {
      
        // $datphong = DatPhong::query()->latest()->paginate(7);
        return $datatables->render(self::PATH_VIEW . __FUNCTION__);
    }

    public function search(Request $request, DatPhongDataTable $datatables){
        $dataTableQuery = DatPhong::query()->with(['user']);

        if ($request->has('startTime') && $request->has('endTime') && $request->filled('startTime') && $request->filled('endTime')) {
            $from = Carbon::createFromFormat('Y-m-d', $request->get('startTime'));
            $to = Carbon::createFromFormat('Y-m-d', $request->get('endTime'));
        
            $dataTableQuery->whereBetween('thoi_gian_den', [$from, $to]);
        }
        

        
        if ($request->has('status') && $request->status != 2) {
            $dataTableQuery->where('trang_thai','=',$request->status);
        }
      
        if ($request->has('create_date_time') && $request->filled('create_date_time')) {
            $create_date_time = Carbon::createFromFormat('Y-m-d', $request->get('create_date_time'));
            $dataTableQuery->where('created_at','>=',$create_date_time);
        }
        $datphong = $dataTableQuery->get();
     
        return Datatables::of($datphong)
        ->addColumn('action', 'datphong.action')
        ->addColumn('ten_khach_hang', function($query){
            return $query->user->ten_nguoi_dung;
        })
        ->addColumn('email', function($query){
            return $query->user->email;
        })
        ->addColumn('so_dien_thoai', function($query){
            return $query->user->so_dien_thoai;
        })
        // ->addColumn('don_gia', function($query){
        //     return $query->loai_phong->gia;
        // })
        ->addColumn('trang_thai', function ($query) {
            $active = "<span class='badge text-bg-success'>Đã xác nhận</span>";
            $inActive = "<span class='badge text-bg-danger'>Chờ xác nhận</span>";
            if ($query->trang_thai == 1) {
                return $active;
            } else {
                return $inActive;
            }
        })
        ->addColumn('action', function ($query) {
            $editBtn = "<a href='" . route('admin.dat_phong.edit', $query->id) . "' class='btn btn-primary'>
            <i class='bi bi-pen'></i>
            </a>";
            // $anhBtn = "<a href='" . route('admin.anh_phong.index',['loai_phong' =>  $query->id]) . "' class='btn btn-info ms-2'>
            // <i class='bi bi-image'></i>
            // </a>";

            // $detailBtn = "<a href='" . route('admin.loai_phong.show', $query->id) . "' class='btn btn-secondary ms-2'>
            // <i class='bi bi-card-list'></i>
            // </a>";
            $deleteBtn = "<a href='" . route('admin.dat_phong.destroy', $query->id) . "' class='btn btn-danger delete-item ms-2'>
            <i class='bi bi-archive'></i>
            </a>";
            // $phongBtn = "<a href='" . route('admin.phong.index',['loai_phong' =>  $query->id]) . "' class='btn btn-warning ms-2'>
            // <i class='bi bi-houses-fill'></i>
            // </a>";
            // $cmBtn =  "<a href='" . route('admin.danh_gia.index',['loai_phong' => $query->id]) . "' class='btn btn-dark ms-2'>
            // <i class='bi bi-chat-dots'></i>
            // </a>";
            $detailBtn = "<a href='" . route('admin.dat_phong.show', ['dat_phong' => $query->id]) . "' class='btn btn-secondary ms-2'>
            <i class='bi bi-list-ul'></i>
            </a>";



            return $editBtn . $deleteBtn . $detailBtn ;
        })
        ->rawColumns(['ten_khach_hang','loai_phong_id','email','so_dien_thoai', 'phong_id','trang_thai','action'])
        ->setRowId('id')
        // ->rawColumns(['action'])
        ->make(true);
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
    public function store(Request $request, User $user, Loai_phong $loaiPhong, KhuyenMai $khuyenMai): RedirectResponse
    {
        if (!Gate::allows('create-A&NV', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }
        //dd($request);
        // $request->validate([
        //     'loai_phong_ids' => 'required|array',
        //     'loai_phong_ids.*.id' => 'required|numeric',
        //     'loai_phong_ids.*.so_luong_phong' => 'required|numeric|min:0',
        //     'ghi_chu' => 'nullable|string',
        // ]);
        // dd($request);

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
        $tong_tien=0;
        // $thoiGianDenFormatted = Carbon::createFromFormat('Y-m-d', $datPhong->thoi_gian_den)->format('Y-m-d');
        // $thoiGianDiFormatted = Carbon::createFromFormat('Y-m-d', $datPhong->thoi_gian_di)->format('Y-m-d');
        foreach ($request->loai_phong_ids as $key => $loaiPhongId){
        // // Lấy số lượng phòng từ mảng so_luong_phong theo chỉ số tương ứng
        // $soLuongPhong = $request->so_luong_phong[$key]['so_luong_phong'];
        // // Thêm dữ liệu vào bảng liên kết
        // $datPhong->loaiPhongs()->attach($loaiPhongId['id'], ['so_luong_phong' => $soLuongPhong]);
        // $phongIds = Phong::where('loai_phong_id', $loaiPhongId['id'])
        // ->whereDoesntHave('dat_phong_noi_phongs.dat_phongs', function ($query) {
        //     $query->where('thoi_gian_di', '<=', '$datPhong->thoi_gian_di')
        //         ->where('thoi_gian_den', '>=', '$datPhong->thoi_gian_den');
        // })
        // ->limit($request->so_luong_phong[$key]['so_luong_phong'])
        // ->pluck('p.id');
        // $datPhong->phongs()->attach($phongIds);
        $phongIds = DB::select("
        SELECT p.id
        FROM phongs p
        WHERE p.loai_phong_id = {$loaiPhongId['id']}
        AND NOT EXISTS (
            SELECT 1
            FROM dat_phong_noi_phongs dp
            LEFT JOIN dat_phongs d ON dp.dat_phong_id = d.id
            WHERE p.id = dp.phong_id
            AND (
                d.thoi_gian_di <= '{$datPhong->thoi_gian_di}' AND d.thoi_gian_den >= '{$datPhong->thoi_gian_den}'
            )
        )
        LIMIT {$request->so_luong_phong[$key]['so_luong_phong']};
        ");
        $datPhong->phongs()->attach($phongIds);
        }
        $ngay_bat_dau = strtotime($request->thoi_gian_den);
        $ngay_ket_thuc = strtotime($request->thoi_gian_di);
        $loaiPhong = Loai_phong::find($loaiPhongId['id']);
        $khuyenMai = KhuyenMai::find($request->khuyen_mai_id);
        $thoi_gian_o= round(($ngay_ket_thuc-$ngay_bat_dau)/ (60 * 60 * 24));
        foreach ($request->loai_phong_ids as $key => $loaiPhongId){
        if($khuyenMai->loai_phong_id == $loaiPhongId['id'] && $khuyenMai->loai_giam_gia == 1)
        {
            $tinh_tien = ($loaiPhong->gia * $request->so_luong_phong[$key]['so_luong_phong'] * $thoi_gian_o)-(($loaiPhong->gia * $request->so_luong_phong[$key]['so_luong_phong'] * $thoi_gian_o)*$khuyenMai->gia_tri_giam/100);
        }else if($khuyenMai->loai_phong_id != $loaiPhongId['id'] || !$khuyenMai){
            $tinh_tien = ($loaiPhong->gia * $request->so_luong_phong[$key]['so_luong_phong'] * $thoi_gian_o);
        }else if($khuyenMai->loai_phong_id == $loaiPhongId['id'] && $khuyenMai->loai_giam_gia == 0){
            $tinh_tien = ($loaiPhong->gia * $request->so_luong_phong[$key]['so_luong_phong'] * $thoi_gian_o)-$khuyenMai->gia_tri_giam;
        }
        $tong_tien = $tinh_tien+$tong_tien;
        }
        $datPhong->update([
            'tong_tien' => $tong_tien
        ]);




        if ($datPhong->dich_vu_id != null) {

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
        } else {
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
    public function show(ChiTietDatPhongDataTable $datatables, string $id, DichVu $dichVu)
    {
        $thongTinHotel = Hotel::all();
        $giaLoaiPhongs = collect();
        $datPhong = DatPhong::findOrFail($id);
        $loai_phong_ids =DatPhongLoaiPhong::where('dat_phong_id', $id)->pluck('loai_phong_id');
        $so_luong_phong =DatPhongLoaiPhong::where('dat_phong_id', $id)->pluck('so_luong_phong');
        $tenLoaiPhongs = collect();
        foreach($loai_phong_ids as $loai_phong_id){
            $tenLoaiPhong = Loai_phong::where('id', $loai_phong_id)->pluck('ten');
            $tenLoaiPhongs = $tenLoaiPhongs->merge($tenLoaiPhong);
            $giaLoaiPhong = Loai_phong::where('id', $loai_phong_id)->pluck('gia');
            $giaLoaiPhongs = $giaLoaiPhongs->merge($giaLoaiPhong);
        };
        $loaiPhong = $tenLoaiPhongs -> zip($giaLoaiPhongs,$so_luong_phong);


        $tenDichVus = collect();
        $giaDichVus = collect();
        $dich_vu_ids = DatPhongDichVu::where('dat_phong_id', $id)->pluck('dich_vu_id');
        $so_luong_dich_vu = DatPhongDichVu::where('dat_phong_id', $id)->pluck('so_luong');
        foreach($dich_vu_ids as $dich_vu_id){
            $tenDichVu = DichVu::where('id', $dich_vu_id)->pluck('ten_dich_vu');
            $tenDichVus = $tenDichVus->merge($tenDichVu);
            $giaDichVu = DichVu::Where('id', $dich_vu_id)->pluck('gia');
            $giaDichVus = $giaDichVus->merge($giaDichVu);
        };
        $dichVu = $tenDichVus->zip($giaDichVus,$so_luong_dich_vu);
        $thanhTien = ChiTietDatPhong::where('dat_phong_id', $datPhong['id'])->pluck('thanh_tien')->first();
        return $datatables->render('admin.dat_phong.show', compact('datPhong','loai_phong_ids','loaiPhong','dichVu','thongTinHotel','thanhTien'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DatPhong $datPhong)
    {
        $j = 0;
        $so_luong_dich_vu = DichVu::count();
        $loai_phong = Loai_phong::query()->pluck('ten', 'id')->toArray();
        $user = User::query()->pluck('ten_nguoi_dung', 'id')->toArray();
        $dich_vus = DichVu::all();
        return view(self::PATH_VIEW . __FUNCTION__, compact('loai_phong', 'datPhong', 'user', 'dich_vus', 'so_luong_dich_vu','j'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DatPhong $datPhong, User $user): RedirectResponse
    {

        if (!Gate::allows('update-A&NV', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }

        // $request->validate([
        //     'dich_vu_ids' => 'required|array',
        //     'dich_vu_ids.*.id' => 'required|numeric',
        //     'dich_vu_ids.*.so_luong' => 'required|numeric|min:0',
        //     'ghi_chu' => 'nullable|string',
        // ]);
        // Tính toán tổng tiền cho các dịch vụ
        // Xóa các dịch vụ hiện có của DatPhong trước khi thêm mới
        $datPhong->dichVus()->detach();

        $tongTienDichVu = 0;
        foreach ($request->dich_vu_ids as $key => $dichVuId) {
            $soLuongDichVu = $request->so_luong[$key]['so_luong'];
            $datPhong->dichVus()->attach($dichVuId['id'], ['so_luong' => $soLuongDichVu]);
            $dichVu = DichVu::find($dichVuId['id']);
            $tongTienDichVu += $dichVu->gia * $soLuongDichVu;
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


        // Lưu dữ liệu vào bảng trung gian
        return back()->with('msg', 'Cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DatPhong $datPhong, User $user): RedirectResponse
    {
        if (!Gate::allows('delete-A&NV', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }
        $datPhong->delete();
        return response(['trang_thai' => 'success']);
    }

}


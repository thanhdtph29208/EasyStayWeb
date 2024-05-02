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
use Exception;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
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

    public function search(Request $request, DatPhongDataTable $datatables)
    {
        $dataTableQuery = DatPhong::query()->with(['user']);
        if ($request->filled('startTime') && $request->filled('startHour')) {
            $startDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('startTime') . ' ' . $request->input('startHour'));
            $dataTableQuery->where('thoi_gian_den', '>=', $startDateTime);
        }

        if ($request->filled('endTime') && $request->filled('endHour')) {
            $endDateTime = Carbon::createFromFormat('Y-m-d H:i', $request->input('endTime') . ' ' . $request->input('endHour'));
            $dataTableQuery->where('thoi_gian_den', '<=', $endDateTime);
        }
        if ($request->has('startTime') || $request->has('endTime')) {
            // Nếu ngày bắt đầu đã được điền
            if ($request->filled('startTime')) {
                $from = Carbon::createFromFormat('Y-m-d', $request->get('startTime'));
                $dataTableQuery->where('thoi_gian_den', '>=', $from);
            }

            // Nếu ngày kết thúc đã được điền
            if ($request->filled('endTime')) {
                $to = Carbon::createFromFormat('Y-m-d', $request->get('endTime'));
                $dataTableQuery->where('thoi_gian_den', '<=', $to);
            }
        }



        if ($request->has('status') && $request->status != 2) {
            $dataTableQuery->where('trang_thai', '=', $request->status);
        }

        if ($request->has('create_date_time') && $request->filled('create_date_time')) {
            $create_date_time = Carbon::createFromFormat('Y-m-d', $request->get('create_date_time'));
            $dataTableQuery->where('created_at', '>=', $create_date_time);
        }
        $datphong = $dataTableQuery->get();

        return Datatables::of($datphong)
            ->addColumn('action', 'datphong.action')
            ->addColumn('ten_khach_hang', function ($query) {
                return $query->user->ten_nguoi_dung;
            })
            ->addColumn('email', function ($query) {
                return $query->user->email;
            })
            ->addColumn('so_dien_thoai', function ($query) {
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



                return $editBtn . $deleteBtn . $detailBtn;
            })
            ->rawColumns(['ten_khach_hang', 'loai_phong_id', 'email', 'so_dien_thoai', 'phong_id', 'trang_thai', 'action'])
            ->setRowId('id')
            // ->rawColumns(['action'])
            ->make(true);
    }
    public function create(DatPhong $datPhong, Request $request)
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
        $loai_phong_id = $request->query('loai_phong_id');
        // dd($loai_phong_id);
        $ten_loai_phong = Loai_phong::Where('id', $loai_phong_id)->pluck('ten');
        $thoi_gian_den = $request->query('thoi_gian_den');
        $thoi_gian_di = $request->query('thoi_gian_di');
        $phong_trong = $request->query('phong_trong');
        // dd($phong_trong);
        // dd($thoi_gian_den,$thoi_gian_di);
        $i = 0;
        $so_luong_loai_phong = Loai_phong::count();
        $user = User::query()->pluck('email', 'id')->toArray();
        $loai_phong = Loai_phong::query()->pluck('ten', 'id')->toArray();
        $phong = Phong::query()->pluck('ten_phong', 'id')->toArray();
        $khuyen_mai = KhuyenMai::query()->pluck('ten_khuyen_mai', 'id')->toArray();
        return view(self::PATH_VIEW . __FUNCTION__, compact('user', 'datPhong', 'loai_phong', 'phong', 'khuyen_mai', 'so_luong_loai_phong', 'i', 'loai_phong_id', 'ten_loai_phong', 'thoi_gian_den', 'thoi_gian_di','phong_trong'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user, Loai_phong $loaiPhong, KhuyenMai $khuyenMai): RedirectResponse
    {
        if (!Gate::allows('create-A&NV', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }
        $tong_nguoi = 0;
        foreach ($request->loai_phong_ids as $key => $loaiPhongId) {
            $gioi_han_nguoi = Loai_phong::Where('id', $loaiPhongId['id'])->pluck('gioi_han_nguoi');
            $tong_nguoi = $tong_nguoi + ($gioi_han_nguoi[0] * (int)$request->so_luong_phong[$key]['so_luong_phong']);
        }
        if ($request->so_luong_nguoi > $tong_nguoi) {
            alert('Số lượng người vượt quá giới hạn người của phòng');
            // return response(['status' => 'error', 'message' => 'Số lượng người vượt quá giới hạn người của phòng']);
            return Redirect::back();
        }

        // dd(Carbon::parse($request->thoi_gian_den)->format('Y-m-d 14:00:00'));
        // dd($request);
        // dd(Auth::user()->id);
        $user = Auth::user();

        $rules = [
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
            'ho_ten' => 'nullable|string|max:255',
            'so_dien_thoai' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
            'so_luong_phong' => 'min:0',
            'so_luong_nguoi' => 'numeric|min:0',
            'thoi_gian_den' => 'date|required',
            'thoi_gian_di' => 'date|required',
            'khuyen_mai_id' => 'nullable',
            'payment' => 'required',
            'ghi_chu' => 'nullable|string',
        ];


        $messages = [
            'email.required' => 'Email không được bỏ trống',
            'email.lowercase' => 'Email phải là chữ thường',
            'email.email' => 'Email không đúng định dạng',
            'email.max' => 'Email không quá 255 ký tự',

            'so_dien_thoai.regex' => 'Số điện thoại không đúng định dạng',
            'so_dien_thoai.min' => 'Số điện thoại tối thiểu 9 số',


            'so_luong_nguoi.numeric' => 'Số lượng người phải là 1 số',
            // 'so_luong.nguoi.integer' => 'Số lượng người phải là 1 số nguyên',
            'so_luong.nguoi.min' => 'Số lượng người phải là 1 số dương',

            // 'so_luong_phong.numeric' => 'Số lượng phong phải là 1 số',
            // 'so_luong.phong.integer' => 'Số lượng phong phải là 1 số nguyên',
            'so_luong.phong.min' => 'Số lượng phong phải là 1 số dương',



            'thoi_gian_den.date' => 'Thời gian đến không đúng định dạng',
            'thoi_gian_den.required' => 'Thời gian đến không được để trống',

            'thoi_gian_di.date' => 'Thời gian đi không đúng định dạng ngày',
            'thoi_gian_di.required' => 'Thời gian đi không được để trống',

            'payment.required' => 'Hình thức thanh toán không được để trống',

        ];

        $validated = $request->validate($rules, $messages);

        // $request->validate([
        //     'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
        //     'ho_ten' =>'nullable|string|max:255',
        //     'so_dien_thoai' => 'regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
        //     'so_luong_nguoi' => 'numeric|integer',
        //     'thoi_gian_den' => 'date|required',
        //     'thoi_gian_di' => 'date|required',
        //     'khuyen_mai_id' => 'nullable',
        //     'payment' => 'required',
        //     'ghi_chu' => 'nullable|string',
        // ]);
        // dd($request);

        $datPhong = DatPhong::create([
            'user_id' => $user->id,
            'email' => $request->email,
            'ho_ten' => $request->ho_ten,
            'so_dien_thoai' => $request->so_dien_thoai,
            'so_luong_nguoi' => $request->so_luong_nguoi,
            'thoi_gian_den' => $request->thoi_gian_den,
            'thoi_gian_di' => $request->thoi_gian_di,
            'khuyen_mai_id' => $request->khuyen_mai_id,
            'payment' => $request->payment,
            'trang_thai' => 0,
            'ghi_chu' => $request->ghi_chu,
        ]);


        $datPhongId = $datPhong->id;
        // Thêm dữ liệu vào bảng DatPhongLoaiPhong



        $thoi_gian_den = Carbon::parse($request->thoi_gian_den)->format('Y-m-d 14:00:00');
        $thoi_gian_di = Carbon::parse($request->thoi_gian_di)->format('Y-m-d 12:00:00');
        foreach ($request->loai_phong_ids as $key => $loaiPhongId) {
            $phongIds = DB::select("
            SELECT p.id
            FROM phongs p
            WHERE p.loai_phong_id = {$loaiPhongId['id']}
            AND (p.deleted_at IS NULL)
            AND NOT EXISTS (
                SELECT 1
                FROM dat_phong_noi_phongs dp
                LEFT JOIN dat_phongs d ON dp.dat_phong_id = d.id
                WHERE p.id = dp.phong_id
                AND (
                    ('$thoi_gian_di' BETWEEN d.thoi_gian_di AND d.thoi_gian_den)
                    OR ('$thoi_gian_den' BETWEEN d.thoi_gian_di AND d.thoi_gian_den)
                    OR (d.thoi_gian_di BETWEEN '$thoi_gian_den' AND '$thoi_gian_di')
                    OR (d.thoi_gian_den BETWEEN '$thoi_gian_den' AND '$thoi_gian_di')
                )
                AND (d.deleted_at IS NULL)
            )
            LIMIT {$request->so_luong_phong[$key]['so_luong_phong']};
            ");
            if ($phongIds == null) {
                $datPhong->delete();
                return back();
            } else {
                foreach ($phongIds as $phongId) {
                    $datPhong->phongs()->attach($phongId);
                }
            }
        }


        foreach ($request->loai_phong_ids as $key => $loaiPhongId) {
            // Lấy số lượng phòng từ mảng so_luong_phong theo chỉ số tương ứng
            $soLuongPhong = $request->so_luong_phong[$key]['so_luong_phong'];

            // Thêm dữ liệu vào bảng liên kết
            $datPhong->loaiPhongs()->attach($loaiPhongId['id'], ['so_luong_phong' => $soLuongPhong]);
        }


        $tong_tien = 0;
        foreach ($request->loai_phong_ids as $key => $loaiPhongId) {
            $ngay_bat_dau = strtotime($request->thoi_gian_den);
            $ngay_ket_thuc = strtotime($request->thoi_gian_di);
            $loaiPhong = Loai_phong::find($loaiPhongId['id']);
            $khuyenMai = KhuyenMai::find($request->khuyen_mai_id);
            $thoi_gian_o = round(($ngay_ket_thuc - $ngay_bat_dau) / (60 * 60 * 24));
            foreach ($request->loai_phong_ids as $key => $loaiPhongId) {
                if (!$khuyenMai || !$request->khuyen_mai_id) {
                    $tinh_tien = ($loaiPhong->gia * $request->so_luong_phong[$key]['so_luong_phong'] * $thoi_gian_o);
                } else if ($khuyenMai->loai_giam_gia == 1) {
                    $tinh_tien = ($loaiPhong->gia * $request->so_luong_phong[$key]['so_luong_phong'] * $thoi_gian_o) - (($loaiPhong->gia * $request->so_luong_phong[$key]['so_luong_phong'] * $thoi_gian_o) * $khuyenMai->gia_tri_giam / 100);
                } else if ($khuyenMai->loai_giam_gia == 0) {
                    $tinh_tien = ($loaiPhong->gia * $request->so_luong_phong[$key]['so_luong_phong'] * $thoi_gian_o) - $khuyenMai->gia_tri_giam;
                }
                $tong_tien = $tinh_tien + $tong_tien;
            }
            $datPhong->update([
                'tong_tien' => $tong_tien
            ]);
        }




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
        $loai_phong_ids = DatPhongLoaiPhong::where('dat_phong_id', $id)->pluck('loai_phong_id');
        $so_luong_phong = DatPhongLoaiPhong::where('dat_phong_id', $id)->pluck('so_luong_phong');
        $tenLoaiPhongs = collect();
        foreach ($loai_phong_ids as $loai_phong_id) {
            $tenLoaiPhong = Loai_phong::where('id', $loai_phong_id)->pluck('ten');
            $tenLoaiPhongs = $tenLoaiPhongs->merge($tenLoaiPhong);
            $giaLoaiPhong = Loai_phong::where('id', $loai_phong_id)->pluck('gia');
            $giaLoaiPhongs = $giaLoaiPhongs->merge($giaLoaiPhong);
        };
        $loaiPhong = $tenLoaiPhongs->zip($giaLoaiPhongs, $so_luong_phong);
        $phong_ids = DatPhongNoiPhong::where('dat_phong_id', $datPhong->id)->pluck('phong_id');
        $tenPhongs = collect();
        foreach ($phong_ids as $phong_id) {
            $tenPhong = Phong::where('id', $phong_id)->pluck('ten_phong');
            $tenPhongs = $tenPhongs->merge($tenPhong);
        }
        $tenDichVus = collect();
        $giaDichVus = collect();
        $dich_vu_ids = DatPhongDichVu::where('dat_phong_id', $id)->pluck('dich_vu_id');
        $so_luong_dich_vu = DatPhongDichVu::where('dat_phong_id', $id)->pluck('so_luong');
        foreach ($dich_vu_ids as $dich_vu_id) {
            $tenDichVu = DichVu::where('id', $dich_vu_id)->pluck('ten_dich_vu');
            $tenDichVus = $tenDichVus->merge($tenDichVu);
            $giaDichVu = DichVu::Where('id', $dich_vu_id)->pluck('gia');
            $giaDichVus = $giaDichVus->merge($giaDichVu);
        };
        $dichVu = $tenDichVus->zip($giaDichVus, $so_luong_dich_vu);
        $thanhTien = ChiTietDatPhong::where('dat_phong_id', $datPhong['id'])->pluck('thanh_tien')->first();
        return $datatables->render('admin.dat_phong.show', compact('datPhong', 'loai_phong_ids', 'loaiPhong', 'dichVu', 'thongTinHotel', 'thanhTien', 'tenPhongs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DatPhong $datPhong, Request $request)
    {

        $j = 0;
        $so_luong_dich_vu = DichVu::count();
        $loai_phong = Loai_phong::query()->pluck('ten', 'id')->toArray();
        $user = User::query()->pluck('ten_nguoi_dung', 'id')->toArray();
        $dich_vus = DichVu::all();
        return view(self::PATH_VIEW . __FUNCTION__, compact('loai_phong', 'datPhong', 'user', 'dich_vus', 'so_luong_dich_vu', 'j'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DatPhong $datPhong, User $user): RedirectResponse
    {

        if (!Gate::allows('update-A&NV', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }

        $rules = [
            'ten_dich_vu' => 'max:255|unique:dich_vus',
            'so_luong' => 'min:0',
            'ghi_chu' => 'nullable|string',
        ];

        $messages = [
            'ten_dich_vu.max' => 'Tên dịch vụ không được vượt quá 255 ký tự',
            'ten_dich_vu.unique' => 'Tên dịch vụ đã tồn tại',

            // 'so_luong.numeric' => 'Số lượng phải là 1 số',
            'so_luong.min' => 'Số lượng phải là 1 số dương',


        ];

        $validated = $request->validate($rules, $messages);

        // $request->validate([
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
    public function destroy(DatPhong $datPhong, User $user)
    {
        if (!Gate::allows('delete-A&NV', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }
        $datPhong->delete();
        return response(['trang_thai' => 'success']);
    }

    public function changeStatus(Request $request)
    {
        try{

        $dat_phong = DatPhong::findOrFail($request->id);
        $dat_phong->trang_thai = $request->status == 'true' ? '1' : '0';
        $dat_phong->save();
        return response(['message' => 'Xác nhận đơn hàng thành công']);
        }catch(Exception $e){
            return $e;

        }
    }
}

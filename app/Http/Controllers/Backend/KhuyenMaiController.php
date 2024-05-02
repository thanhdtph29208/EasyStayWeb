<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\KhuyenMaiDataTable;
use App\Models\KhuyenMai;
use Illuminate\Http\Request;
use App\Models\Phong;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Loai_phong;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
class KhuyenMaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        // Tạo một query builder cho model KhuyenMai
        $query = KhuyenMai::query();

        // Kiểm tra và thêm điều kiện lọc theo thời gian bắt đầu và kết thúc
        if ($request->has('startTime') && $request->has('endTime') && $request->filled('startTime') && $request->filled('endTime')) {
            $from = Carbon::createFromFormat('Y-m-d', $request->get('startTime'));
            $to = Carbon::createFromFormat('Y-m-d', $request->get('endTime'));

            $query->whereBetween('ngay_bat_dau', [$from, $to]);
        }

        // Kiểm tra và thêm điều kiện lọc theo trạng thái
        if ($request->has('status') && $request->status != 2) {
            $query->where('trang_thai', $request->status);
        }

        // Kiểm tra và thêm điều kiện lọc theo thời gian tạo
        if ($request->has('create_date_time') && $request->filled('create_date_time')) {
            $create_date_time = Carbon::createFromFormat('Y-m-d', $request->get('create_date_time'));
            $query->where('created_at', '>=', $create_date_time);
        }

        // Truy vấn dữ liệu khuyến mãi
        $khuyenmais = $query->get();

        // Trả về dữ liệu dưới dạng DataTables
        return DataTables::of($khuyenmais)
            ->addColumn('action', function ($query) {
                // Thêm các nút chỉnh sửa và xóa
                $editBtn = "<a href='" . route('admin.khuyen_mai.edit', $query->id) . "' class='btn btn-primary'>
                                <i class='bi bi-pen'></i>
                            </a>";

                $deleteBtn = "<a href='" . route('admin.khuyen_mai.destroy', $query->id) . "' class='btn btn-danger delete-item ms-2'>
                                <i class='bi bi-archive'></i>
                            </a>";

                return $editBtn . $deleteBtn;
            })
            ->rawColumns(['loai_giam_gia', 'gia_tri_giam', 'loai_phong_id','trang_thai', 'action'])
            ->setRowId('id')
            // ->rawColumns([
            ->make(true);
    }


    public function index(Request $request, KhuyenMaiDataTable $datatables)
    {

        return $datatables->render('admin.khuyen_mai.index');
        $khuyenmais = KhuyenMai::all();
        foreach($khuyenmais as $khuyenmai){
            $trang_thai = $khuyenmai->checkStatus();
            switch($trang_thai){
                case 0:
                    echo "Chưa áp dụng";
                    break;
                case 1:
                    echo "Đang áp dụng";
                    break;
                case 2:
                    echo "Kết thúc";
                    break;

            }
        }


        // return view('admin.khuyen_mai.index', compact('khuyenMaiList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $loai_phongs = Loai_phong::all();
        return view('admin.khuyen_mai.create', compact('loai_phongs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user): RedirectResponse
    {
        if (!Gate::allows('create', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }
        //
        $data = $request->all();
        $rules = [
            'ten_khuyen_mai' => 'required|string|max:255',
            'ma_giam_gia' => 'required|string|max:255|unique:khuyen_mais,ma_giam_gia',
            'loai_giam_gia' => 'required|boolean',
            'gia_tri_giam' => 'required|numeric|min:0',
            // 'so_luong' => 'required|integer|min:0',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
            'trang_thai' => 'required|boolean',
            'mo_ta' => 'nullable|string|max:255',
        ];

        $messages = [
            'ten_khuyen_mai.required' => 'Tên khuyến mãi không được bỏ trống',
            'ten_khuyen_mai.max' => 'Tên khuyến mãi không được quá 255 ký tự',

            'ma_giam_gia.required' => 'Mã giảm giá không được bỏ trống',
            'ma_giam_gia.max' => 'Mã giảm giá không được quá 255 ký tự',
            'ma_giam_gia.unique' => 'Mã giảm giá đã tồn tại',

            'loai_giam_gia.required' => 'Loại giảm giá không được bỏ trống',

            'gia_tri_giam.required' => 'Giá trị giảm không được bỏ trống',
            'gia_tri_giam.numeric' => 'Giá trị giảm phải là 1 số',
            'gia_tri_giam.min' => 'Giá trị giảm phải là 1 số dương',

            // 'so_luong.required' => 'Số lượng không được bỏ trống',
            // 'so_luong.integer' => 'Số lượng phải là 1 số nguyên',
            // 'so_luong.min' => 'Số lượng phải là 1 số dương',

            'ngay_bat_dau.required' => 'Ngày bắt đầu không được bỏ trống',
            'ngay_bat_dau.date' => 'Ngày bắt đầu không đúng định dạng ngày',

            'ngay_ket_thuc.required' => 'Ngày kết thúc không được bỏ trống',
            'ngay_ket_thuc.date' => 'Ngày kết thúc không đúng định dạng ngày',
            'ngay_ket_thuc.after' => 'Thời gian ngày kết thúc phải sau ngày bắt đầu',

            'trang_thai.required' => 'Trạng thái không được bỏ trống',

            'mo_ta.required' => 'Mô tả không được bỏ trống',
        ];

        $validated = $request->validate($rules, $messages);
        // $request->validate([
        //     'ten_khuyen_mai' => 'required|string|max:255',
        //     'loai_phong_id' => 'required|integer|exists:phongs,id',
        //     'ma_giam_gia' => 'required|string|max:255|unique:khuyen_mais,ma_giam_gia',
        //     'loai_giam_gia' => 'required|boolean',
        //     'gia_tri_giam' => 'required|numeric',
        //     'so_luong' => 'required|integer',
        //     'ngay_bat_dau' => 'required|date',
        //     'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
        //     'trang_thai' => 'required|boolean',
        //     'mo_ta' => 'nullable|string',
        // ]);


        KhuyenMai::query()->create($data);
        Toastr::success('Thêm khuyến mãi thành công', 'Thành công');
        return redirect()->route('admin.khuyen_mai.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(KhuyenMai $khuyenMai)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KhuyenMai $khuyenMai)
    {

        //

        $loai_phongs = Loai_phong::all();
        return view('admin.khuyen_mai.edit', compact('khuyenMai', 'loai_phongs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id, User $user): RedirectResponse
    {
        if (!Gate::allows('update', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }
        //
        $khuyenMai = KhuyenMai::findOrFail($id);

        $rules = [
            'ten_khuyen_mai' => 'required|string|max:255',
            'ma_giam_gia' => 'required|string|max:255|unique:khuyen_mais,ma_giam_gia,' . $khuyenMai->id,
            'loai_giam_gia' => 'required|boolean',
            'gia_tri_giam' => 'required|numeric|min:0',
            // 'so_luong' => 'required|integer|min:0',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
            'trang_thai' => 'required|boolean',
            'mo_ta' => 'nullable|string|max:255',
        ];

        $messages = [
            'ten_khuyen_mai.required' => 'Tên khuyến mãi không được bỏ trống',
            'ten_khuyen_mai.max' => 'Tên khuyến mãi không được quá 255 ký tự',


            'ma_giam_gia.required' => 'Mã giảm giá không được bỏ trống',
            'ma_giam_gia.max' => 'Mã giảm giá không được quá 255 ký tự',
            'ma_giam_gia.unique' => 'Mã giảm giá đã tồn tại',

            'loai_giam_gia.required' => 'Loại giảm giá không được bỏ trống',

            'gia_tri_giam.required' => 'Giá trị giảm không được bỏ trống',
            'gia_tri_giam.numeric' => 'Giá trị giảm phải là 1 số',
            'gia_tri_giam.min' => 'Giá trị giảm phải là 1 số dương',

            // 'so_luong.required' => 'Số lượng không được bỏ trống',
            // 'so_luong.integer' => 'Số lượng phải là 1 số nguyên',
            // 'so_luong.min' => 'Số lượng phải là 1 số dương',

            'ngay_bat_dau.required' => 'Ngày bắt đầu không được bỏ trống',
            'ngay_bat_dau.date' => 'Ngày bắt đầu không đúng định dạng ngày',

            'ngay_ket_thuc.required' => 'Ngày kết thúc không được bỏ trống',
            'ngay_ket_thuc.date' => 'Ngày kết thúc không đúng định dạng ngày',
            'ngay_ket_thuc.after' => 'Thời gian ngày kết thúc phải sau ngày bắt đầu',

            'trang_thai.required' => 'Trạng thái không được bỏ trống',

            'mo_ta.required' => 'Mô tả không được bỏ trống',
        ];

        $validated = $request->validate($rules, $messages);
        // $request->validate([
        //     'ten_khuyen_mai' => 'required|string|max:255',
        //     'ma_giam_gia' => 'required|string|max:255|unique:khuyen_mais,ma_giam_gia,' . $khuyenMai->id,
        //     'loai_phong_id' => 'required|integer|exists:phongs,id',
        //     'loai_giam_gia' => 'required|boolean',
        //     'gia_tri_giam' => 'required|numeric',
        //     'so_luong' => 'required|integer',
        //     'ngay_bat_dau' => 'required|date',
        //     'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
        //     'trang_thai' => 'required|boolean',
        //     'mo_ta' => 'nullable|string',
        // ]);

        $khuyenMai->update($request->all());
        Toastr::success('Cập nhật khuyến mãi thành công', 'Thành công');
        return redirect()->route('admin.khuyen_mai.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KhuyenMai $khuyenMai, User $user): RedirectResponse
    {
        if (!Gate::allows('delete', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }
        //
        $khuyenMai->delete();

        // return back()->with('msg','Xóa thành công');
        return response(['trang_thai' => 'success']);
    }


}

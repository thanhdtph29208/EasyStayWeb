<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\ChiTietLoaiPhongDataTable;
use App\DataTables\LoaiPhongDataTable;
use App\Models\Loai_phong;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoaiPhongValidationRequest;
use App\Models\Anh_phong;
use App\Models\Phong;
use App\Models\User;
use App\Traits\ImageUploadTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Yajra\DataTables\DataTables;

use function Termwind\render;
use Brian2694\Toastr\Facades\Toastr;

class LoaiPhongController extends Controller
{
    use ImageUploadTrait;
    /**
     * Display a listing of the resource.
     */
    const PATH_UPLOAD = 'loai_phong';
    public function index(LoaiPhongDataTable $datatable)
    {
        $so_luong = Phong::select('Loai_phongs.ten', DB::raw('COUNT(phongs.id) as so_luong'))
            ->join('Loai_phongs', 'Phongs.loai_phong_id', '=', 'Loai_phongs.id')
            ->groupBy('Loai_phongs.ten')
            ->get();

        $phong_trong = Loai_phong::select('loai_phongs.*', DB::raw('COUNT(phongs.id) as phong_trong'))
            ->leftJoin('phongs', function ($join) {
                $join->on('loai_phongs.id', '=', 'phongs.loai_phong_id')
                    ->where('phongs.trang_thai', '=', '1');
            })
            ->groupBy('loai_phongs.id', 'loai_phongs.ten')
            ->get();

        return $datatable->with('so_luong', $so_luong)->with('phong_trong', $phong_trong)->render('admin.loai_phong.index');
    }
    public function search(Request $request, LoaiPhongDataTable $datatables)
    {
        $dataTableQuery = Loai_phong::query()->with('loai_phong');


        if ($request->filled('ten')) {
            $dataTableQuery->where('ten', 'like', '%' . $request->ten . '%');
        }


if ($request->filled('gia_min') && $request->filled('gia_max')) {

    $gia_min = (float) $request->gia_min;
    $gia_max = (float) $request->gia_max;


    $dataTableQuery->whereBetween('gia', [$gia_min, $gia_max]);
} elseif ($request->filled('gia_min')) {

    $gia_min = (float) $request->gia_min;
    $dataTableQuery->where('gia', '>=', $gia_min);
} elseif ($request->filled('gia_max')) {

    $gia_max = (float) $request->gia_max;
    $dataTableQuery->where('gia', '<=', $gia_max);
}









        if ($request->filled('trang_thai')) {
            $trang_thai = (int) $request->trang_thai;

            if ($trang_thai === 0) {

                $dataTableQuery->where('trang_thai', 0);
            } elseif ($trang_thai === 1) {

                $dataTableQuery->where('trang_thai', 1);
            } elseif ($trang_thai === 2) {

            }
        }



        $loai_phong = $dataTableQuery->get();

        $loai_phong->each(function ($item) use ($request) {
            $phong_trong = Phong::where('loai_phong_id', $item->id)->where('trang_thai', '1')->count();
            $item->phong_trong = $phong_trong;
        });


        return DataTables::of($loai_phong)
            ->addColumn('action', 'loaiphong.action')

            ->addColumn('so_luong', function ($query) {
                $so_luong = Phong::where('loai_phong_id', $query->id)->count();
                return $so_luong;
            })

            ->addColumn('anh', function ($query) {
                return "<img src='" . Storage::url($query->anh) . "' width='100px' alt='ảnh phòng'>";
            })
            ->addColumn('phong_trong', function ($query) {
                return $query->phong_trong;
            })
            ->addColumn('trang_thai', function ($query) {
                return $query->phong_trong == 0 ? "<span class='badge text-bg-danger'>Hết phòng</span>" : "<span class='badge text-bg-success'>Còn phòng</span>";
            })
            ->addColumn('action', function ($query) {
                $editBtn = "<a href='" . route('admin.loai_phong.edit', $query->id) . "' class='btn btn-primary'>
                <i class='bi bi-pen'></i>
                </a>";
                // $anhBtn = "<a href='" . route('admin.anh_phong.index',['loai_phong' =>  $query->id]) . "' class='btn btn-info ms-2'>
                // <i class='bi bi-image'></i>
                // </a>";

                // $detailBtn = "<a href='" . route('admin.loai_phong.show', $query->id) . "' class='btn btn-secondary ms-2'>
                // <i class='bi bi-card-list'></i>
                // </a>";
                $deleteBtn = "<a href='" . route('admin.loai_phong.destroy', $query->id) . "' class='btn btn-danger delete-item ms-2'>
                <i class='bi bi-archive'></i>
                </a>";
                // $phongBtn = "<a href='" . route('admin.phong.index',['loai_phong' =>  $query->id]) . "' class='btn btn-warning ms-2'>
                // <i class='bi bi-houses-fill'></i>
                // </a>";
                // $cmBtn =  "<a href='" . route('admin.danh_gia.index',['loai_phong' => $query->id]) . "' class='btn btn-dark ms-2'>
                // <i class='bi bi-chat-dots'></i>
                // </a>";

                $moreBtn = "
                <div class='dropdown d-inline ms-1'>
                    <button class='btn btn-secondary dropdown-toggle' type='button' data-bs-toggle='dropdown' aria-expanded='false'>
                    <i class='bi bi-list-ul'></i>
                    </button>
                    <ul class='dropdown-menu'>
                        <li><a class='dropdown-item' href='" . route('admin.anh_phong.index', ['loai_phong' => $query->id]) . "'>
                        <i class='bi bi-image'></i> Ảnh phòng
                        </a></li>
                        <li><a class='dropdown-item' href='" . route('admin.loai_phong.show', ['loai_phong' => $query->id]) . "'>
                        <i class='bi bi-card-list'></i> Chi tiết loại phòng
                        </a></li>
                        <li><a class='dropdown-item' href='" . route('admin.phong.index', ['loai_phong' => $query->id]) . "'>
                        <i class='bi bi-houses-fill'></i> Phòng
                        </a></li>
                        <li><a class='dropdown-item' href='" . route('admin.danh_gia.index', ['loai_phong' => $query->id]) . "'>
                        <i class='bi bi-chat-dots'></i> Đánh giá
                        </a></li>
                    </ul>
                </div>
                ";

                return $editBtn . $deleteBtn . $moreBtn ;
            })

            ->rawColumns(['so_luong', 'anh', 'phong_trong', 'trang_thai', 'action'])
            ->setRowId('id')
            ->make(true);
        }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.loai_phong.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user): RedirectResponse
    {
        if (!Gate::allows('create', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }
        $rules = [
            'ten' => 'required|max:255|unique:loai_phongs',
			'anh' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gia' => 'required|numeric|min:0',
            'gia_ban_dau' => 'nullable|numeric|min:0',
            'gioi_han_nguoi' => 'required|numeric|min:0',
            'so_luong' => 'nullable|numeric|min:0',
            'mo_ta_ngan' => 'required|max:255',
            'mo_ta_dai' => 'required|max:1000',
            'trang_thai' => 'required'
        ];

        $messages = [
            'ten.required' => 'Tên không được để trống',
            'ten.max' => 'Tên không được vượt quá 255 ký tự',
            'ten.unique' => 'Tên loại phòng đã tồn tại',

            'anh.required' => 'Ảnh không được để trống',
            'anh.image' => 'Ảnh không đúng định dạng',
            'anh.mimes' => 'Ảnh không đúng định dạng',
            'anh.max' => 'Ảnh quá kích thước 2048kb',

            'gia.required' => 'Giá không được để trống',
            'gia.numeric' => 'Giá phải là 1 số',
            'gia.min' => 'Giá phải là 1 số dương',

            // 'gia_ban_dau.required' => 'Giá ban đầu không được để trống',
            'gia_ban_dau.numeric' => 'Giá ban đầu phải là 1 số',
            'gia_ban_dau.min' => 'Giá ban đầu phải là 1 số dương',

            'gioi_han_nguoi.required' => 'Giới hạn người không được để trống',
            'gioi_han_nguoi.numeric' => 'Giới hạn người phải là 1 số',
            'gioi_han_nguoi.min' => 'Giới hạn người phải là 1 số dương',

            // 'so_luong.required' => 'Số lượng không được để trống',
            'so_luong.numeric' => 'Số lượng phải là 1 số',
            'so_luong.min' => 'Số lượng phải là 1 số dương',

            'mo_ta_ngan.required' => 'Mô tả ngắn không được để trống',
            'mo_ta_ngan.max' => 'Mô tả ngắn không được vượt quá 255 ký tự',

            'mo_ta_dai.required' => 'Mô tả dài không được để trống',
            'mo_ta_dai.max' => 'Mô tả dài không được vượt quá 1000 ký tự',

            'trang_thai.required' => 'Trạng thái không được để trống',
        ];

        $validated = $request->validate($rules, $messages);

        // $request->validate([
        //     'ten' => 'required|unique:loai_phongs',
        //     'anh' => 'nullable', 'image',
        //     'gia' => 'required',
        //     'gia_ban_dau' => 'nullable',
        //     'gioi_han_nguoi' => 'required',
        //     'so_luong' => 'required',
        //     'mo_ta_ngan' => 'required',
        //     'mo_ta_dai' => 'required',
        //     'trang_thai' => 'required',

        // ]);


        $data = $request->except('anh');
        if ($request->hasFile('anh')) {
            $data['anh'] = Storage::put(self::PATH_UPLOAD, $request->file('anh'));
        }
        Loai_phong::query()->create($data);
        Toastr::success('Thêm loại phòng thành công', 'Thành công');
        return redirect()->route('admin.loai_phong.index');
    }

    /**
     * Display the specified resource.
     */
    // public function show(ChiTietLoaiPhongDataTable $datatable)
    // {
    //     return $datatable->render('admin.loai_phong.show');
    // }

    public function show(Loai_phong $loai_phong)
    {
        $so_luong = Phong::select('Loai_phongs.ten', DB::raw('COUNT(phongs.id) as so_luong'))
            ->join('Loai_phongs', 'Phongs.loai_phong_id', '=', 'Loai_phongs.id')
            ->groupBy('Loai_phongs.ten')
            ->get();
        return view('admin.loai_phong.show', compact('loai_phong','so_luong'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Loai_phong $loai_phong)
    {

        return view('admin.loai_phong.edit', compact('loai_phong'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Loai_phong $loai_phong, User $user): RedirectResponse
    {
        if (!Gate::allows('update', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }

        $rules = [
            'ten' => 'required|max:255|unique:loai_phongs,ten,' . $loai_phong->id,
            'anh' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'gia' => 'required|numeric|min:0',
            'gia_ban_dau' => 'nullable|numeric|min:0',
            'gioi_han_nguoi' => 'required|numeric|min:0',
            'so_luong' => 'nullable|numeric|min:0',
            'mo_ta_ngan' => 'required|max:255',
            'mo_ta_dai' => 'required|max:1000',
            'trang_thai' => 'required'
        ];

        $messages = [
            'ten.required' => 'Tên không được để trống',
            'ten.max' => 'Tên không được vượt quá 255 ký tự',
            'ten.unique' => 'Tên loại phòng đã tồn tại',

            // 'anh.required' => 'Ảnh không được để trống',
            'anh.image' => 'Ảnh không đúng định dạng',
            'anh.mimes' => 'Ảnh không đúng định dạng',
            'anh.max' => 'Ảnh quá kích thước 2048kb',

            'gia.required' => 'Giá không được để trống',
            'gia.numeric' => 'Giá phải là 1 số',
            'gia.min' => 'Giá phải là 1 số dương',

            // 'gia_ban_dau.required' => 'Giá ban đầu không được để trống',
            'gia_ban_dau.numeric' => 'Giá ban đầu phải là 1 số',
            'gia_ban_dau.min' => 'Giá ban đầu phải là 1 số dương',

            'gioi_han_nguoi.required' => 'Giới hạn người không được để trống',
            'gioi_han_nguoi.numeric' => 'Giới hạn người phải là 1 số',
            'gioi_han_nguoi.min' => 'Giới hạn người phải là 1 số dương',

            // 'so_luong.required' => 'Số lượng không được để trống',
            'so_luong.numeric' => 'Số lượng phải là 1 số',
            'so_luong.min' => 'Số lượng phải là 1 số dương',

            'mo_ta_ngan.required' => 'Mô tả ngắn không được để trống',
            'mo_ta_ngan.max' => 'Mô tả ngắn không được vượt quá 255 ký tự',

            'mo_ta_dai.required' => 'Mô tả dài không được để trống',
            'mo_ta_dai.max' => 'Mô tả dài không được vượt quá 1000 ký tự',

            'trang_thai.required' => 'Trạng thái không được để trống',
        ];

        $validated = $request->validate($rules, $messages);

        // $request->validate([
        //     'ten' => 'required|unique:loai_phongs,ten,' . $loai_phong->id,
        //     'anh' => 'nullable', 'image',
        //     'gia' => 'required',
        //     'gia_ban_dau' => 'nullable',
        //     'gioi_han_nguoi' => 'required',
        //     'so_luong' => 'required',
        //     'mo_ta_ngan' => 'required',
        //     'mo_ta_dai' => 'required',
        //     'trang_thai' => 'required',

        // ]);
        $data = $request->except('anh');
        if ($request->hasFile('anh')) {
            $data['anh'] = Storage::put(self::PATH_UPLOAD, $request->file('anh'));
        }
        $oldAnh = $loai_phong->anh;
        if ($request->hasFile('anh') && Storage::exists($oldAnh)) {
            Storage::delete($oldAnh);
        }
        $loai_phong->update($data);

        // if($request->fails()){
        // 	$errors = $request->messages()->all();

        // 	foreach($errors as $error){
        // 		Toastr::error($error, 'Lỗi');
        // 	}
        // }

        // Toastr::success('Cập nhật sản phẩm thành công', 'Thành công');
        toastr()->success('Thành công !','Thông báo');
        return redirect()->route('admin.loai_phong.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, User $user)
    {
        if (!Gate::allows('delete', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }
        $loai_phong = Loai_phong::findOrFail($id);

        $this->deleteImage($loai_phong->anh);
        $anh_phong = Anh_phong::where('loai_phong_id', $loai_phong->id)->get();
        foreach ($anh_phong as $anh) {
            $this->deleteImage($anh->anh);
            $anh->delete();
        }
        $loai_phong->delete();
        return response(['trang_thai' => 'success']);

        // $loai_phong->delete();
        // if(Storage::exists($loai_phong->anh)){
        //     Storage::delete($loai_phong->anh);
        // }
        // return back()->with('msg','Xóa thành công');
    }


    public function changeStatus(Request $request)
    {
        $loai_phong = Loai_phong::findOrFail($request->id);
        $loai_phong->trang_thai = $request->trang_thai == 'true' ? 0 : 1;
        $loai_phong->save();
        return response(['message' => 'Trạng thái cập nhật thành công']);
    }
}

<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\PhongDataTable;
use App\Models\Phong;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Loai_phong;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Brian2694\Toastr\Facades\Toastr;


class PhongController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    const PATH_VIEW = 'admin.phong.';

    public function index(Request $request, PhongDataTable $datatables)
    {
        $loai_phong = Loai_phong::findOrFail($request->loai_phong);
        return $datatables->render('admin.phong.index', compact('loai_phong'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $loai_phong = Loai_phong::query()->pluck('ten','id')->toArray();
        // return view(self::PATH_VIEW . __FUNCTION__, compact('loai_phong'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user): RedirectResponse
    {
        if (! Gate::allows('create', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }

        $rules = [
            'ten_phong' => 'required|unique:phongs',
            'mo_ta' => 'nullable|max:255',
            'trang_thai' => 'required',
        ];

        $messages = [
            'ten_phong.required' => 'Tên phòng không được bỏ trống',
            'ten_phong.unique' => 'Tên phòng đã tồn tại',

            'mo_ta.max' => 'Mô tả tối đa 255 ký tự',
            
            'trang_thai.required' => 'Trạng thái không được bỏ trống',
        ];

		$validated = $request->validate($rules, $messages);


        // $request->validate([
        //     'ten_phong' => 'required|unique::phongs',
        //     'loai_phong_id' => [
        //         Rule::exists('phongs','id')
        //     ],
        //     'mo_ta' => 'required',
        //     'trang_thai' => 'required',
        //     // 'trang_thai' => [
        //     //     Rule::in([1,0])
        //     // ],
        // ]);
        Phong::query()->create($request->all());
        Toastr::success('Thêm mới phòng thành công', 'Thành công');
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Phong $phong)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Phong $phong)
    {
        $loai_phong = Loai_phong::query()->pluck('ten','id')->toArray();
        // dd($phong);
        return view(self::PATH_VIEW . __FUNCTION__, compact('loai_phong','phong'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Phong $phong, User $user): RedirectResponse
    {
        if (! Gate::allows('update', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }
        $rules = [
            'ten_phong' => 'required|unique:phongs,ten_phong,' . $phong->id,
            'mo_ta' => 'nullable|max:255',
            'trang_thai' => 'required',
        ];

        $messages = [
            'ten_phong.required' => 'Tên phòng không được bỏ trống',
            'ten_phong.unique' => 'Tên phòng đã tồn tại',

            'mo_ta.max' => 'Mô tả tối đa 255 ký tự',
            
            'trang_thai.required' => 'Trạng thái không được bỏ trống',
        ];
        $validated = $request->validate($rules, $messages);

        // $request->validate([
        //     'ten_phong' => 'required|unique::phongs,ten_phong,' . $phong->id,
        //     'loai_phong_id' => [
        //         Rule::exists('phongs','id')
        //     ],
        //     'mo_ta' => 'required',
        //     'trang_thai' => 'required',
        //     'trang_thai' => [
        //         Rule::in([1,0])
        //     ],
        // ]);
        $phong->update($request->all());
        Toastr::success('Cập nhật phòng thành công', 'Thành công');
        return back()->with('msg','Cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Phong $phong, User $user)
    {
        if (! Gate::allows('delete', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }
        $phong->delete();
        return response(['trang_thai' => 'success']);
    }
}

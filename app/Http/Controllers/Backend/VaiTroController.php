<?php

namespace App\Http\Controllers\Backend;
use App\DataTables\VaiTroDataTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VaiTro;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Brian2694\Toastr\Facades\Toastr;

class VaiTroController extends Controller
{
    //
    const PATH_VIEW = 'admin.vai_tro.';
    public function index(VaiTroDataTable $datatable)
    {
        return $datatable->render('admin.vai_tro.index');
    }
    public function create()
    {
        return view(self::PATH_VIEW . __FUNCTION__);
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
           'ten_chuc_vu' => 'required|max:255|unique:vai_tros',
           'mo_ta' => 'required|max:255',
           'trang_thai' => 'required',
        ];

        $messages = [
            'ten_chu_vu.required' => 'Tên chức vụ không được để trống',
            'ten_chu_vu.max' => 'Tên chức vụ không được vượt quá 255 ký tự',
            'ten_chu_vu.unique' => 'Tên chức vụ đã tồn tại',

            'mo_ta.required' => 'Mô tả không được để trống',
            'mo_ta.max' => 'Mô tả không được vượt quá 255 ký tự',

            'trang_thai.required' => 'Trạng thái không được để trống',
        ];

        $validated = $request->validate($rules, $messages);

        VaiTro::query()->create($request->all());
        Toastr::success('Thêm vai trò thành công', 'Thành công');
        return redirect()->route('admin.vai_tro.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(VaiTro $vai_tro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(VaiTro $vai_tro)
    {
        return view(self::PATH_VIEW . __FUNCTION__, compact('vai_tro'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, VaiTro $vai_tro, User $user): RedirectResponse
    {
        if (! Gate::allows('update', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }
        $rules = [
            'ten_chuc_vu' => 'required|max:255|unique:vai_tros,ten_chuc_vu,' . $vai_tro->id,
            'mo_ta' => 'required|max:255',
            'trang_thai' => 'required',
         ];

         $messages = [
             'ten_chu_vu.required' => 'Tên chức vụ không được để trống',
             'ten_chu_vu.max' => 'Tên chức vụ không được vượt quá 255 ký tự',
             'ten_chu_vu.unique' => 'Tên chức vụ đã tồn tại',

             'mo_ta.required' => 'Mô tả không được để trống',
             'mo_ta.max' => 'Mô tả không được vượt quá 255 ký tự',

             'trang_thai.required' => 'Trạng thái không được để trống',
         ];

        $validated = $request->validate($rules, $messages);
        $vai_tro->update($request->all());
        Toastr::success('Cập nhật vai trò thành công', 'Thành công');
        return redirect()->route('admin.vai_tro.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(VaiTro $vai_tro, User $user)
    {
        if (! Gate::allows('delete', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }
        $vai_tro->delete();
        return back()->with('msg', 'Xóa thành công');
    }

}

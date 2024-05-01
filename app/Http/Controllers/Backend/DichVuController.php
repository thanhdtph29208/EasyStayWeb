<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\DichVuDataTable;
use App\Models\DichVu;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Brian2694\Toastr\Facades\Toastr;

class DichVuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, DichVuDataTable $datatable)
    {
        return $datatable->render('admin.dich_vu.index');
        // $dichVuList = DichVu::all();
        // return view('admin.dich_vu.index', compact('dichVuList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('admin.dich_vu.create');
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
            'ten_dich_vu' => 'required|max:255|unique:dich_vus',
            'gia' => 'required|numeric|min:0',
            'so_luong' => 'required|numeric|min:0',
            'trang_thai' => 'required',

        ];

        $messages = [
            'ten_dich_vu.required' => 'Tên dịch vụ không được để trống',
            'ten_dich_vu.max' => 'Tên dịch vụ không được vượt quá 255 ký tự',
            'ten_dich_vu.unique' => 'Tên dịch vụ đã tồn tại',

            'gia.required' => 'Giá không được để trống',
            'gia.numeric' => 'Giá phải là 1 số',
            'gia.min' => 'Giá phải là 1 số dương',

            'so_luong.required' => 'Số lượng không được để trống',
            'so_luong.numeric' => 'Số lượng phải là 1 số',
            'so_luong.min' => 'Số lượng phải là 1 số dương',

            'trang_thai.required' => 'Trạng thái không được để trống',
        ];

        $validated = $request->validate($rules, $messages);

        // $request->validate([
        //     'ten_dich_vu' => 'required',
        //     'gia' => 'required|numeric',
        //     'so_luong' => 'required|numeric',
        //     'trang_thai' => 'required|numeric',
        // ]);


        DichVu::create($data);

        Toastr::success('Thêm dịch vụ thành công', 'Thành công');
        return redirect()->route('admin.dich_vu.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(DichVu $dichVu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DichVu $dichVu)
    {
        //
        return view('admin.dich_vu.edit', compact('dichVu'));
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
        $dichVu = DichVu::findOrFail($id);

        $rules = [
            'ten_dich_vu' => 'required|max:255|unique:dich_vus,ten_dich_vu,' . $dichVu->id,
            'gia' => 'required|numeric|min:0',
            'so_luong' => 'required|numeric|min:0',
            'trang_thai' => 'required',

        ];

        $messages = [
            'ten_dich_vu.required' => 'Tên dịch vụ không được để trống',
            'ten_dich_vu.max' => 'Tên dịch vụ không được vượt quá 255 ký tự',
            'ten_dich_vu.unique' => 'Tên dịch vụ đã tồn tại',

            'gia.required' => 'Giá không được để trống',
            'gia.numeric' => 'Giá phải là 1 số',
            'gia.min' => 'Giá phải là 1 số dương',

            'so_luong.required' => 'Số lượng không được để trống',
            'so_luong.numeric' => 'Số lượng phải là 1 số',
            'so_luong.min' => 'Số lượng phải là 1 số dương',

            'trang_thai.required' => 'Trạng thái không được để trống',
        ];

        $validated = $request->validate($rules, $messages);

        // $data = $request->validate([
        //     'ten_dich_vu' => 'required',
        //     'gia' => 'required|numeric',
        //     'so_luong' => 'required|numeric',
        //     'trang_thai' => 'required|numeric',
        // ]);

        $dichVu->update($request->all());

        Toastr::success('Cập nhật dịch vụ thành công', 'Thành công');
        return redirect()->route('admin.dich_vu.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DichVu $dichVu, User $user): RedirectResponse
    {
        if (!Gate::allows('delete', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }
        //
        $dichVu->delete();

        return response(['trang_thai' => 'success']);
    }
}

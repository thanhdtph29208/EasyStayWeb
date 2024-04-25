<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\BaiVietDataTable;
use App\Models\Bai_viet;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Validation\Validator;

class BaiVietController extends Controller
{
    const PATH_VIEW = 'admin.bai_viet.';
    const PATH_UPLOAD = 'bai_viet';

    public function index( Request $request, BaiVietDataTable $datatable )
    {
        return $datatable->render('admin.bai_viet.index');
        // $data = Bai_viet::latest()->paginate(5);
        // return view(self::PATH_VIEW . 'index', compact('data'));
    }

    public function create()
    {
        return view(self::PATH_VIEW . 'create');
    }

    public function upload(Request $request){

        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName(); $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
            $request->file("upload")->move (public_path("images"), $fileName);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('images/'.$fileName);
            $msg = 'Image uploaded successfully';
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
            }
       return false;
    }

    public function store(Request $request , User $user): RedirectResponse
    {
        $rules = [
            'tieu_de' => 'required|max:255',
			'anh' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'mo_ta_ngan' => 'required|max:225',
            'noi_dung_create' => 'required',
            'trang_thai' => [
                Rule::in([
                    Bai_viet::XUAT_BAN,
                    Bai_viet::NHAP
                ])
            ],
        ];

        $messages = [
            'tieu_de.required' => 'Tiêu đề không được bỏ trống',
            'tieu_de.max' => 'Tiêu đề tối đa 255 ký tự',

            'anh.required' => 'Ảnh không được để trống',
            'anh.image' => 'Ảnh không đúng định dạng',
            'anh.mimes' => 'Ảnh không đúng định dạng',
            'anh.max' => 'Ảnh quá kích thước 2048kb',

            'mo_ta_ngan.required' => 'Mô tả ngắn không được bỏ trống',
            'mo_ta_ngan.max' => 'Mô tả ngắn tối đa 255 ký tự',

            'noi_dung_create.required' => 'Nội dung không được bỏ trống',

        ];

        $validated = $request->validate($rules, $messages);


        // $request->validate([
        //     'tieu_de' => 'required|max:225',
        //     'anh' => 'required|nullable|image|max:1080',
        //     'mo_ta_ngan' => 'required|max:225',
        //     'noi_dung' => 'required',
        //     'trang_thai' => [
        //         Rule::in([
        //             Bai_viet::XUAT_BAN,
        //             Bai_viet::NHAP
        //         ])
        //     ],
        // ]);

        $data = $request->except('anh');

        if ($request->hasFile('anh')) {
            $data['anh'] = $request->file('anh')->store(self::PATH_UPLOAD, 'public');
        }

        $data['noi_dung'] = $request->noi_dung_create;

        Bai_viet::query()->create($data);

        Toastr::success('Thêm bài viết thành công','Thành công');
        return redirect()->route('admin.bai_viet.index');

        // return back()->with('msg', 'Thêm thành công');
    }

    public function edit(Bai_viet $bai_viet)
    {
        return view(self::PATH_VIEW . 'edit', compact('bai_viet'));
    }

    public function update(Request $request, Bai_viet $bai_viet , User $user)
    {
        $rules = [
            'tieu_de' => 'required|max:255',
			'anh' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'mo_ta_ngan' => 'required|max:225',
            'noi_dung' => 'required',
            'trang_thai' => [
                Rule::in([
                    Bai_viet::XUAT_BAN,
                    Bai_viet::NHAP
                ])
            ],
        ];

        $messages = [
            'tieu_de.required' => 'Tiêu đề không được bỏ trống',
            'tieu_de.max' => 'Tiêu đề tối đa 255 ký tự',

            'anh.required' => 'Ảnh không được để trống',
            'anh.image' => 'Ảnh không đúng định dạng',
            'anh.mimes' => 'Ảnh không đúng định dạng',
            'anh.max' => 'Ảnh quá kích thước 2048kb',

            'mo_ta_ngan.required' => 'Mô tả ngắn không được bỏ trống',
            'mo_ta_ngan.max' => 'Mô tả ngắn tối đa 255 ký tự',

            'noi_dung.required' => 'Nội dung không được bỏ trống',

        ];

        $validated = $request->validate($rules, $messages);

        // $request->validate([
        //     'tieu_de' => 'required|max:225',
        //     'anh' => 'required|image|max:1080',
        //     'mo_ta_ngan' => 'required|nullable|max:225',
        //     'noi_dung' => 'required',
        //     'trang_thai' => [
        //         Rule::in([
        //             Bai_viet::XUAT_BAN,
        //             Bai_viet::NHAP
        //         ])
        //     ],
        // ]);

        $data = $request->except('anh');

        if ($request->hasFile('anh')) {
            $data['anh'] = $request->file('anh')->store(self::PATH_UPLOAD, 'public');

            if (Storage::exists($bai_viet->anh)) {
                Storage::delete($bai_viet->anh);
            }
        }

        $bai_viet->update($data);
        Toastr::success('Cập nhật bài viết thành công','Thành công');
        return redirect()->route('admin.bai_viet.index');
        // Toastr::error('Cập nhật không thành công', 'failed');
        // return back()->with('msg', 'Cập nhật thành công');
    }



    public function destroy(Bai_viet $bai_viet , User $user): RedirectResponse
    {
        if (! Gate::allows('delete', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }
        $bai_viet->delete();

        if (Storage::exists($bai_viet->anh)) {
            Storage::delete($bai_viet->anh);
        }
        return response(['trang_thai' => 'success']);
    }
}

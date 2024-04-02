<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\KhachSanDataTable;
use App\Models\Hotel;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Brian2694\Toastr\Facades\Toastr;

class hotelController extends Controller
{


    const PATH_VIEW = 'admin.khach_san.';
    const PATH_UPLOAD = 'khach_san';

    public function index(Request $request, KhachSanDataTable $datatables)
    {
        return $datatables->render('admin.khach_san.index');
        // $data = Hotel::paginate();
        // return view(self::PATH_VIEW . 'index', compact('data'));
    }

    public function edit(Hotel $khach_san)
    {
        return view(self::PATH_VIEW . 'edit', compact('khach_san'));
    }

    public function update(Request $request, Hotel $khach_san, User $user): RedirectResponse
    {
        if (!Gate::allows('update', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }

        $rules = [
            'ten' => 'required|max:255',
            'logo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'so_dien_thoai' => ['required','regex:/^0\d{9,10}$/'],
            'email' => 'required|email',
            'mo_ta' => 'nullable',
            'dia_chi' => 'required|max:255',
            'facebook' => 'required',
            'instagram' => 'required',
            'twitter' => 'required',
        ];

        $messages = [
            'ten.requied' => 'Tên không được bỏ trống',
            
            // 'logo.required' => 'Ảnh không được để trống',
            'logo.image' => 'Ảnh không đúng định dạng',
            'logo.mimes' => 'Ảnh không đúng định dạng',
            'logo.max' => 'Ảnh quá kích thước 2048kb',

            'so_dien_thoai.required' => 'Số điện thoại không được bỏ trống',
            'so_dien_thoai.regex' => 'Số điện thoại không hợp lệ',

            'email.required' => 'Email không được bỏ trống',
            'email.email' => 'Email không hợp lệ',

            'dia_chi.required' => 'Địa chỉ không được bỏ trống',
            'dia_chi.max' => 'Địa chỉ không được quá 255 ký tự',

            'facebook.required' => "Facebook không được bỏ trống",

            'instagram.required' => 'Instagram không được bỏ trống',

            'twitter.required' => 'Twitter không được bỏ trống',
        ];

        $validated = $request->validate($rules, $messages);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            $data['logo'] = Storage::put(self::PATH_UPLOAD, $request->file('logo'));

            $oldLogo = $khach_san->logo;
            if ($request->hasFile('logo') && (Storage::exists($oldLogo))) {
                Storage::delete($oldLogo);
            }
        }

        $khach_san->update($data);
        Toastr::success('Cập nhật thông tin khách sạn thành công', 'Thành công');

        return redirect()->route('admin.khach_san.index');
    }
}

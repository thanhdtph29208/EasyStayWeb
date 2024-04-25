<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\BinhLuanDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\comments;

class BinhLuanController extends Controller
{
    const PATH_VIEW = 'admin.binh_luan.';

    public function index( Request $request, BinhLuanDataTable $datatable )
    {
        return $datatable->with('id', $request->id)->render('admin.binh_luan.index');
        // $data = Bai_viet::latest()->paginate(5);
        // return view(self::PATH_VIEW . 'index', compact('data'));
    }

    public function delete($id){
        comments::find($id)->delete();
        return response(['trang_thai' => 'success']);
    }


    public function destroy(comments $binh_luan , User $user): RedirectResponse
    {
        if (! Gate::allows('delete', $user)) {
            return Redirect::back()->with('error', 'Bạn không có quyền thực hiện thao tác này.');
        }
        $binh_luan->delete();

        return response(['trang_thai' => 'success']);
    }
}

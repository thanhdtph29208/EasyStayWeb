<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Brian2694\Toastr\Facades\Toastr;

class ChangePasswordController extends Controller
{
    /**
     * Hiển thị form thay đổi mật khẩu.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showChangePasswordForm()
    {
        return view('client.pages.password-change');

    }

    /**
     * Cập nhật mật khẩu của người dùng.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|different:current_password',
            'new_password_confirmation' => 'required|same:new_password',
        ]);

        $user = Auth::user();

       
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.'])->withInput();
        }

       
        $user->password = Hash::make($request->new_password);
        $user->save();
       
        Auth::logout();

    return redirect()->route('login')->with('success', 'Mật khẩu đã được cập nhật thành công. Vui lòng đăng nhập lại.');
    }
}

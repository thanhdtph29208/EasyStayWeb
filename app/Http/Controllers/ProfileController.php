<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{

    public function index(){ 
        $users = User::all();
        return view('client.pages.hoso', compact('users'));
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */

    public function update(Request $request)
    {
        $user = $request->user();
    
        if ($request->hasFile('anh')) {
            $anhPath = $request->anh->store('public/anh');
            $user->anh = str_replace('public', 'storage', $anhPath);
        }
    
        $user->update($request->all());
    
        Session::flash('success', 'Cập nhật thông tin thành công');
        // toastr()->success('Thành công !','Cập nhật thành công');
    
        return redirect()->back();
    }
    

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

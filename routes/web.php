<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\AnhPhongController;
use App\Http\Controllers\Backend\BaiVietController;
use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\DanhGiaController;
use App\Http\Controllers\Backend\LoaiPhongController;
use App\Http\Controllers\Backend\PhongController;
use App\Http\Controllers\Backend\hotelController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Backend\ChiTietDonDatController;
use App\Http\Controllers\Backend\DatPhongController;
use App\Http\Controllers\Backend\VaiTroController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\ExportController;
use App\Http\Controllers\Backend\DonDatController;
use App\Http\Controllers\Backend\KhuyenMaiController;
use App\Http\Controllers\Backend\DichVuController;
use App\Http\Controllers\Frontend\ChiTietLoaiPhongController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
// 	return view('client.layouts.master');
// });

Route::get('/', [App\Http\Controllers\Frontend\HomeController::class, 'home'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

 Route::get('chi_tiet_loai_phong/{$id}', [ChiTietLoaiPhongController::class,'detail'])->name('client.pages.chitiet');
 Route::get('loai_phong',[ChiTietLoaiPhongController::class, 'allRoom'])->name('clients.pages.loai_phong');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
   
});

require __DIR__ . '/auth.php';


Route::prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::resource('loai_phong', LoaiPhongController::class);
        Route::resource('phong', PhongController::class);
        Route::resource('anh_phong', AnhPhongController::class);
        Route::resource('khach_san', hotelController::class);
        Route::resource('bai_viet', BaiVietController::class);
        Route::resource('user', RegisteredUserController::class);
        // Route::resource('danh_gia',DanhGiaController::class);
        Route::resource('banners', BannerController::class);
		Route::resource('danh_gia', DanhGiaController::class);
        Route::resource('vai_tro', VaiTroController::class);
        Route::resource('dat_phong', DatPhongController::class);
     
        Route::put('loai_phong/change-status', [LoaiPhongController::class, 'changeStatus'])->name('loai_phong.change-status');
        Route::get('exportUser', [ExportController::class, 'exportUser']);
        
        Route::resource('khuyen_mai', KhuyenMaiController::class);
        Route::resource('dich_vu', DichVuController::class);


    });


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

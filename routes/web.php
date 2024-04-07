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
use App\Http\Controllers\Backend\DatPhongController;
use App\Http\Controllers\Backend\VaiTroController;
use App\Http\Controllers\Backend\ChiTietDatPhongController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\ExportController;
use App\Http\Controllers\Backend\KhuyenMaiController;
use App\Http\Controllers\Backend\DichVuController;
use App\Http\Controllers\Backend\ThongKeController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\ChiTietLoaiPhongController;
use App\Http\Controllers\Frontend\HoSoController;
use App\Http\Controllers\Frontend\KiemTraPhongController;
use App\Http\Controllers\Frontend\LienHeController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\LichSuDatPhongController;
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



Route::get('ho_so', [ProfileController::class, 'index'])->name('client.pages.hoso');
Route::get('lich_su_dat_phong', [LichSuDatPhongController::class, 'userBookingHistory'])->name('client.pages.lich_su_dat_phong');



Route::get('chi_tiet_loai_phong/{id}', [ChiTietLoaiPhongController::class, 'detail'])->name('client.pages.loai_phong.chitietloaiphong');
Route::get('loai_phong', [ChiTietLoaiPhongController::class, 'allRoom'])->name('clients.pages.loai_phong.loai_phong');

Route::get('tin_tuc', [App\Http\Controllers\Frontend\BaiVietFEController::class, 'index'])->name('client.pages.bai_viet.index');
Route::get('chi_tiet_tin_tuc/{id}', [App\Http\Controllers\Frontend\BaiVietFEController::class, 'show'])->name('client.pages.bai_viet.show');

Route::get('lien_he', [LienHeController::class, 'contact'])->name('client.pages.lien_he');

// Route::post('kiem_tra_phong', [KiemTraPhongController::class, 'checkPhong'])->name('kiem_tra_phong');
Route::match(['get', 'post'], 'kiem_tra_phong', [KiemTraPhongController::class, 'checkPhong'])->name('kiem_tra_phong');

Route::post('them_gio_hang', [CartController::class, 'addToCart'])->name('them_gio_hang');
Route::get('chi_tiet_gio_hang',[CartController::class, 'cartDetail'])->name('chi_tiet_gio_hang');
Route::get('cart-count', [CartController::class, 'getCartCount'])->name('cart-count');
Route::get('chi_tiet_gio_hang/xoa_loai_phong/{rowId}', [CartController::class, 'removeRoom'])->name('chi_tiet_gio_hang.xoa_loai_phong');
Route::post('chi_tiet_gio_hang/them_phong', [CartController::class, 'updateRoomQuantity'])->name('chi_tiet_gio_hang.them_phong');
Route::get('coupon-calc', [CartController::class, 'couponCalc'])->name('coupon-calc');
Route::get('clear-cart', [CartController::class, 'clearCart'])->name('clear-cart');


// thanh toán 
Route::post('/vnpay_payment', [CheckoutController::class, 'vnpay_payment']);


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('client.pages.password-change');
    Route::put('/change-password', [ChangePasswordController::class, 'updatePassword'])->name('client.pages.password-update');
    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout');
    
    Route::get('pay', [CheckoutController::class, 'pay'])->name('pay');
    Route::get('pay/success', [CheckoutController::class, 'checkoutSuccess'])->name('pay-success');
    Route::get('pay/success1', [CheckoutController::class, 'checkoutSuccess1'])->name('pay-success1');

    

  
});


require __DIR__ . '/auth.php';


    Route::middleware(['auth','verified','block.user'])->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::resource('dashboard', ThongKeController::class);

        Route::resource('loai_phong', LoaiPhongController::class);
        Route::resource('phong', PhongController::class);
        Route::resource('anh_phong', AnhPhongController::class);
        Route::resource('khach_san', hotelController::class);
        Route::resource('bai_viet', BaiVietController::class);
        Route::resource('user', RegisteredUserController::class);
        Route::resource('banners', BannerController::class);
        // Route::resource('danh_gia', DanhGiaController::class);
        Route::resource('vai_tro', VaiTroController::class);
        Route::resource('dat_phong', DatPhongController::class);
        Route::resource('chi_tiet_dat_phong', ChiTietDatPhongController::class);
        Route::put('loai_phong/change-status', [LoaiPhongController::class, 'changeStatus'])->name('loai_phong.change-status');
        Route::get('exportUser', [ExportController::class, 'exportUser'])->name('exportUser');
        Route::get('exportHoaDon', [ExportController::class, 'exportHoaDon'])->name('exportHoaDon');

        Route::put('searchKhuyenMai', [DatPhongController::class, 'searchKhuyenMai'])->name('searchKhuyenMai');
        Route::resource('khuyen_mai', KhuyenMaiController::class);
        Route::resource('dich_vu', DichVuController::class);

        Route::resource('lien_he', LienHeController::class);
    });

Route::middleware(['auth', 'verified'])->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::resource('danh_gia', DanhGiaController::class);
    });

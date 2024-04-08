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
Route::get('ho_so', [ProfileController::class, 'index'])->name('client.pages.hoso'); // Hồ sơ
Route::get('chi_tiet_loai_phong/{id}', [ChiTietLoaiPhongController::class, 'detail'])->name('client.pages.loai_phong.chitietloaiphong'); // chi tiết loại phòng
Route::get('loai_phong', [ChiTietLoaiPhongController::class, 'allRoom'])->name('clients.pages.loai_phong.loai_phong'); // loại phòng
Route::get('tin_tuc', [App\Http\Controllers\Frontend\BaiVietFEController::class, 'index'])->name('client.pages.bai_viet.index'); // bài viết
Route::get('chi_tiet_tin_tuc/{id}', [App\Http\Controllers\Frontend\BaiVietFEController::class, 'show'])->name('client.pages.bai_viet.show'); // tin tức
Route::get('lien_he', [LienHeController::class, 'contact'])->name('client.pages.lien_he'); // liên hệ
Route::match(['get', 'post'], 'kiem_tra_phong', [KiemTraPhongController::class, 'checkPhong'])->name('kiem_tra_phong'); // kiểm tra phòng
Route::post('them_gio_hang', [CartController::class, 'addToCart'])->name('them_gio_hang'); // giỏ hàng
Route::get('chi_tiet_gio_hang',[CartController::class, 'cartDetail'])->name('chi_tiet_gio_hang'); // chi tiết giỏ hàng
Route::get('cart-count', [CartController::class, 'getCartCount'])->name('cart-count'); // cart
Route::get('chi_tiet_gio_hang/xoa_phong/{rowId}', [CartController::class, 'removeRoom'])->name('chi_tiet_gio_hang.xoa_phong'); // chi tiết xóa giỏ hàng
Route::post('chi_tiet_gio_hang/them_phong', [CartController::class, 'updateRoomQuantity'])->name('chi_tiet_gio_hang.them_phong');// chi tiết thêm giỏ hàng
Route::get('coupon-calc', [CartController::class, 'couponCalc'])->name('coupon-calc');
Route::get('/vnpay_payment', [CheckoutController::class, 'vnpay_payment'])->name('vnpay_payment');// thanh toán bằng vnpay
Route::get('/momo_payment', [CheckoutController::class, 'momo_payment'])->name('momo_payment'); // thanh toán bằng momo
Route::get('pay', [CheckoutController::class, 'pay'])->name('pay');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/change-password', [ChangePasswordController::class, 'showChangePasswordForm'])->name('client.pages.password-change');
    Route::put('/change-password', [ChangePasswordController::class, 'updatePassword'])->name('client.pages.password-update');
    Route::get('checkout', [CheckoutController::class, 'index'])->name('checkout');
});


require __DIR__ . '/auth.php';
    Route::middleware(['auth','verified','block.user'])->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::resource('dashboard', ThongKeController::class); // Thống kê
        Route::resource('loai_phong', LoaiPhongController::class); // Loại phòng
        Route::resource('phong', PhongController::class); // Phòng
        Route::resource('anh_phong', AnhPhongController::class); // Ảnh phòng
        Route::resource('khach_san', hotelController::class); // Hotel
        Route::resource('bai_viet', BaiVietController::class); // Bài viết
        Route::resource('user', RegisteredUserController::class); // Registered
        Route::resource('banners', BannerController::class); // Banner
        // Route::resource('danh_gia', DanhGiaController::class);
        Route::resource('vai_tro', VaiTroController::class); // Vai trò
        Route::resource('dat_phong', DatPhongController::class); // Đặt phòng
        Route::resource('chi_tiet_dat_phong', ChiTietDatPhongController::class); // Chi tiết đặt phòng
        Route::put('loai_phong/change-status', [LoaiPhongController::class, 'changeStatus'])->name('loai_phong.change-status'); // Thay đổi trạng thái loại phòng
        Route::get('exportUser', [ExportController::class, 'exportUser'])->name('exportUser'); // ExportUser
        Route::put('searchKhuyenMai', [DatPhongController::class, 'searchKhuyenMai'])->name('searchKhuyenMai'); 
        Route::resource('khuyen_mai', KhuyenMaiController::class); // Khuyến mãi
        Route::resource('dich_vu', DichVuController::class); // Dịch vụ
        Route::resource('lien_he', LienHeController::class); // Liên hệ
    });

Route::middleware(['auth', 'verified'])->prefix('admin')
    ->as('admin.')
    ->group(function () {

        Route::resource('danh_gia', DanhGiaController::class);
    });

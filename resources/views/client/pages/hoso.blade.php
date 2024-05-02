@extends('client.layouts.master')
@section('content')
<section class="relative table w-full items-center py-36 bg-[url('../../assets/images/bg/cta.html')] bg-top bg-no-repeat bg-cover">
    <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 via-slate-900/80 to-slate-900"></div>
    <div class="container relative">
        <div class="grid grid-cols-1 pb-8 text-center mt-10">
            <h3 class="text-3xl leading-normal tracking-wider font-semibold text-white">Hồ sơ
            </h3>
        </div><!--end grid-->
    </div><!--end container-->

    <div class="absolute text-center z-10 bottom-5 start-0 end-0 mx-3">
        <ul class="tracking-[0.5px] mb-0 inline-block">
            <li class="inline-block uppercase text-[13px] font-bold duration-500 ease-in-out text-white/50 hover:text-white">
                <a href="<?= env('APP_URL') ?>/">EasyStay</a>
            </li>
            <li class="inline-block text-base text-white/50 mx-0.5 ltr:rotate-0 rtl:rotate-180"><i class="mdi mdi-chevron-right"></i></li>
            <li class="inline-block uppercase text-[13px] font-bold duration-500 ease-in-out text-white" aria-current="page">Hồ sơ</li>
        </ul>
    </div>
</section><!--end section-->
<div class="container mx-auto py-8">
    <!-- Grid with Two Columns -->
    <div class="mt-28">
        <!-- First Column -->
   
        <!-- Second Column -->
        <div class="bg-white rounded-lg overflow-hidden shadow-md">
            <p class="text-2xl font-semibold mt-4 ms-3">Thông tin cá nhân</p>
            <!-- Hiển thị thông báo lỗi -->
            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Lỗi!</strong>
                <span class="block sm:inline">{{ $errors->first() }}</span>
            </div>
            @endif
            <!-- Hiển thị thông báo thành công -->
            @if(Session::has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Thành công!</strong>
                <span class="block sm:inline">{{ Session::get('success') }}</span>
            </div>
            @endif
            <form method="POST" action="{{ route('ho_so.update') }}">
                @csrf
                @method('PUT')
                <div class="p-6 grid grid-cols-2 gap-4">

                <label class="block">
            <span >Hình ảnh:</span>
            <img class="w-20 h-20 rounded	" src="{{ asset(Auth::user()->anh) }}" alt="" />
            <input type="file" name="anh" class="form-input mt-3" accept="image/*">
        </label>

                    <label class="block">
                        <span>Họ và Tên:</span>
                        <input name="ten_nguoi_dung" class="form-input mt-3 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" type="text" value="{{ old('ten_nguoi_dung', Auth::user()->ten_nguoi_dung) }}" required />
                    </label>
                    <label class="block">
                        <span>Email:</span>
                        <input name="email" class="form-input mt-3 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" type="email" value="{{ old('email', Auth::user()->email) }}" required />
                    </label>
                    <label class="block">
                        <span>Địa chỉ:</span>
                        <input name="dia_chi" class="form-input mt-3 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" type="text" value="{{ old('dia_chi', Auth::user()->dia_chi) }}" required />
                    </label>
                    <label class="block">
                        <span>Số điện thoại:</span>
                        <input name="so_dien_thoai" class="form-input mt-3 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" type="tel" pattern="[0-9]{}" value="{{ old('so_dien_thoai', Auth::user()->so_dien_thoai) }}" required />
                    </label>
                    <label class="block">
                        <span>Ngày sinh:</span>
                        <input name="ngay_sinh" class="form-input mt-3 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent" type="date" value="{{ old('ngay_sinh', Auth::user()->ngay_sinh) }}" required />
                    </label>
                </div>
                <div class="flex justify-center items-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-6 ">
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
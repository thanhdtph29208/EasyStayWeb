@extends('client.layouts.master')
@section('content')
<!-- Start Hero -->
<section class="relative table w-full items-center py-36 bg-[url('../../assets/images/bg/cta.html')] bg-top bg-no-repeat bg-cover">
    <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 via-slate-900/80 to-slate-900">

    </div>
    <div class="container relative">
        <div class="grid grid-cols-1 pb-8 text-center mt-10">
            <h3 class="font-bold text-white lg:leading-normal leading-normal text-4xl lg:text-2xl mb-6 mt-5">LOẠI PHÒNG</h3>
        </div><!--end grid-->
    </div><!--end container-->

    <div class="absolute text-center z-10 bottom-5 start-0 end-0 mx-3">
        <ul class="tracking-[0.5px] mb-0 inline-block">
            <li class="inline-block uppercase text-[13px] font-bold duration-500 ease-in-out text-white/50 hover:text-white"><a href="<?= env('APP_URL') ?>/">EasyStay</a></li>
            <li class="inline-block text-base text-white/50 mx-0.5 ltr:rotate-0 rtl:rotate-180"><i class="mdi mdi-chevron-right"></i></li>
            <li class="inline-block uppercase text-[13px] font-bold duration-500 ease-in-out text-white" aria-current="page">Loại phòng</li>
        </ul>
    </div>
</section><!--end section-->
<!-- End Hero -->

<div class="grid lg:grid-cols-2 grid-cols-1 my-6 gap-6 container">
    <!-- Form Lọc -->
    <div class="col-span-2">
    <form action="{{ route('client.pages.loai_phong.filter') }}" method="GET" class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-4">
            <!-- Input để lọc theo tên phòng -->
            <input type="text" name="ten_phong" placeholder="Tên phòng" class="px-4 py-2 border border-gray-300 rounded-md">
            
   
            <!-- Nút submit để thực hiện lọc -->
            <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Lọc</button>
        </div>
        </form>
        <form action="{{ route('client.pages.loai_phong.filter') }}" method="GET" class="flex items-center justify-between mb-6">

        <div class="flex items-center space-x-4">
            <!-- Input để lọc theo tên phòng -->
            <input type="text" name="gia" placeholder="Giá"  class="px-4 py-2 border border-gray-300 rounded-md">
            
   
            <!-- Nút submit để thực hiện lọc -->
            <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Lọc</button>
        </div>
        </form>
        <form action="{{ route('client.pages.loai_phong.filter') }}" method="GET" class="flex items-center justify-between mb-6">

        <div class="flex items-center space-x-4">
            <!-- Input để lọc theo tên phòng -->
            <select name="trang_thai" >
        <option value="">Chọn trạng thái</option>
        <option value="1">Còn phòng</option>
        <option value="0">Hết phòng </option>
    </select>
            
   
            <!-- Nút submit để thực hiện lọc -->
            <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Lọc</button>
        </div>
    </form>
</div>


    <!-- Danh sách loại phòng -->
    @foreach ($rooms as $room)
    <div class="group rounded-md shadow dark:shadow-gray-700">
        <div class="md:flex md:items-center">
            <div class="relative overflow-hidden md:shrink-0 md:rounded-md rounded-t-md shadow dark:shadow-gray-700 md:m-3 mx-3 mt-3">
                <img src="{{ Storage::url($room->anh) }}" class="h-full w-full object-cover md:w-48 md:h-56 scale-125 group-hover:scale-100 duration-500" alt="">
                <div class="absolute top-0 end-0 p-4">
                    <a href="javascript:void(0)" class="size-8 inline-flex justify-center items-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-800 rounded-full text-slate-100 dark:text-slate-700 focus:text-red-500 dark:focus:text-red-500 hover:text-red-500 dark:hover:text-red-500"><i class="mdi mdi-heart text-[20px] align-middle"></i></a>
                </div>
            </div>
            <div class="p-4 w-full">
                <p class="flex items-center text-slate-400 font-medium mb-2"><i data-feather="map-pin" class="text-red-500 size-4 me-1"></i> Hà Nội, Việt Nam</p>
                <a href="#" class="font-medium hover:text-red-500 duration-500 ease-in-out">{{$room->ten}}</a>
                <div class="mt-4 pt-4 flex justify-between items-center border-t border-slate-100 dark:border-gray-800">
                    <h5 class="text-lg font-medium text-red-500">{{$room->gia}}</h5>
                    <a href="<?= env('APP_URL') ?>/chi_tiet_loai_phong/<?= $room->id ?>" class="text-slate-400 hover:text-red-500">Khám phá ngay<i class="mdi mdi-arrow-right"></i></a>
                </div>
                <div class="mt-3">
                    @if($room->trang_thai == 0)
                    <button class="py-1 px-3 inline-block tracking-wide align-middle duration-500 text-base text-center bg-gray-500 text-white rounded-md">Hết phòng</button>
                    @endif
                </div>
            </div>
        </div>
    </div><!--end content-->
    @endforeach
</div><!--end grid-->

@endsection

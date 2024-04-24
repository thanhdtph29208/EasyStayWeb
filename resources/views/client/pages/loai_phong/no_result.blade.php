@extends('client.layouts.master')
@section('content')
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
<div class="container mx-auto py-8">
    <div class="text-center">
        <h2 class="text-2xl font-bold mb-4">Không có kết quả được tìm thấy</h2>
        <p class="text-gray-600">Xin lỗi, không có phòng nào phù hợp với tiêu chí tìm kiếm của bạn.</p>
    </div>
</div>

@endsection

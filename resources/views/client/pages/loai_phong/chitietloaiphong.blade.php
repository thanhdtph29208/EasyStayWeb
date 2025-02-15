
@extends('client.layouts.master')
@section('content')

<!-- Start Hero -->
<section class="relative table w-full items-center py-36 bg-[url('../../assets/images/bg/cta.html')] bg-top bg-no-repeat bg-cover">
    <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 via-slate-900/80 to-slate-900"></div>
    <div class="container relative">
        <div class="grid grid-cols-1 pb-8 text-center mt-10">
            <h3 class="text-3xl leading-normal tracking-wider font-semibold text-white">Loại phòng: {{ $detail->ten }}
            </h3>
        </div><!--end grid-->
    </div><!--end container-->

    <div class="absolute text-center z-10 bottom-5 start-0 end-0 mx-3">
        <ul class="tracking-[0.5px] mb-0 inline-block">
            <li class="inline-block uppercase text-[13px] font-bold duration-500 ease-in-out text-white/50 hover:text-white">
                <a href="<?= env('APP_URL') ?>/">EasyStay</a>
            </li>
            <li class="inline-block text-base text-white/50 mx-0.5 ltr:rotate-0 rtl:rotate-180"><i class="mdi mdi-chevron-right"></i></li>
            <li class="inline-block uppercase text-[13px] font-bold duration-500 ease-in-out text-white" aria-current="page">Chi tiết loại phòng</li>
        </ul>
    </div>
</section><!--end section-->
<!-- End Hero -->
<section class="relative md:py-24 py-16">
    <div class="container relative">
        <div class="grid md:grid-cols-12 grid-cols-1 gap-6">
            <div class="lg:col-span-8 md:col-span-7">
                <div class="grid grid-cols-12 gap-4">
                    <div class="md:col-span-8 col-span-8">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active thumbnail border rounded shadow-lg" aria-current="true" aria-label="Slide 1">
                            <img src="{{ Storage::url($detail->anh) }}" alt="..." class="d-block">
                        </button>
                        @foreach ($detail->anhPhong as $key => $item)
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ ++$key }}" class="thumbnail border rounded shadow-lg" aria-label="Slide {{ ++$key }}">
                            <img src="{{ asset($item->anh) }}" class="d-block" alt="...">
                        </button>
                        @endforeach
                    </div>
                </div>


      

                <h5 class="text-2xl font-semibold mt-5">{{ $detail->ten }}</h5>
                <p class="flex items-center text-slate-400 font-medium mt-2"><i data-feather="map-pin" class="size-4 me-1"></i> Hà Nội, Việt Nam</p>

                <ul class="list-none">

                    <li class="inline-flex items-center me-5 mt-5">
                        <i data-feather="users" class="size-6 stroke-[1.5] text-red-500"></i>

                        <div class="ms-3">
                            <p class="font-medium">Giới hạn người:</p>
                            <span class="text-slate-400 font-medium text-sm">{{ $detail->gioi_han_nguoi }}</span>
                        </div>
                    </li>



                    <li class="inline-flex items-center me-5 mt-5">
                        <i data-feather="dollar-sign" class="size-6 stroke-[1.5] text-red-500"></i>

                        <div class="ms-3">
                            <p class="font-medium">{{ $detail->gia }} VNĐ</p>
                            <span class="text-slate-400 font-medium text-sm">1 ngày</span>
                        </div>
                    </li>
                    <li class="inline-flex items-center me-5 mt-5">
                        <i data-feather="" class="size-6 stroke-[1.5] text-red-500"></i>

                        <div class="ms-3">
                            <p class="font-medium">Trạng Thái Loại Phòng:</p>
                            <span class="text-slate-400 font-medium text-sm">{{ $detail->trang_thai ? 'Còn phòng' : 'Hết phòng'}}</span>
                        </div>
                    </li>
                   
                       
                      
                 
                </ul>

      
                <div class="mt-6">
                    <h5 class="text-lg font-semibold">Mô tả phòng:</h5>

                    <p class="text-slate-400 mt-6">{{ $detail->mo_ta_dai }}</p>
                    <!-- <p class="text-slate-400 mt-3">The advantage of its Latin origin and the relative meaninglessness of Lorum Ipsum is that the text does not attract attention to itself or distract the viewer's attention from the layout.</p> -->
                </div>
            
                <div class="border-2 rounded mt-6">
                    <div class="m-3">
                        <h2 class="mb-3 font-semibold text-xl">Đánh giá </h2>
                        <hr>
                        <div class="mt-3">
                            @if (count($danh_gias) > 0)
                            @foreach ($danh_gias as $key => $item)
                            <table>
                                <tr>
                                    <td>
                                        <span class="size-8 inline-flex items-center justify-center tracking-wide align-middle duration-500 text-base text-center rounded-full border border-slate-500 text-white bg-slate-400">
                                            <img class="rounded-full " width="45px" src="{{ Storage::url($item->user['anh']) }}" alt="">
                                        </span>
                                    </td>
                                    <td>
                                        <ul class="pt-4 ps-2">
                                            <li class="font-semibold text-base">{{ $item->user['email'] }}</li>
                                            <li>{{ $item['noi_dung'] }}</li>
                                            <li class="text-xs text-gray-600">{{ $item['created_at'] }}</li>
                                        </ul>

                                    </td>
                                </tr>
                            </table>
                            @endforeach
                            @else
                            <p class="text-red-500 italic mt-8">***Chưa có đánh giá nào cho loại phòng này***</p>
                            @endif
                        </div>

                    </div>

                    <form class="px-3" action="{{ route('admin.danh_gia.store', $detail) }}" method="post">
                        @csrf
                        <input type="text" value="{{ $detail->id }}" name="loai_phong_id" hidden>
                        <div class="mt-6  ">
                            <span class="input-group-text text-lg font-semibold">Bình luận:</span>
                            <div class="input-group my-2 ">
                                <textarea class="form-control border border-gray-300 w-full rounded-sm" aria-label="With textarea" rows="4" placeholder="Nhập phần bình luận của bạn ở đây" name="noi_dung"></textarea>
                            </div>
                        </div>

                        <button class="py-2 px-5 mb-2 inline-block tracking-wide align-middle duration-500 text-base text-center bg-red-500 text-white rounded-md w-25 ">
                            Gửi
                        </button>

                    </form>

                </div>

            </div>

            <div class="lg:col-span-4 md:col-span-5">
                <div class="p-4 rounded-md shadow dark:shadow-gray-700 sticky top-20">

   <div>

   </div>


                    <div class="mt-6">
                        <h5 class="text-lg font-medium">Google Map</h5>

                        <div class="mt-3">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.8671732908642!2d105.74461987448129!3d21.038000087462674!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31345550b525aa03%3A0x3fdefc40f69a023a!2zVHLGsOG7nW5nIENhbyDEkOG6s25nIEZQVA!5e0!3m2!1svi!2s!4v1710989478110!5m2!1svi!2s" style="border:0" class="w-full h-[300px] rounded-full" allowfullscreen; allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end container-->
</section>
<!-- End -->
<div id="notification" class="notification hidden">
    <p id="notificationMessage"></p>
</div>

<!-- Back to top button -->
<a href="#" onclick="topFunction()" id="back-to-top" class="back-to-top fixed hidden text-lg rounded-md z-10 bottom-5 end-5 size-8 text-center bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white justify-center items-center"><i class="mdi mdi-arrow-up"></i></a>

<!-- JavaScript -->
<script src="path/to/your/javascript.js"></script>

<!-- Switcher -->
<div class="fixed top-1/4 -left-2 z-50">
    <span class="relative inline-block rotate-90">
        <input type="checkbox" class="checkbox opacity-0 absolute" id="chk">
        <label class="label bg-slate-900 dark:bg-white shadow dark:shadow-gray-800 cursor-pointer rounded-full flex justify-between items-center p-1 w-14 h-8" for="chk">
            <i data-feather="moon" class="w-[18px] h-[18px] text-yellow-500"></i>
            <i data-feather="sun" class="w-[18px] h-[18px] text-yellow-500"></i>
            <span class="ball bg-white dark:bg-slate-900 rounded-full absolute top-[2px] left-[2px] w-7 h-7"></span>
        </label>
    </span>
</div>

<!-- <div class="fixed top-1/2 -right-11 z-50 hidden sm:block">
                                    <a href="https://1.envato.market/travosy" target="_blank" class="py-1 px-3 relative inline-block rounded-t-md -rotate-90 bg-white dark:bg-slate-900 shadow-md dark:shadow dark:shadow-gray-800 font-semibold"><i class="mdi mdi-cart-outline me-1"></i> Download</a>
                                </div> -->
<!-- Switcher -->

<!-- LTR & RTL Mode Code -->
<div class="fixed top-[40%] -left-3 z-50">
    <a href="#" id="switchRtl">
        <span class="py-1 px-3 relative inline-block rounded-b-md -rotate-90 bg-white dark:bg-slate-900 shadow-md dark:shadow dark:shadow-gray-800 font-semibold rtl:block ltr:hidden">LTR</span>
        <span class="py-1 px-3 relative inline-block rounded-b-md -rotate-90 bg-white dark:bg-slate-900 shadow-md dark:shadow dark:shadow-gray-800 font-semibold ltr:block rtl:hidden">RTL</span>
    </a>
</div>
<!-- LTR & RTL Mode Code -->

<!-- Back to top -->
<a href="#" onclick="topFunction()" id="back-to-top" class="back-to-top fixed hidden text-lg rounded-md z-10 bottom-5 end-5 size-8 text-center bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white justify-center items-center"><i class="mdi mdi-arrow-up"></i></a>
<!-- Back to top -->

<!-- JAVASCRIPTS -->
<script src="<?= env('APP_URL') ?>assets/libs/js-datepicker/datepicker.min.js"></script>
<script src="<?= env('APP_URL') ?>assets/libs/tobii/js/tobii.min.js"></script>
<script src="<?= env('APP_URL') ?>assets/libs/feather-icons/feather.min.js"></script>
<script src="<?= env('APP_URL') ?>assets/js/plugins.init.js"></script>
<script src="<?= env('APP_URL') ?>assets/js/app.js"></script>
<!-- JAVASCRIPTS -->
@endsection
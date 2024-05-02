@extends('client.layouts.master')
@section('content')
    <!-- Start Hero -->
    <section
        class="relative table w-full items-center py-36 bg-[url('../../assets/images/bg/cta.html')] bg-top bg-no-repeat bg-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 via-slate-900/80 to-slate-900"></div>
        <div class="container relative">
            <div class="grid grid-cols-1 pb-8 text-center mt-10">
                <h3 class="text-4xl leading-normal tracking-wider font-semibold text-white">{{ $detail->tieu_de }}</h3>

                <ul class="list-none mt-6">
                    <!-- <li class="inline-block text-white/50 mx-5"> <span class="text-white block">Author :</span> <span class="block">Travosy</span></li> -->
                    <li class="inline-block text-white/50 mx-5"> <span class="text-white block">Date :</span> <span
                            class="block">{{ $detail->created_at }}</span></li>
                    <!-- <li class="inline-block text-white/50 mx-5"> <span class="text-white block">Time :</span> <span class="block">8 Min Read</span></li> -->
                </ul>
            </div><!--end grid-->
        </div><!--end container-->

        <div class="absolute text-center z-10 bottom-5 start-0 end-0 mx-3">
            <ul class="tracking-[0.5px] mb-0 inline-block">
                <li
                    class="inline-block uppercase text-[13px] font-bold duration-500 ease-in-out text-white/50 hover:text-white">
                    <a href="<?= env('APP_URL') ?>/">EasyStay</a>
                </li>
                <li class="inline-block text-base text-white/50 mx-0.5 ltr:rotate-0 rtl:rotate-180"><i
                        class="mdi mdi-chevron-right"></i></li>
                <li class="inline-block uppercase text-[13px] font-bold duration-500 ease-in-out text-white"
                    aria-current="page">Chi tiết tin tức</li>
            </ul>
        </div>
    </section><!--end section-->
    <!-- End Hero -->

    <!-- Start -->
    <section class="relative md:py-24 py-16">
        <div class="container">
            <div class="grid  grid-cols-1 gap-6">
                <div class="lg:col-span-8 md:col-span-6">
                    <div class="relative overflow-hidden rounded-md shadow dark:shadow-gray-800">

                        <img src="{{ Storage::url($detail->anh) }}" alt="">

                        <div class="p-6">
                            <!-- <p class="text-slate-400">{!! $detail->noi_dung !!}</p> -->
                            <p class="text-slate-400 italic border-x-4 border-red-500 rounded-ss-xl rounded-ee-xl mt-3 p-3">
                                {!! $detail->noi_dung !!}</p>
                            <!-- <p class="text-slate-400 mt-3">The advantage of its Latin origin and the relative meaninglessness of Lorum Ipsum is that the text does not attract attention to itself or distract the viewer's attention from the layout.</p> -->
                        </div>
                    </div>


                </div>


            </div>
        </div>


    </section><!--end section-->
    <!-- End -->
    <!--  -->
@endsection

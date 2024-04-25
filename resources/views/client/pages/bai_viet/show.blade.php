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
    <div class="m-4 bg-white rounded-lg border p-2 ">

        <h3 class="font-bold text-gray-600">Discussion</h3>
        <div class="flex flex-col">

            @foreach ($comments as $comment)
                <div class="border rounded-md p-3 ml-3 my-3">
                    <div class="flex gap-3 items-center">

                        <img src="https://avatars.githubusercontent.com/u/22263436?v=4"
                            class="object-cover w-8 h-8 rounded-full
                            border-2 border-emerald-400  shadow-emerald-400
                            ">

                        <h3 class="font-bold text-gray-600">
                            {{ $comment->user->ten_nguoi_dung }}
                        </h3>
                    </div>


                    <p class="text-gray-600 mt-2">
                        {{ $comment->content }}
                    </p>

                </div>
            @endforeach





        </div>
        @if (Auth::check())
            <form action="{{ route('client.pages.bai_viet.comment') }}" method="POST">
                @csrf
                <input type="hidden" name="id_user" value="{{ Auth::user()->id }}">
                <input type="hidden" name="id_post" value="{{ $id }}">
                <div class="w-full px-3 my-2">
                    <textarea
                        class="bg-gray-100 rounded text-gray-600 border border-gray-400 leading-normal resize-none w-full h-20 py-2 px-3 font-medium placeholder-gray-700 focus:outline-none focus:bg-white"
                        name="content" placeholder="Type Your Comment" required></textarea>
                </div>
                @error('content')
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ $message }}</span>
                        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                            <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20">
                                <title>Close</title>
                                <path
                                    d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" />
                            </svg>
                        </span>
                    </div>
                @enderror

                <div class="w-full flex justify-end px-3">
                    <input type="submit" class="px-2.5 py-1.5 rounded-md text-white text-sm bg-indigo-500"
                        value="Post Comment">
                </div>
            </form>
        @else
            <div>
                <!-- Display the content here for users who are not logged in -->
            </div>
            <p>You need to <a href="{{ route('login', ['redirect' => Request::path()]) }}">login</a> to post a comment.</p>
        @endif




    </div>
@endsection

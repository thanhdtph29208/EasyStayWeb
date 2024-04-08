@extends('client.layouts.master')
@section('content')
    <!-- Start Hero -->
    <section
        class="relative table w-full items-center py-36 bg-[url('../../assets/images/bg/cta.html')] bg-top bg-no-repeat bg-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 via-slate-900/80 to-slate-900"></div>
        <div class="container relative">
            <div class="grid grid-cols-1 pb-8 text-center mt-10">
                <h3 class="text-3xl leading-normal tracking-wider font-semibold text-white">Thông tin đặt phòng
                </h3>
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
                    aria-current="page">Thông tin đặt phòng</li>
            </ul>
        </div>
    </section><!--end section-->
    <div class="container mx-auto py-5">
        <form action="{{ route('pay') }}" method="get">
            @csrf
            <div class="flex flex-row">
                <div class="w-3/4">
                    <div class=" shadow-md rounded px-4 py-5">
                        <h1 class="text-xl font-bold mb-4">Thông tin người đặt phòng</h1>
                        <hr class="my-2 ">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="block text-sm font-medium mb-1">Họ và tên</label>
                            <input type="text" class="form-input w-full" id="exampleInputEmail1"
                                value="{{ Auth::user()->ten_nguoi_dung }}" required name="name">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail2" class="block text-sm font-medium mb-1">Số điện thoại</label>
                            <input type="text" class="form-input w-full" id="exampleInputEmail2" required
                                name="order_sdt">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail3" class="block text-sm font-medium mb-1">Email</label>
                            <input type="text" class="form-input w-full" id="exampleInputEmail3" required name="address"
                                value="{{ Auth::user()->email }}">
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail3" class="block text-sm font-medium mb-1">Hình thức thanh
                                toán</label>
                            <select class="form-select w-full" aria-label="Default select example" id="payment"
                                name="payment">
                                <option value="1" name="billpayment">VNPay</option>
                                <option value="2" name="payUrl" >Momo</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="w-1/4 ms-5 px-4 py-5 shadow-md">
                    <div>
                        <h5 class="text-xl font-bold mb-2">Yêu cầu đặt phòng của bạn</h5>
                        <hr class="my-3">
                        <p class="text-base font-medium mb-2">Khách sạn EasyStay</p>
                        <p class="text-base font-medium mb-2">Nhận phòng:</p>
                        <p class="text-base font-medium mb-2">Trả phòng:</p>
                    </div>
                    <hr>
                    <div>
                        <h5 class="text-xl font-bold my-2">Thông tin phòng</h5>
                        @foreach ($cartItems as $item)
                            <p class="text-base">Tên phòng: {{ $item->name }}</p>
                            <p class="text-base">Số lượng: {{ $item->qty }}</p>
                            <p class="text-base">Giá phòng: {{ number_format($item->price, 0, ',', ',') }} VNĐ</p>
                            <p class="text-red-600 text-right text-base font-bold">
                                {{ number_format($item->price * $item->qty, 0, ',', ',') }} VNĐ
                            </p>
                            <hr>
                        @endforeach
                    </div>

                    <div class="flex justify-between mt-3">
                        <p class="font-bold text-lg">Tổng giá:</p>
                        <p class="text-red-600 font-bold text-lg">{{ number_format($cartTotal, 0, ',', ',') }} VNĐ</p>
                    </div>

                    <div class="mt-2">
                        <p class="text-sm">Bao gồm tất cả các loại thuế và phí dịch vụ</p>
                        <p class="text-red-600 text-sm">(Theo quy định của Ngân hàng Nhà nước Việt Nam, Quý khách vui lòng
                            thanh toán bằng VNĐ)</p>
                    </div>
                </div>
            </div>


        
        
            <div>
                <input type="hidden" name="cart_total" value="{{ $cartTotal }}">
                <button type="submit" name="redirect"
                    class="bg-blue-500 text-white hover:bg-red-700 font-bold py-2 px-4 rounded w-full mt-4">Thanh
                    toán</button>
            </div>
            
        </form>
    </div>
@endsection

@extends('client.layouts.master')
@section('content')
    <!-- Start Hero -->
    <section
        class="relative table w-full items-center py-36 bg-[url('../../assets/images/bg/cta.html')] bg-top bg-no-repeat bg-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 via-slate-900/80 to-slate-900"></div>
        <div class="container relative">
            <div class="grid grid-cols-1 pb-8 text-center mt-10">
                <h3 class="text-3xl leading-normal tracking-wider font-semibold text-white">Thông tin đơn đặt phòng
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
                    aria-current="page">Thông tin đơn đặt phòng</li>
            </ul>
        </div>
    </section><!--end section-->
    <div class="container mx-auto py-8">
        <div class="grid grid-cols-[900px_minmax(300px,_1fr)_100px] gap-3 ">
            {{-- div 1 --}}
            <div class="bg-white rounded-lg overflow-hidden shadow-md">
                <div class="bg-white border rounded-lg">
                    <div class="p-4 border-b">
                        <h1 class="text-xl font-semibold">Thông tin đơn đặt phòng</h1>
                    </div>
                    <div class="p-4">
                        <table class="w-full table-fixed">
                            <thead>
                                <tr>
                                    <th class="px-2 py-2">#</th>
                                    <th class="px-2 py-2">Loại phòng</th>
                                    <th class="px-2 py-2">Số lượng</th>
                                    <th class="px-2 py-2">Giá</th>
                                    <th class="px-2 py-2">Tổng tiền</th>
                                    <th class="px-2 py-2">
                                        <button class="btn btn-primary ">Clear</button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartItems as $item)
                                    <tr>
                                        <td>
                                            <img src="{{ Storage::url($item->image) }}" alt="" class="w-24 h-auto">
                                        </td>
                                        <td>
                                            <p>{{ $item->name }}</p>
                                        </td>
                                        <td>
                                            <div class="flex items-center">
                                                <button class="btn btn-warning mr-1 room-decrement">
                                                    <i class="bi bi-dash"></i>
                                                </button>
                                                <input type="text"
                                                    class="form-control w-16 px-2 py-1 text-center phong-qty"
                                                    value="{{ $item->qty }}" readonly data-rowid="{{ $item->rowId }}">
                                                <button class="btn btn-success ml-1 room-increment">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>{{ number_format($item->price, 0, '.', '.') }}VNĐ</td>
                                        <td id="{{ $item->rowId }}">
                                            {{ number_format($item->price * $item->qty, 0, '.', '.') }}VNĐ
                                        </td>
                                        <td>
                                            <a href="{{ route('chi_tiet_gio_hang.xoa_loai_phong', $item->rowId) }}"
                                                class="btn btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                @if (count($cartItems) == 0)
                                    <tr>
                                        <td class="border-b-0">Bạn chưa chọn phòng nào !</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- div 2 --}}

            <div class="bg-white rounded-lg overflow-hidden shadow-md">
                <div class="p-4 border-b">
                    <h5 class="text-lg font-semibold">THÔNG TIN ĐẶT PHÒNG</h5>
                </div>
                <div class="p-4">

                    <form class="mb-3" action="{{ route('checkout') }}" method="get">
                        @csrf

                        <div class="flex items-center justify-between mb-2 mt-2">
                            <p class="font-semibold">Tổng tiền:</p>
                            <p id="total" class="font-semibold">{{ number_format($total, 0, '.', '.') }}VNĐ</p>
                            <input type="hidden" value="{{ $total }}" name="total">
                        </div>

                        <div class="flex items-center justify-between mb-2 mt-2">
                            <p class="font-semibold">Khuyến mãi:</p>
                            <p id="discount" class="font-semibold">0VNĐ</p>
                        </div>

                        <div class="flex items-center justify-between mt-2">
                            <p class="font-semibold">Thành tiền:</p>
                            <p id="cart_total" class="font-semibold">{{ number_format($total, 0, '.', '.') }}VNĐ</p>
                            <input type="hidden" value="{{ $total }}" name="cart_total" id="input_cart_total">
                        </div>

                        <div class="mt-3">
                            <span class="font-semibold">Áp dụng mã giảm giá</span>

                            {{-- <form id="coupon_form" class="flex mb-3 mt-2"> --}}

                            <input class="form-control w-40 px-2 py-1 mr-2 border-2" type="text"
                                placeholder="Mã khuyến mãi" name="coupon_code">
                            <button
                                class="py-1 px-2 inline-block font-semibold tracking-wide align-middle duration-500 text-base text-center bg-red-500 text-white rounded-md">Áp
                                dụng</button>
                            {{-- </form> --}}
                        </div>

                        @if (count($cartItems) != 0)
                            <button
                                class="mt-4 py-1 px-5 h-10 inline-block tracking-wide align-middle duration-500 text-base text-center bg-red-500 text-white rounded-md w-full cursor-pointer"
                                type="submit">Đặt phòng</button>
                        @endif

                    </form>



                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.room-increment').on('click', function() {
                alert(123);
                let input = $(this).siblings('.phong-qty')
                let quantity = parseInt(input.val()) + 1;
                console.log(quantity);
                let rowId = input.data('rowid')
                $.ajax({
                    url: "{{ route('chi_tiet_gio_hang.them_phong') }}",
                    method: "POST",
                    data: {
                        quantity: quantity,
                        rowId: rowId
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.status === 'success') {
                            input.val(quantity)
                            let id = "#" + rowId;
                            toastr.success(data.message)
                            $(id).text(data.phong_total + "VNĐ")
                            VNĐ('#total').text(data.total + "VNĐ");
                            calcCouponDiscount()
                        } else if (data.status === 'error') {
                            toastr.error(data.message)
                        }
                    }
                })
            })

            $('.room-decrement').on('click', function() {
                let input = $(this).siblings('.phong-qty')
                let quantity = parseInt(input.val()) - 1;
                if (quantity < 1) {
                    quantity = 1
                }
                input.val(quantity)
                // console.log(quantity);
                let rowId = input.data('rowid')
                $.ajax({
                    url: "{{ route('chi_tiet_gio_hang.them_phong') }}",
                    method: "POST",
                    data: {
                        quantity: quantity,
                        rowId: rowId
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.status === 'success') {
                            let id = "#" + rowId;
                            toastr.success(data.message)
                            $(id).text(data.phong_total + "VNĐ")
                            $('#total').text(data.total + "VNĐ");
                            calcCouponDiscount()
                        }
                    }
                })
            })

            $('.clear-cart').on('click', function() {
                Swal.fire({
                    title: 'Bạn có muốn xóa?',
                    text: "Hành động này sẽ xóa loại phòng!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Đồng ý'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                            type: 'GET',
                            url: "{{ route('clear-cart') }}",
                            success: function(data) {
                                console.log(data);
                                if (data.status == 'success') {
                                    Swal.fire(
                                        'Xóa!',
                                        data.message,
                                        'success'
                                    )
                                    window.location.reload();
                                }
                            },
                            error: function(xhr, status, error) {
                                console.log(error);
                            }
                        })
                    }
                })
            })

            function calcCouponDiscount() {
                $.ajax({
                    type: 'GET',
                    url: "{{ route('coupon-calc') }}",
                    success: function(data) {
                        console.log(data);
                        if (data.status == 'error') {
                            toastr.error(data.message)
                        } else if (data.status == 'success') {
                            $('#discount').text(data.discount + 'VNĐ');
                            $('#cart_total').text(data.cart_total + 'VNĐ');
                            $('#input_cart_total').val(data.cart_total);
                        }
                    }
                })
            }
        })
    </script>
@endpush

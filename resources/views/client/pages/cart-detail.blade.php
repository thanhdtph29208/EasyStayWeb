@extends('client.layouts.master')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- <script>
    function changeQuantity(rowId, action) {
        var inputElement = document.querySelector('input[data-rowid="' + rowId + '"]');
        var currentQuantity = parseInt(inputElement.value);
        var price = parseFloat(inputElement.getAttribute('data-price'));

        if (action === 'increase') {
            inputElement.value = currentQuantity + 1;
        } else if (action === 'decrease' && currentQuantity > 0) {
            inputElement.value = currentQuantity - 1;
        }

        var subtotal = price * parseInt(inputElement.value);
        document.getElementById(rowId).textContent = `${subtotal.toLocaleString()} VNĐ`;

        // Cập nhật tổng số tiền
        updateTotalPrice();
    }

    function updateTotalPrice() {
        var total = 0;
        var subtotals = document.querySelectorAll('td[id^="row"]');
        subtotals.forEach(function(subtotal) {
            total += parseFloat(subtotal.textContent.replace('VNĐ', '').replace(/\./g, '').trim());
        });
        document.getElementById('totalPrice').textContent = `${total.toLocaleString()} VNĐ`;
    }



    
</script> -->

<section class="relative table w-full items-center py-36 bg-[url('../../assets/images/bg/cta.html')] bg-top bg-no-repeat bg-cover">
    <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 via-slate-900/80 to-slate-900"></div>
    <div class="container relative">
        <div class="grid grid-cols-1 pb-8 text-center mt-10">
            <h3 class="text-3xl leading-normal tracking-wider font-semibold text-white">Thông tin đơn đặt phòng
            </h3>
        </div><!--end grid-->
    </div><!--end container-->

    <div class="absolute text-center z-10 bottom-5 start-0 end-0 mx-3">
        <ul class="tracking-[0.5px] mb-0 inline-block">
            <li class="inline-block uppercase text-[13px] font-bold duration-500 ease-in-out text-white/50 hover:text-white">
                <a href="<?= env('APP_URL') ?>/">EasyStay</a>
            </li>
            <li class="inline-block text-base text-white/50 mx-0.5 ltr:rotate-0 rtl:rotate-180"><i class="mdi mdi-chevron-right"></i></li>
            <li class="inline-block uppercase text-[13px] font-bold duration-500 ease-in-out text-white" aria-current="page">Thông tin đơn đặt phòng</li>
        </ul>
    </div>
</section><!--end section-->
<div class="container mx-auto py-8" id="cart">
    <div class="grid grid-cols-[900px_minmax(300px,_1fr)_100px] gap-3 ">
        {{-- div 1 --}}
        <div class="bg-white rounded-lg overflow-hidden shadow-md">
            <div class="bg-white border rounded-lg">
                <div class="p-4 border-b">
                    <h1 class="text-xl font-semibold">Thông tin đơn đặt phòng</h1>
                </div>
                <div class="p-4">
                    <table class="w-full table-auto shadow-md rounded-lg overflow-hidden">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                <th class="px-4 py-2">#</th>
                                <th class="px-4 py-2">Loại phòng</th>
                                <th class="px-4 py-2">Số lượng</th>
                                <th class="px-4 py-2">Giá</th>
                                <th class="px-4 py-2">Tổng tiền</th>
                                <th class="px-4 py-2">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $item)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="px-4 py-2">
                                    <img src="{{ Storage::url($item->options['image']) }}" alt="" class="w-24 h-auto rounded-full">
                                </td>
                                <td class="px-4 py-2">
                                    <p class="text-sm font-medium">{{ $item->name }}</p>
                                </td>
                                <td class="px-4 py-2 flex items-center">
                                    <!-- <button type="button" onclick="changeQuantity('{{ $item->rowId }}', 'decrease')" class="mr-2 focus:outline-none text-gray-500 hover:text-red-500 hover:bg-gray-200 dark:hover:text-red-400 dark:hover:bg-gray-700 rounded-full p-1">
                                        <i class="bi bi-dash"></i>
                                    </button> -->
                                    <!-- <button class="btn btn-warning room-decrement">
                                        <i class="bi bi-dash"></i>
                                    </button> -->
                                    <input type="text" class="form-control w-16 px-2 py-1 text-center phong-qty border border-gray-300 dark:border-gray-600 rounded-md" value="{{ $item->qty }}" readonly data-rowid="{{ $item->rowId }}" data-price="{{ $item->price }}">
                                    <!-- <button class="btn btn-success room-increment">
                                        <i class="bi bi-plus-lg"></i>
                                    </button> -->
                                    <!-- <button type="button" onclick="changeQuantity('{{ $item->rowId }}', 'increase')" class="ml-2 focus:outline-none text-gray-500 hover:text-green-500 hover:bg-gray-200 dark:hover:text-green-400 dark:hover:bg-gray-700 rounded-full p-1">
                                        <i class="bi bi-plus-lg"></i>
                                    </button> -->
                                </td>
                                <td class="px-4 py-2">{{ number_format($item->price, 0, '.', '.') }}VNĐ</td>
                                <td class="px-4 py-2" id="{{ $item->rowId }}">{{ number_format($item->price * $item->qty , 0, '.', '.') }}VNĐ</td>
                                <td class="px-4 py-2">

                                    <button class="clear-cart inline-flex items-center px-2 py-1 text-xs font-bold leading-none text-red-600 bg-red-100 hover:bg-red-200 rounded-full focus:outline-none focus:shadow-outline" data-row-id="{{ $item->rowId }}">
                                        <i class="bi bi-trash mr-2"></i> Xóa
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @if (count($cartItems) == 0)
                            <tr class="text-center">
                                <td class="px-4 py-2 border-b-0" colspan="6">Bạn chưa chọn phòng nào!</td>
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
                        <p id="total" class="font-semibold">{{ number_format($total  , 0, '.', '.') }}VNĐ</p>
                        <input type="hidden" value="{{ $total }}" name="total">
                    </div>

                    <div class="flex items-center justify-between mb-2 mt-2">
                        <p class="font-semibold">Khuyến mãi:</p>
                        <p id="discount" class="font-semibold">0VNĐ</p>
                    </div>

                    <div class="flex items-center justify-between mt-2">
                        <p class="font-semibold">Thành tiền:</p>
                        <p id="cart_total" class="font-semibold">{{ number_format($total , 0, '.', '.') }}VNĐ</p>

                        <input type="hidden" value="{{ $total }}" name="cart_total" id="input_cart_total">
                    </div>

                    @if (count($cartItems) != 0)
                    <button class="mt-4 py-1 px-5 h-10 inline-block tracking-wide align-middle duration-500 text-base text-center bg-red-500 text-white rounded-md w-full cursor-pointer" type="submit">Đặt phòng</button>
                    @endif
                </form>

                <div class="mt-3">
                    <span class="font-semibold">Áp dụng mã giảm giá</span>

                    <form id="coupon_form" class="flex mb-3 mt-2">
                        <input class="form-control w-40 px-2 py-1 mr-2 border-2" type="text" placeholder="Mã khuyến mãi" name="coupon_code">
                        <button type="submit" class="py-1 px-2 inline-block font-semibold tracking-wide align-middle duration-500 text-base text-center bg-red-500 text-white rounded-md">Áp dụng</button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('.room-increment').on('click', function() {
            let input = $(this).siblings('.phong-qty');
            let so_luong = parseInt(input.val()) + 1; // Renamed variable from quantity to so_
            console.log(so_luong); // Changed quantity to so_luong
            let rowId = input.data('rowid');
            $.ajax({
                url: "{{ route('chi_tiet_gio_hang.them_phong') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    quantity: so_luong, // Changed quantity to so_luong
                    rowId: rowId
                },
                success: function(data) {
                    console.log(data);
                    if (data.status === 'success') {
                        input.val(so_luong); // Changed quantity to so_luong
                        let id = "#" + rowId;
                        toastr.success(data.message);
                        $(id).text(data.phong_total + "VNĐ");
                        VNĐ('#total').text(data.total + "VNĐ");
                        calcCouponDiscount();
                    } else if (data.status === 'error') {
                        toastr.error(data.message);
                    }
                }
            });
        });
        // });


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
                    _token: "{{ csrf_token() }}",
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
            var rowId = $(this).data('row-id'); // Lấy rowId từ data-attribute của thẻ 'button'

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
                        type: 'DELETE', // Sử dụng phương thức DELETE
                        url: "{{ route('chi_tiet_gio_hang.xoa_loai_phong', '') }}/" + rowId,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
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
        });



        $('#coupon_form').on('submit', function(e) {
    e.preventDefault();
    let formData = $(this).serialize();
    console.log(formData);
    $.ajax({
        type: 'GET',
        url: "{{ route('apply-coupon') }}",
        data: formData,
        success: function(data) {
            console.log(data);
            if (data.status == 'error') {
                toastr.error(data.message)
            } else if (data.status == 'success') {
                // Gọi hàm tính toán giảm giá sau khi áp dụng mã
                calcCouponDiscount();
                toastr.success(data.message)
            }
        }
    })
});

function calcCouponDiscount() {
    $.ajax({
        type: 'GET',
        url: "{{ route('coupon-calc') }}",
        success: function(data) {
            console.log(data);
            if (data.status == 'error') {
                toastr.error(data.message)
            } else if (data.status == 'success') {
                // Định dạng lại giá trị giảm giá và tổng giá trị đặt phòng
                let formattedDiscount = formatNumber(data.gia_tri_giam) + 'VNĐ';
                let formattedTotal = formatNumber(data.cart_total) + 'VNĐ';
                
                // Hiển thị giá trị đã định dạng
                $('#discount').text(formattedDiscount);
                $('#cart_total').text(formattedTotal);
                $('#input_cart_total').val(data.cart_total);
            }
        }
    })
}

// Hàm định dạng số thành chuỗi có dấu phân cách
function formatNumber(number) {
    return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
    })
</script>




@endpush
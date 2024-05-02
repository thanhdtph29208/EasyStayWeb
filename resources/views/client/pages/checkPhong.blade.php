@extends('client.layouts.master')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

<!-- Start Hero -->
<section class="relative table w-full items-center py-36 bg-[url('../../assets/images/bg/cta.html')] bg-top bg-no-repeat bg-cover">
    <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 via-slate-900/80 to-slate-900"></div>
    <div class="container relative">
        <div class="grid grid-cols-1 pb-8 text-center mt-10">
            <h3 class="text-3xl leading-normal tracking-wider font-semibold text-white">Tra cứu: </h3>
            <p class="text-white">Ngày bắt đầu: <?= $ngayBatDau->format('Y-m-d') ?></p>
            <p class="text-white">Ngày kết thúc: <?= $ngayKetThuc->format('Y-m-d') ?></p>

        </div><!--end grid-->
    </div><!--end container-->

    <div class="absolute text-center z-10 bottom-5 start-0 end-0 mx-3">
        <ul class="tracking-[0.5px] mb-0 inline-block">
            <li class="inline-block uppercase text-[13px] font-bold duration-500 ease-in-out text-white/50 hover:text-white">
                <a href="<?= env('APP_URL') ?>/">EasyStay</a>
            </li>
            <li class="inline-block text-base text-white/50 mx-0.5 ltr:rotate-0 rtl:rotate-180"><i class="mdi mdi-chevron-right"></i></li>
            <li class="inline-block uppercase text-[13px] font-bold duration-500 ease-in-out text-white" aria-current="page">Tra cứu phòng</li>
        </ul>
    </div>
</section><!--end section-->





<section class="relative  md:py-24 py-16">
    <div id="room-info-container" class="container grid grid-cols-2 gap-6">
        @foreach ($availableLoaiPhongs as $loaiPhong)

        <div class="group rounded-md shadow dark:shadow-gray-700 my-6  ">
            <div class="md:flex md:items-center cd-item-parent">
                <div class="relative overflow-hidden md:shrink-0 md:rounded-md rounded-t-md shadow dark:shadow-gray-700 md:m-3 mx-3 mt-3 cd-item w-1/3">
                    <img src="{{ Storage::url($loaiPhong->anh) }}" class="h-full w-full object-cover md:w-48 md:h-56 scale-125 group-hover:scale-100 duration-500" data-id="{{ $loaiPhong->id }}" alt="">

                    <div class="absolute top-0 start-0 p-4">
                        <span class="bg-red-500 text-white text-[12px] px-2.5 py-1 font-medium rounded-md h-5">EasyStay</span>
                    </div>

                    <div class="absolute top-0 end-0 p-4">
                        <a href="javascript:void(0)" class="size-8 inline-flex justify-center items-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-800 rounded-full text-slate-100 dark:text-slate-700 focus:text-red-500 dark:focus:text-red-500 hover:text-red-500 dark:hover:text-red-500"><i class="mdi mdi-heart text-[20px] align-middle"></i></a>
                    </div>
                </div>

                <div class="p-4 ml-2 w-full">
                    <p class="flex items-center text-slate-400 font-medium mb-2"><i data-feather="map-pin" class="text-red-500 size-4 me-1"></i> Hà Nội, Việt Nam</p>

                    <form action="" class="book-cart">
                        <input type="hidden" name="id" value="{{ $loaiPhong->id }}">
                        <a href="#" class="text-lg font-medium hover:text-red-500 duration-500 ease-in-out">{{ $loaiPhong->ten }}</a>
                        <div class="mt-2 pt-4 flex justify-between items-center border-t border-slate-100 dark:border-gray-800">
                            <h5 class="text-lg font-medium text-red-500">{{ number_format($loaiPhong->gia, 0, ',', '.') }}₫</h5> <br>
                        </div>
                        <div>
                            @foreach ($phongs as $phong)
                            @if ($phong['loai_phong']->id == $loaiPhong->id)
                            <ul>
                                <p>Phòng trống: {{ $phong['available_rooms']->count() }}</p>
                                @foreach ($phong['available_rooms'] as $room)
                                <input type="hidden" name="phong[]" value="{{ $room->id }}">
                                @endforeach
                            </ul>
                            <!-- <div class="grid grid-cols-2 gap-4"> -->
                            <div class="flex flex-col items-start mb-4">

    <label for="qty_{{ $loaiPhong->id }}" class="inline-block mb-1 text-sm font-medium text-gray-700">Số lượng phòng muốn đặt:</label>
    <input type="number" id="qty_{{ $loaiPhong->id }}" name="so_luong" min="1" value="1" max="{{ $phong['available_rooms']->count() }}" class="form-input border border-gray-300 rounded-md w-full" onchange="updateSoLuongNguoi('{{ $loaiPhong->id }}')">
</div>

<div class="flex flex-col items-start mb-4">
    <label for="so_luong_nguoi_{{ $loaiPhong->id }}" class="inline-block mb-1 text-sm font-medium text-gray-700">Số lượng người:</label>
    <input type="number" name="so_luong_nguoi" id="so_luong_nguoi_{{ $loaiPhong->id }}" value="1" min="1" max="{{ $phong['loai_phong']->gioi_han_nguoi }}" class="form-input border border-gray-300 rounded-md w-full">
</div>

<script>
function updateSoLuongNguoi(loaiPhongId) {
    var qty = parseInt(document.getElementById("qty_" + loaiPhongId).value);
    var gioiHanNguoi = parseInt(document.getElementById("so_luong_nguoi_" + loaiPhongId).getAttribute("max"));
    var inputSoLuongNguoi = document.getElementById("so_luong_nguoi_" + loaiPhongId);

    // Tính toán số lượng người tối đa dựa trên số lượng phòng muốn đặt và giới hạn người của mỗi phòng
    var maxSoLuongNguoi = qty * gioiHanNguoi;

    // Lấy giá trị hiện tại của ô nhập số lượng người
    var currentSoLuongNguoi = parseInt(inputSoLuongNguoi.value);

    // Kiểm tra xem số lượng người hiện tại có vượt quá số lượng người tối đa không
    if (currentSoLuongNguoi > maxSoLuongNguoi) {
        // Nếu vượt quá, giảm số lượng người hiện tại xuống số lượng người tối đa
        inputSoLuongNguoi.value = maxSoLuongNguoi;
    } else if (currentSoLuongNguoi < 1) {
        // Nếu số lượng người hiện tại là số âm hoặc bằng 0, đặt lại thành 1
        inputSoLuongNguoi.value = 1;
    }

    // Cập nhật thuộc tính max của ô số lượng người
    inputSoLuongNguoi.setAttribute("max", maxSoLuongNguoi);
}
</script>


                            <!-- </div> -->

                            @endif
                            @endforeach

                        </div>


                        <input type="hidden" name="ngayBatDau" value="{{ $ngayBatDau }}">
                        <input type="hidden" name="ngayKetThuc" value="{{ $ngayKetThuc }}">


                        <div>
                            <h5>Không hoàn trả phí khi hủy phòng</h5>
                        </div>

                        <button type="submit" class="mt-3 py-1 px-3 h-10 inline-block tracking-wide align-middle duration-500 text-base text-center bg-red-500 text-white rounded-md cursor-pointer hover:bg-slate-800"><i class="fa-solid fa-cart-plus"></i></button>
                        <!-- <a class="mt-3 py-1 px-3 h-10 inline-block tracking-wide align-middle duration-500 text-base text-center bg-red-500 text-white rounded-md cursor-pointer hover:bg-slate-800" href="#">Chọn phòng</a> -->

                    </form>
                    <a class="mt-3 py-1 px-3 h-10 inline-block tracking-wide align-middle duration-500 text-base text-center bg-red-500 text-white rounded-md cursor-pointer hover:bg-slate-800 cd-trigger" href="#0">Xem chi tiết</a>

                </div>
            </div>
        </div>

        <div class="cd-quick-view mt-[150px]" data-id="{{ $loaiPhong->id }}">
            <div class="cd-slider-wrapper ">
                <ul class="cd-slider">
                    <li class="selected"><img src="{{ Storage::url($loaiPhong->anh) }}"></li>

                    <!-- <li><img src="https://www.quackit.com/pix/byron_bay_225x169.jpg"></li>
                                            <li><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRzKg15DFyzKJ_hdFHNW4SmN0O7f5yPkg-G4Yx1F_4vTHFtDaoeoef7uKleSVZG93-YnQ0&usqp=CAU"></li> -->
                </ul> <!-- cd-slider -->

                <ul class="cd-slider-navigation">
                    <li><a class="cd-next" href="#0">Prev</a></li>
                    <li><a class="cd-prev" href="#0">Next</a></li>
                </ul>
            </div>

            <div class="cd-item-info">

                <p class="flex items-center text-slate-400 font-medium mb-2"><i data-feather="map-pin" class="text-red-500 size-4 me-1"></i> Hà Nội, Việt Nam</p>
                <h2>{{ $loaiPhong->ten }}</h2>
                <h5 class="text-lg font-medium text-red-500"> {{ number_format($loaiPhong->gia, 0, ',', '.') }}₫</h5>
                <p class="text-slate-400">{{ $loaiPhong->mo_ta_ngan }}</p>

                @foreach ($phongs as $phong)
                @if ($phong['loai_phong']->id == $loaiPhong->id)
                <ul>
                    <p class="text-slate-400">Phòng trống: {{ $phong['available_rooms']->count() }}
                    </p>
                    @foreach ($phong['available_rooms'] as $room)
                    <li class="hidden">Phòng số: {{ $room->ten_phong }}</li>
                    @endforeach
                </ul>
                @endif
                @endforeach

                <!-- <p class="text-slate-400">Số lượng còn lại: {{ $loaiPhong->so_luong }}</p> <br> -->
                <p>Lưu ý: Không hoàn trả phí khi hủy phòng</p>

                <!-- <ul class="cd-item-action">
                                            <li>
                                                <button class="py-1 px-5 h-10 inline-block tracking-wide align-middle duration-500 text-base text-center bg-red-500 text-white rounded-md w-full cursor-pointer">Đặt ngay</button>
                                            </li>
                                        </ul>  -->


            </div> <!-- cd-item-info -->
            <a href="#0" class="cd-close">Đóng</a>
        </div> <!-- cd-quick-view -->




        @endforeach

    </div>

</section>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('.book-cart').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission behavior

            // Serialize form data
            let formData = $(this).serialize();
            formData += '&_token={{ csrf_token() }}';

            // Send Ajax request
            $.ajax({
                method: 'POST',
                // data: {
                //     _token: "{{ csrf_token() }}",
                data: formData,
                // },
                url: "{{ route('them_gio_hang') }}",
                // headers: {
                //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                // },
                success: function(data) {
                    if (data.status === 'success') {
                        getCartCount();
                        toastr.success(data.message);
                    } else if (data.status == "error") {
                        toastr.error(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error('Đã xảy ra lỗi trong khi xử lý yêu cầu của bạn.');
                }
            });
        });



        function getCartCount() {
            $.ajax({
                method: 'GET',
                url: "{{ route('cart-count') }}",
                success: function(data) {
                    console.log(data);
                    $('#cart-count').text(data)
                }
            })
        }
    });
</script>
@endpush
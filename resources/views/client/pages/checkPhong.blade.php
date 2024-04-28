@extends('client.layouts.master')
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#filterFormten').submit(function(event) {
            event.preventDefault(); // Ngăn chặn gửi yêu cầu mặc định
            var formData = $(this).serialize(); // Thu thập dữ liệu biểu mẫu
            $.ajax({
                url: $(this).attr('action'),
                type: 'GET',
                data: formData,
                success: function(data) {
                    // Xóa nội dung hiện tại của #room-info-container
                    $('#room-info-container').empty();

                    // Hiển thị thông tin của mỗi loại phòng từ dữ liệu trả về
                    data.rooms.forEach(function(room) {
                        var roomHtml = `

        <div class="group rounded-md shadow dark:shadow-gray-700 my-6">
    <div class="md:flex md:items-center cd-item-parent">
        <div class="relative overflow-hidden md:shrink-0 md:rounded-md rounded-t-md shadow dark:shadow-gray-700 md:m-3 mx-3 mt-3 cd-item">
            <img src="${room.anh}" class="h-full w-full object-cover md:w-50 md:h-56 scale-125 group-hover:scale-100 duration-500"  data-id="${room.id}" alt="">

            <div class="absolute top-0 start-0 p-4">
                <span class="bg-red-500 text-white text-xs px-2.5 py-1 font-medium rounded-md h-5">EasyStay</span>
            </div>
            <div class="absolute top-0 end-0 p-4">
                <a href="javascript:void(0)" class="size-8 inline-flex justify-center items-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-800 rounded-full text-slate-100 dark:text-slate-700 focus:text-red-500 dark:focus:text-red-500 hover:text-red-500 dark:hover:text-red-500"><i class="mdi mdi-heart text-lg align-middle"></i></a>
            </div>
        </div>
        <div class="p-4 ml-2 w-full">
            <p class="flex items-center text-slate-400 font-medium mb-2"><i data-feather="map-pin" class="text-red-500 size-4 me-1"></i> Hà Nội, Việt Nam</p>
            <form action="" class="book-cart">
                <input type="hidden" name="id" value="1">
                <a href="#" class="text-lg font-medium hover:text-red-500 duration-500 ease-in-out">${room.ten}</a>
                <div class="mt-2 pt-4 flex justify-between items-center border-t border-slate-100 dark:border-gray-800">
                    <h5 class="text-lg font-medium text-red-500">${room.gia}</h5> <br>
                </div>
                <div>
                    <ul>
                        <p>Phòng trống: ${room.phong_trong}</p>
                    
                    </ul>
                  
                    <div>
                        <label for="qty">Số lượng phòng muốn đặt:1</label>
                        <a type="number" id="qty" name="so_luong" min="1" value="1" max="${room.so_luong}" class=""1</a>
                    </div>
                    <div>
                        <label for="so_luong_nguoi">Số lượng người:</label>
                        <input type="number" name="so_luong_nguoi" id="so_luong_nguoi" value="1" min="1" max="${room.gioi_han_nguoi}" >
                    </div>
                </div>

            
                <input type="hidden" name="ngayBatDau" value="${room.ngayBatDau}">
                    <input type="hidden" name="ngayKetThuc" value="${room.ngayKetThuc}">

                <div>
                    <h5>Không hoàn trả phí khi hủy phòng</h5>
                </div>

                <button type="submit"
                                            class="mt-3 py-1 px-3 h-10 inline-block tracking-wide align-middle duration-500 text-base text-center bg-red-500 text-white rounded-md cursor-pointer hover:bg-slate-800"><i
                                                class="fa-solid fa-cart-plus"></i></button>            </form>

            <a class="mt-2 py-1 px-3 h-10 inline-block tracking-wide align-middle duration-500 text-base text-center bg-red-500 text-white rounded-md cursor-pointer hover:bg-slate-800 cd-trigger" href="#0">Xem chi tiết</a>
        </div>
    </div>
</div>


        
        

        `;
                        // Thêm phần tử HTML của phòng vào #room-info-container
                        $('#room-info-container').append(roomHtml);
                    });
                },
                error: function(xhr, textStatus, errorThrown) {
                    // Xử lý lỗi nếu có
                    console.error('Error:', errorThrown);
                    // Hiển thị thông báo lỗi
                    Toastify({
                        text: "Đã xảy ra lỗi khi lọc dữ liệu.",
                        duration: 3000,
                        gravity: "top", // Hiển thị thông báo ở phía dưới
                        position: 'center' // Canh giữa
                    }).showToast();
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#filterForm').submit(function(event) {
            event.preventDefault(); // Ngăn chặn gửi yêu cầu mặc định

            // Lấy giá trị min và max từ form
            var giaMin = parseInt($('#gia_min').val());
            var giaMax = parseInt($('#gia_max').val());

            // Kiểm tra nếu giá tối đa nhỏ hơn giá tối thiểu
            if (giaMin < 0 || giaMax < 0) {
                // Hiển thị thông báo lỗi
                Toastify({
                    text: "Giá tiền không thể là số âm.",
                    duration: 3000,
                    gravity: "top", // Hiển thị thông báo ở phía dưới
                    position: 'center' // Canh giữa
                }).showToast();
                return; // Dừng việc thực hiện các lệnh tiếp theo
            }


            // Kiểm tra nếu giá tối đa nhỏ hơn giá tối thiểu
            if (giaMax < giaMin) {
                // Hiển thị thông báo lỗi
                Toastify({
                    text: "Giá tối đa không thể nhỏ hơn giá tối thiểu.",
                    duration: 3000,
                    gravity: "top", // Hiển thị thông báo ở phía dưới
                    position: 'center' // Canh giữa
                }).showToast();
                return; // Dừng việc thực hiện các lệnh tiếp theo
            }

            if (isNaN(giaMin) || isNaN(giaMax)) {
                // Hiển thị thông báo lỗi
                Toastify({
                    text: "Giá tiền phải là số.",
                    duration: 3000,
                    gravity: "top", // Hiển thị thông báo ở phía dưới
                    position: 'center' // Canh giữa
                }).showToast();
                return; // Dừng việc thực hiện các lệnh tiếp theo
            }

            // Nếu không có lỗi, tiến hành gửi dữ liệu qua AJAX
            var formData = $(this).serialize(); // Thu thập dữ liệu biểu mẫu
            $.ajax({
                url: $(this).attr('action'),
                type: 'GET',
                data: formData,
                success: function(data) {
                    // Xóa nội dung hiện tại của phần tử để chuẩn bị hiển thị dữ liệu mới
                    $('#room-info-container').empty();

                    // Hiển thị thông tin của mỗi loại phòng thỏa mãn điều kiện lọc
                    data.rooms.forEach(function(room) {
                        // Kiểm tra giá của phòng có nằm trong khoảng từ giaMin đến giaMax không
                        if (room.gia >= giaMin && room.gia <= giaMax) {
                            var roomHtml = `
                             
        <div class="group rounded-md shadow dark:shadow-gray-700 my-6">
    <div class="md:flex md:items-center cd-item-parent">
        <div class="relative overflow-hidden md:shrink-0 md:rounded-md rounded-t-md shadow dark:shadow-gray-700 md:m-3 mx-3 mt-3 cd-item">
            <img src="${room.anh}" class="h-full w-full object-cover md:w-50 md:h-56 scale-125 group-hover:scale-100 duration-500"  data-id="${room.id}" alt="">

            <div class="absolute top-0 start-0 p-4">
                <span class="bg-red-500 text-white text-xs px-2.5 py-1 font-medium rounded-md h-5">EasyStay</span>
            </div>
            <div class="absolute top-0 end-0 p-4">
                <a href="javascript:void(0)" class="size-8 inline-flex justify-center items-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-800 rounded-full text-slate-100 dark:text-slate-700 focus:text-red-500 dark:focus:text-red-500 hover:text-red-500 dark:hover:text-red-500"><i class="mdi mdi-heart text-lg align-middle"></i></a>
            </div>
        </div>
        <div class="p-4 ml-2 w-full">
            <p class="flex items-center text-slate-400 font-medium mb-2"><i data-feather="map-pin" class="text-red-500 size-4 me-1"></i> Hà Nội, Việt Nam</p>
            <form action="" class="book-cart">
                <input type="hidden" name="id" value="1">
                <a href="#" class="text-lg font-medium hover:text-red-500 duration-500 ease-in-out">${room.ten}</a>
                <div class="mt-2 pt-4 flex justify-between items-center border-t border-slate-100 dark:border-gray-800">
                    <h5 class="text-lg font-medium text-red-500">${room.gia}</h5> <br>
                </div>
                <div>
                    <ul>
                        <p>Phòng trống: ${room.phong_trong}</p>
                    </ul>
                  
                    <div>
                        <label for="qty">Số lượng phòng muốn đặt:1</label>
                        <a type="number" id="qty" name="so_luong" min="1" value="1" max="${room.so_luong}" class=""1</a>
                    </div>
                    <div>
                        <label for="so_luong_nguoi">Số lượng người:</label>
                        <input type="number" name="so_luong_nguoi" id="so_luong_nguoi" value="1" min="1" max="${room.gioi_han_nguoi}" >
                    </div>
                </div>

            
                <input type="hidden" name="ngayBatDau" value="${room.ngayBatDau}">
                    <input type="hidden" name="ngayKetThuc" value="${room.ngayKetThuc}">

                <div>
                    <h5>Không hoàn trả phí khi hủy phòng</h5>
                </div>

                <button type="submit"
                                            class="mt-3 py-1 px-3 h-10 inline-block tracking-wide align-middle duration-500 text-base text-center bg-red-500 text-white rounded-md cursor-pointer hover:bg-slate-800"><i
                                                class="fa-solid fa-cart-plus"></i></button>            </form>

            <a class="mt-2 py-1 px-3 h-10 inline-block tracking-wide align-middle duration-500 text-base text-center bg-red-500 text-white rounded-md cursor-pointer hover:bg-slate-800 cd-trigger" href="#0">Xem chi tiết</a>
        </div>
    </div>
</div>


                            `;
                            $('#room-info-container').append(roomHtml);
                        }
                    });
                },
                error: function(xhr, textStatus, errorThrown) {
                    // Xử lý lỗi nếu có
                    console.error('Error:', errorThrown);
                    // Hiển thị thông báo lỗi
                    Toastify({
                        text: "Không có loại phòng phù hợp",
                        duration: 3000,
                        gravity: "top", // Hiển thị thông báo ở phía dưới
                        position: 'center' // Canh giữa
                    }).showToast();
                }
            });
        });
    });
</script>

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


<div class="flex justify-center mt-4">

    <form action="{{ route('client.pages.loai_phong.filter') }}" method="GET" id="filterFormten" class="flex items-center justify-between mb-6 mr-4">
        <div class="flex items-center space-x-4">
            <input type="text" name="ten_phong" required placeholder="Tên phòng" class="px-4 py-2 border border-gray-300 rounded-md" id="ten_phong">
            <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Lọc</button>
        </div>
    </form>


    <form action="{{ route('client.pages.loai_phong.filter') }}" id="filterForm" method="GET" class="mb-6 searchForm5  mr-4">

        <div class="flex justify-between items-center">
            <input id="gia_min" type="text" name="gia_min" required placeholder="Giá tối thiểu" class="px-4 py-2 border border-gray-300 rounded-md  mr-4 ">
            <input id="gia_max" type="text" name="gia_max" required placeholder="Giá tối đa" class="px-4 py-2 border border-gray-300 rounded-md  mr-4">
            <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Lọc</button>
        </div>

    </form>

</div>



<section class="relative  md:py-24 py-16">
    <div id="room-info-container" class="container grid grid-cols-2 gap-6">
        @foreach ($availableLoaiPhongs as $loaiPhong)

        <div class="group rounded-md shadow dark:shadow-gray-700 my-6  ">
            <div class="md:flex md:items-center cd-item-parent">
                <div class="relative overflow-hidden md:shrink-0 md:rounded-md rounded-t-md shadow dark:shadow-gray-700 md:m-3 mx-3 mt-3 cd-item">
                    <img src="{{ Storage::url($loaiPhong->anh) }}" class="h-full w-full object-cover md:w-50 md:h-56 scale-125 group-hover:scale-100 duration-500" data-id="{{ $loaiPhong->id }}" alt="">

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
                            <h5 class="text-lg font-medium text-red-500">{{ $loaiPhong->gia }}</h5> <br>
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
                            <div>
                                <label for="qty">Số lượng phòng muốn đặt:</label>
                                <input type="number" id="qty" name="so_luong" min="1" value="1" max="{{ $phong['available_rooms']->count() }}">
                            </div>

                            <div>
                                <label for="so_luong_nguoi">Số lượng người:</label>
                                <input type="number" name="so_luong_nguoi" id="so_luong_nguoi" value="1" min="1" max="{{ $phong['loai_phong']->gioi_han_nguoi }}">
                            </div>
                            @endif
                            @endforeach

                        </div>


                        <input type=" hidden" name="ngayBatDau" value="{{ $ngayBatDau }}">
                        <input type=" hidden" name="ngayKetThuc" value="{{ $ngayKetThuc }}">



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
                <h5 class="text-lg font-medium text-red-500">{{ $loaiPhong->gia }}</h5>
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
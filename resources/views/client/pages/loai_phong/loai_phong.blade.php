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
                        // Xóa nội dung hiện tại của phần tử để chuẩn bị hiển thị dữ liệu mới
                        $('#room-info-container').empty();

                        // Hiển thị thông tin của mỗi loại phòng
                        data.rooms.forEach(function(room) {
                            var roomHtml = `
            <div class="group rounded-md shadow dark:shadow-gray-700">
                <div class="md:flex md:items-center">
                    <div class="relative overflow-hidden md:shrink-0 md:rounded-md rounded-t-md shadow dark:shadow-gray-700 md:m-3 mx-3 mt-3">
                        <img src="${room.anh}" class="h-full w-full object-cover md:w-48 md:h-56 scale-125 group-hover:scale-100 duration-500" alt="">
                        <div class="absolute top-0 end-0 p-4">
                            <a href="javascript:void(0)" class="size-8 inline-flex justify-center items-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-800 rounded-full text-slate-100 dark:text-slate-700 focus:text-red-500 dark:focus:text-red-500 hover:text-red-500 dark:hover:text-red-500"><i class="mdi mdi-heart text-[20px] align-middle"></i></a>
                        </div>
                    </div>
                    <div class="p-4 w-full">
                        <p class="flex items-center text-slate-400 font-medium mb-2"><i data-feather="map-pin" class="text-red-500 size-4 me-1"></i> Hà Nội, Việt Nam</p>
                        <a href="#" class="font-medium hover:text-red-500 duration-500 ease-in-out">${room.ten}</a>
                        <div class="mt-4 pt-4 flex justify-between items-center border-t border-slate-100 dark:border-gray-800">
                            <h5 class="text-lg font-medium text-red-500">${room.gia}</h5>
                            <a href="<?= env('APP_URL') ?>/chi_tiet_loai_phong/${room.id}" class="text-slate-400 hover:text-red-500">Khám phá ngay<i class="mdi mdi-arrow-right"></i></a>
                        </div>
                        <div class="mt-3">
                            ${room.trang_thai == 0 ? '<button class="py-1 px-3 inline-block tracking-wide align-middle duration-500 text-base text-center bg-gray-500 text-white rounded-md">Hết phòng</button>' : ''}
                        </div>
                    </div>
                </div>
            </div><!--end content-->
        `;
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
                    gravity: "top",
                    position: 'right',
                    backgroundColor: "linear-gradient(to right, #ff416c, #ff4b2b)",
                    className: "info",
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
                    gravity: "top",
                    position: 'right',
                    backgroundColor: "linear-gradient(to right, #ff416c, #ff4b2b)",
                    className: "info",
                }).showToast();
                    return; // Dừng việc thực hiện các lệnh tiếp theo
                }


                // Kiểm tra nếu giá tối đa nhỏ hơn giá tối thiểu
                if (giaMax < giaMin) {
                    // Hiển thị thông báo lỗi
                    Toastify({
                        text: "Giá tối đa không thể nhỏ hơn giá tối thiểu.",
                        duration: 3000,
    gravity: "top",
    position: 'right',
    backgroundColor: "linear-gradient(to right, #ff416c, #ff4b2b)",
    className: "info",
}).showToast();
                    return; // Dừng việc thực hiện các lệnh tiếp theo
                }

                if (giaMax === giaMin) {
                    // Hiển thị thông báo lỗi
                    Toastify({
                        text: "Giá tối đa không thể bằng hơn giá tối thiểu.",
                        duration: 3000,
    gravity: "top",
    position: 'right',
    backgroundColor: "linear-gradient(to right, #ff416c, #ff4b2b)",
    className: "info",
}).showToast();
                    return; // Dừng việc thực hiện các lệnh tiếp theo
                }

                if (isNaN(giaMin) || isNaN(giaMax)) {
                    // Hiển thị thông báo lỗi
                    Toastify({
                        text: "Giá tiền phải là số.",
                        duration: 3000,
    gravity: "top",
    position: 'right',
    backgroundColor: "linear-gradient(to right, #ff416c, #ff4b2b)",
    className: "info",
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
                                <div class="group rounded-md shadow dark:shadow-gray-700">
                                    <div class="md:flex md:items-center">
                                        <div class="relative overflow-hidden md:shrink-0 md:rounded-md rounded-t-md shadow dark:shadow-gray-700 md:m-3 mx-3 mt-3">
                                            <img src="${room.anh}" class="h-full w-full object-cover md:w-48 md:h-56 scale-125 group-hover:scale-100 duration-500" alt="">
                                            <div class="absolute top-0 end-0 p-4">
                                                <a href="javascript:void(0)" class="size-8 inline-flex justify-center items-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-800 rounded-full text-slate-100 dark:text-slate-700 focus:text-red-500 dark:focus:text-red-500 hover:text-red-500 dark:hover:text-red-500"><i class="mdi mdi-heart text-[20px] align-middle"></i></a>
                                            </div>
                                        </div>
                                        <div class="p-4 w-full">
                                            <p class="flex items-center text-slate-400 font-medium mb-2"><i data-feather="map-pin" class="text-red-500 size-4 me-1"></i> Hà Nội, Việt Nam</p>
                                            <a href="#" class="font-medium hover:text-red-500 duration-500 ease-in-out">${room.ten}</a>
                                            <div class="mt-4 pt-4 flex justify-between items-center border-t border-slate-100 dark:border-gray-800">
                                                <h5 class="text-lg font-medium text-red-500">${room.gia}</h5>
                                                <a href="<?= env('APP_URL') ?>/chi_tiet_loai_phong/${room.id}" class="text-slate-400 hover:text-red-500">Khám phá ngay<i class="mdi mdi-arrow-right"></i></a>
                                            </div>
                                            <div class="mt-3">
                                                ${room.trang_thai == 0 ? '<button class="py-1 px-3 inline-block tracking-wide align-middle duration-500 text-base text-center bg-gray-500 text-white rounded-md">Hết phòng</button>' : ''}
                                            </div>
                                        </div>
                                    </div>
                                </div><!--end content-->
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
    gravity: "top",
    position: 'right',
    backgroundColor: "linear-gradient(to right, #ff416c, #ff4b2b)",
    className: "info",
}).showToast();
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#filterFormtrangthai').submit(function(event) {
                event.preventDefault(); // Ngăn chặn gửi yêu cầu mặc định
                var formData = $(this).serialize(); // Thu thập dữ liệu biểu mẫu
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'GET',
                    data: formData,
                    success: function(data) {
                        // Xóa nội dung hiện tại của phần tử để chuẩn bị hiển thị dữ liệu mới
                        $('#room-info-container').empty();

                        // Hiển thị thông tin của mỗi loại phòng
                        data.rooms.forEach(function(room) {
                            var statusText = room.trang_thai == 0 ? 'Hết phòng' :
                                'Còn phòng';
                            var statusClass = room.trang_thai == 0 ?
                                'bg-gray-500 text-white' : 'bg-green-500 text-white';

                            var roomHtml = `
                            <div class="group rounded-md shadow dark:shadow-gray-700">
                                <div class="md:flex md:items-center">
                                    <div class="relative overflow-hidden md:shrink-0 md:rounded-md rounded-t-md shadow dark:shadow-gray-700 md:m-3 mx-3 mt-3">
                                        <img src="${room.anh}" class="h-full w-full object-cover md:w-48 md:h-56 scale-125 group-hover:scale-100 duration-500" alt="">
                                        <div class="absolute top-0 end-0 p-4">
                                            <a href="javascript:void(0)" class="size-8 inline-flex justify-center items-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-800 rounded-full text-slate-100 dark:text-slate-700 focus:text-red-500 dark:focus:text-red-500 hover:text-red-500 dark:hover:text-red-500"><i class="mdi mdi-heart text-[20px] align-middle"></i></a>
                                        </div>
                                    </div>
                                    <div class="p-4 w-full">
                                        <p class="flex items-center text-slate-400 font-medium mb-2"><i data-feather="map-pin" class="text-red-500 size-4 me-1"></i> Hà Nội, Việt Nam</p>
                                        <a href="#" class="font-medium hover:text-red-500 duration-500 ease-in-out">${room.ten}</a>
                                        <div class="mt-4 pt-4 flex justify-between items-center border-t border-slate-100 dark:border-gray-800">
                                            <h5 class="text-lg font-medium text-red-500">${room.gia}</h5>
                                            <a href="<?= env('APP_URL') ?>/chi_tiet_loai_phong/${room.id}" class="text-slate-400 hover:text-red-500">Khám phá ngay<i class="mdi mdi-arrow-right"></i></a>
                                        </div>
                                        <div class="mt-3">
                                            <button class="py-1 px-3 inline-block tracking-wide align-middle duration-500 text-base text-center rounded-md ${statusClass}">${statusText}</button>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end content-->
                        `;
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
    gravity: "top",
    position: 'right',
    backgroundColor: "linear-gradient(to right, #ff416c, #ff4b2b)",
    className: "info",
}).showToast();
                    }
                });
            });
        });
    </script>
<script>
$(document).ready(function() {
    // Hiển thị menu dropdown khi nhấp vào nút
    $('#dropdownDelayButton').click(function() {
        $('#dropdownDelay').toggleClass('hidden');
    });

    // Đóng menu dropdown khi người dùng click vào nút lọc dữ liệu
    $('#filterFormten, #filterForm, #filterFormtrangthai').submit(function(event) {
        // Ngăn chặn hành động mặc định của form
        event.preventDefault();

        // Đóng menu dropdown bằng cách thêm lớp hidden
        $('#dropdownDelay').addClass('hidden');

        // Tiến hành lọc dữ liệu...
        // (Thêm mã xử lý lọc dữ liệu của bạn ở đây)
    });
});

</script>


<script>
    $(document).ready(function() {
        // Xử lý sự kiện click cho nút "Xóa bộ lọc"
        $('#clearFiltersButton').click(function() {
            // Lấy giá trị của ba ô input
            var tenPhongValue = $('#ten_phong').val().trim();
            var giaMinValue = $('#gia_min').val().trim();
            var giaMaxValue = $('#gia_max').val().trim();
            
            // Kiểm tra xem có ít nhất một ô input có dữ liệu hay không
            if (tenPhongValue === '' && giaMinValue === '' && giaMaxValue === '') {
                // Nếu không có ô nào có dữ liệu, hiển thị toast thông báo
                Toastify({
    text: "Bạn chưa nhập thông tin.",
    duration: 3000,
    gravity: "top",
    position: 'right',
    backgroundColor: "linear-gradient(to right, #ff416c, #ff4b2b)",
    className: "info",
}).showToast();
                return;
            }
            
            // Nếu có ít nhất một ô input có dữ liệu, tiến hành xóa bộ lọc và chuyển hướng
            // Reset giá trị của các ô input
            $('#ten_phong').val('');
            $('#gia_min').val('');
            $('#gia_max').val('');
            // Reset giá trị của dropdown chọn trạng thái về giá trị mặc định
            $('#trang_thai').val('');
            
            // Chuyển hướng về trang loai_phong
            window.location.href = '<?= env('APP_URL') ?>/loai_phong';
        });
    });
</script>


    <section
        class="relative table w-full items-center py-36 bg-[url('../../assets/images/bg/cta.html')] bg-top bg-no-repeat bg-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 via-slate-900/80 to-slate-900">

        </div>
        <div class="container relative">
            <div class="grid grid-cols-1 pb-8 text-center mt-10">
                <h3 class="font-bold text-white lg:leading-normal leading-normal text-4xl lg:text-2xl mb-6 mt-5">LOẠI PHÒNG
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
                    aria-current="page">Loại phòng</li>
            </ul>
        </div>
    </section><!--end section-->
    <!-- End Hero -->

    <div class="grid lg:grid-cols-2 grid-cols-1 my-6 gap-6 container">
        <!-- Form Lọc -->
        <div class="col-span-2 ">

            <form class="p-6 bg-white dark:bg-slate-900 rounded-xl shadow dark:shadow-gray-700" method="post"
                action="{{ route('kiem_tra_phong') }}">
                @csrf
                <div class="registration-form text-dark text-start">
                    <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-4">
                        <div>
                            <label class="form-label font-medium text-slate-900 dark:text-white">Lựa chọn ngày đến:</label>
                            <div class="relative mt-2">
                                <i data-feather="calendar" class="size-[18px] absolute top-[10px] start-3"></i>
                                <input name="thoi_gian_den" required type="date" id="thoi_gian_den"
                                    class="w-full py-2 px-3 ps-10 h-10 bg-transparent dark:bg-slate-900 dark:text-slate-200 rounded-md outline-none border border-gray-100 dark:border-gray-800 focus:ring-0 "
                                    min="{{ date('Y-m-d') }}" placeholder="Lựa chọn ngày đến">
                            </div>
                        </div>

                        <div>
                            <label class="form-label font-medium text-slate-900 dark:text-white">Lựa chọn ngày đi:</label>
                            <div class="relative mt-2">
                                <i data-feather="calendar" class="size-[18px] absolute top-[10px] start-3"></i>
                                <input name="thoi_gian_di" required type="date" id="thoi_gian_di"
                                    class="w-full py-2 px-3 ps-10 h-10 bg-transparent dark:bg-slate-900 dark:text-slate-200 rounded-md outline-none border border-gray-100 dark:border-gray-800 focus:ring-0 "
                                    min="{{ date('Y-m-d') }}" placeholder="Lựa chọn ngày đi">
                            </div>
                        </div>
                        <div class="lg:mt-[35px]">
                            <input type="submit" id="search-buy" name="search"
                                class="py-1 px-5 h-10 inline-block tracking-wide align-middle duration-500 text-base text-center bg-red-500 text-white rounded-md w-full cursor-pointer"
                                value="Tìm kiếm">
                        </div>
                    </div>
                </div>
            </form>



           

        

         
                <button id="dropdownDelayButton" data-dropdown-toggle="dropdownDelay" data-dropdown-delay="500" data-dropdown-trigger="hover" class=" mt-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Bộ Lọc <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
</svg>
</button>

<!-- Dropdown menu -->
<div id="dropdownDelay" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700 ">
    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200 mt-4" aria-labelledby="dropdownDelayButton">
      <li>
      <form action="{{ route('client.pages.loai_phong.filter') }}" method="GET" id="filterFormten"
                    class="flex items-center justify-between mb-6 mr-4">
                    <div class="flex items-center space-x-4">
                        <input type="text" name="ten_phong" required placeholder="Tên phòng"
                            class="px-4 py-2 border border-gray-300 rounded-md" id="ten_phong">
                        <button type="submit"
                            class="px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Lọc</button>
                    </div>
                </form>

      </li>
      <li>
                <form action="{{ route('client.pages.loai_phong.filter') }}" id="filterForm" method="GET"
                    class="mb-6 searchForm5  mr-4">

                    <div class="flex justify-between items-center">
                        <input id="gia_min" type="text" name="gia_min" required placeholder="Giá tối thiểu"
                            class="px-4 py-2 border border-gray-300 rounded-md  mr-4 ">
                        <input id="gia_max" type="text" name="gia_max" required placeholder="Giá tối đa"
                            class="px-4 py-2 border border-gray-300 rounded-md  mr-4">
                        <button type="submit"
                            class="px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Lọc</button>
                    </div>

                </form>
      </li>
      <li>
      <form action="{{ route('client.pages.loai_phong.filter') }}" id="filterFormtrangthai" method="GET"
                    class="flex items-center justify-between mb-6 mr-4">
                    <div class="flex items-center space-x-4">
                        <!-- Dropdown để lọc theo trạng thái -->
                        <select required name="trang_thai" class="px-4 py-2 border border-gray-300 rounded-md">
                            <option value="">Chọn trạng thái</option>
                            <option value="1">Còn phòng</option>
                            <option value="0">Hết phòng</option>
                        </select>
                        <!-- Nút submit để thực hiện lọc -->
                        <button type="submit"
                            class="px-6 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 ">Lọc</button>
                    </div>
                </form>
      </li>
    <li>
    <button id="clearFiltersButton" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Xóa bộ lọc</button>

                </button>
    </li>
    </ul>
</div>



        </div>
    </div><!--end grid-->

    {{-- div --}}
    <div id="room-info-container" class="container grid grid-cols-2 gap-6">

        <!-- Danh sách loại phòng -->
        @foreach ($rooms as $room)
            <div class="group rounded-md shadow dark:shadow-gray-700">
                <div class="md:flex md:items-center">
                    <div
                        class="relative overflow-hidden md:shrink-0 md:rounded-md rounded-t-md shadow dark:shadow-gray-700 md:m-3 mx-3 mt-3">
                        <img src="{{ Storage::url($room->anh) }}"
                            class="h-full w-full object-cover md:w-48 md:h-56 scale-125 group-hover:scale-100 duration-500"
                            alt="">
                        <div class="absolute top-0 end-0 p-4">
                            <a href="javascript:void(0)"
                                class="size-8 inline-flex justify-center items-center bg-white dark:bg-slate-900 shadow dark:shadow-gray-800 rounded-full text-slate-100 dark:text-slate-700 focus:text-red-500 dark:focus:text-red-500 hover:text-red-500 dark:hover:text-red-500"><i
                                    class="mdi mdi-heart text-[20px] align-middle"></i></a>
                        </div>
                    </div>
                    <div class="p-4 w-full">
                        <p class="flex items-center text-slate-400 font-medium mb-2"><i data-feather="map-pin"
                                class="text-red-500 size-4 me-1"></i> Hà Nội, Việt Nam</p>
                        <a href="<?= env('APP_URL') ?>/chi_tiet_loai_phong/<?= $room->id ?>"
                            class="font-medium hover:text-red-500 duration-500 ease-in-out">{{ $room->ten }}</a>
                        <div
                            class="mt-4 pt-4 flex justify-between items-center border-t border-slate-100 dark:border-gray-800">
                            <h5 class="text-lg font-medium text-red-500">{{ $room->gia }}</h5>
                            <a href="<?= env('APP_URL') ?>/chi_tiet_loai_phong/<?= $room->id ?>"
                                class="text-slate-400 hover:text-red-500">Khám phá ngay<i
                                    class="mdi mdi-arrow-right"></i></a>
                        </div>
                        <div class="mt-3">
                            @if ($room->trang_thai == 0)
                                <button
                                    class="py-1 px-3 inline-block tracking-wide align-middle duration-500 text-base text-center bg-gray-500 text-white rounded-md">Hết
                                    phòng</button>
                            @endif
                        </div>
                    </div>
                </div>

            </div><!--end content-->
        @endforeach




    </div>
@endsection

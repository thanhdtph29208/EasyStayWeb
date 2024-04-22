@extends('client.layouts.master')
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
@section('content')

<section class="relative table w-full items-center py-10 bg-[url('../../assets/images/bg/cta.html')] bg-top bg-no-repeat bg-cover">
    <div class="absolute inset-0 bg-gradient-to-b from-slate-900/60 via-slate-200/20 to-slate-900"></div>
    <div class="container relative">
        <div class=" pb-8 text-center mt-10">

        </div><!--end grid-->
    </div><!--end container-->
    <div class="absolute text-center z-10 bottom-5 start-0 end-0 mx-3">

    </div>
</section><!--end section-->

<div class="mx-auto text-center mt-8 font-medium text-xl		 ">
    <p> Thông tin chi tiết đơn đặt phòng</p>
</div>
<div class="container mx-auto py-8 ">

    <div class="w-full ">


        <article class="gap-8 grid grid-cols-2">

            <div class="col-span-2 mt-6 md:mt-0  ">
                <div class="flex justify-between items-start mb-5">
                    <div class="pe-4">
                        <footer class="flex font-medium mb-2">
                            <p class="mr-20  text-lg text-gray-900 dark:text-gray-400">Thời gian đến: <time datetime="">{{$userBooking->thoi_gian_den}}</time></p>

                            <p class=" text-lg text-gray-900 dark:text-gray-400">Thời gian đi: <time datetime="">{{$userBooking->thoi_gian_di}}</time></p>

                        </footer>

                    </div>
                </div>


                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase dark:text-gray-400">
                            <tr class="font-medium text-sm">
                                <th scope="col" class="px-6 py-3">
                                    Loại Phòng
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tên Loại Phòng
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Số lượng Phòng
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Đánh giá phòng
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loaiPhong as $phong)
                            <tr class="border-b border-gray-200 dark:border-gray-700">
                                <td scope="row" class="px-6 py-4">
                                    {{ $phong[0] }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $phong[1] }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $phong[2] }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('danh_gia_loai_phong', $phong[0]) }}" class="text-blue-500 hover:text-blue-700">Đánh giá phòng</a>
                                </td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>



            </div>

            <div>
                <div>
                    <h3 class="font-medium dark:text-white text-lg mb-6"> Thông tin khách hàng</h3>
                </div>

                <ul class="space-y-4 text-sm text-gray-500 dark:text-gray-400">
                    <li class="flex items-center">
                        <h4>Họ tên: {{ $userBooking->ho_ten }}</h4>

                    </li>
                    <li class="flex items-center">
                        <h4>Email: {{ $userBooking->email }}</h4>

                    </li>
                    <li class="flex items-center">
                        <h4>Số điện thoại: {{ $userBooking->so_dien_thoai }}</h4>
                    </li>
                    <li class="flex items-center">
                        <h4>Số lượng người: {{ $userBooking->so_luong_nguoi }}</h4>
                    </li>
                    <p>
                        Hình thức thanh toán: <span class="fw-bold">{{$userBooking->payment}}</span></p>


                    <li>

<button type="button" class="btn btn-outline-primary">
                            <a href=" {{ route('client.pages.lich_su_dat_phong') }}">
                                Quay lại
                            </a>
                        </button>

                    </li>
                </ul>
            </div>


            <div>
                <div>
                    <h3 class="font-medium dark:text-white text-lg mb-6"> Thông tin đơn hàng</h3>
                </div>
                <ul class="space-y-4 text-sm text-gray-500 dark:text-gray-400">
                    <li class="flex items-center">
                        Mã giảm giá:
                        <?php
                        if ($userBooking->khuyen_Mai !== null) {
                            // Nếu đối tượng "khuyen_Mai" tồn tại, thì truy cập thuộc tính "ma_giam_gia"
                            $gia_tri_giam =  $userBooking->khuyen_Mai->gia_tri_giam;
                            echo  $gia_tri_giam;
                            if ($userBooking->khuyen_Mai->loai_giam_gia == 1) {
                                echo "%";
                            }
                        } else {
                            // Nếu đối tượng "khuyen_Mai" không tồn tại, hiển thị thông báo tương ứng
                            echo "Bạn không sử dụng mã giảm giá";
                        }
                            ?>
                    </li>
                    <li class="flex items-center">
                        Ghi chú:
                        <?php
                        $ghi_chu = $userBooking->ghi_chu;

                        if (!empty($ghi_chu)) {
                            echo $ghi_chu;
                        } else {
                            echo "Chưa có ghi chú";
                        }
                        ?>
                    </li>
                    <li class="flex items-center">
                        Tổng tiền phòng:
                        <?php
                        $tong_tien = $userBooking->tong_tien; // giả sử $DatPhong->tong_tien chứa giá trị 3520000
                        // Định dạng số thành chuỗi dạng tiền tệ
                        $tong_tien_format = number_format($tong_tien, 0, ',', '.');
                        echo $tong_tien_format . " VNĐ"; // Kết quả sẽ là 3.520.000 VNĐ
                        ?>
                    </li>
                    <p>Trạng thái: <span class="{{ $userBooking->trang_thai ? 'badge text-bg-success' : 'badge text-bg-danger' }} fw-bold">{{ $userBooking->trang_thai ? 'Xác nhận' : 'Chưa xác nhận' }}</span></p>

                </ul>
            </div>
    </div>
    </article>



</div>
</div>







@endsection
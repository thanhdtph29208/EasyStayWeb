@extends('client.layouts.master')
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
                </ul>
            </div>
            <div>
                <p class="dark:text-white text-lg mb-6">Cảm ơn khách hàng đã tin tưởng và đồng hàng cùng chúng tôi!</p>


                <ul class="space-y-4 text-sm text-gray-500 dark:text-gray-400">


            <li>

            <a href="<?= env('APP_URL') ?>/chi_tiet_loai_phong/<?= $userBooking->ho_ten  ?>" class="text-slate-900 text-lg hover:text-red-500"> Đánh giá phòng <i class="mdi mdi-arrow-right"></i></a>

            </li>

                </ul>
            </div>
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
                            <tr class="font-medium text-sm		  ">
                                <th scope="col" class="px-6 py-3 ">
                                    Loại Phòng
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tên Loại Phòng
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Số lượng Phòng
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($loaiPhong as $loai_phong)
                            <tr class="border-b border-gray-200 dark:border-gray-700 ">
                                <td scope="row" class="px-6 py-4 ">
                                    {{ $loai_phong[0] }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $loai_phong[1] }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $loai_phong[2] }}
                                </td>
                            </tr>
                        @endforeach
                            </tbody>
                    </table>
                    <div style="float:right">
                        <h3 style="padding-top: 10px;">Mã giảm giá:
                            <?php
                                if ($userBooking->khuyen_Mai !== null) {
                                    // Nếu đối tượng "khuyen_Mai" tồn tại, thì truy cập thuộc tính "ma_giam_gia"
                                    $ma_giam_gia = $userBooking->khuyen_Mai->ma_giam_gia;
                                    echo $ma_giam_gia;
                                } else {
                                    // Nếu đối tượng "khuyen_Mai" không tồn tại, hiển thị thông báo tương ứng
                                    echo "Bạn không có mã giảm giá";
                                }
                            ?>
                        </h3>
                        <h3 style="padding-top: 3px;">Giá trị giảm:
                            {{ $khuyen_mai[0]->gia_tri_giam }}
                            @if( $khuyen_mai[0]->loai_giam_gia = 1 )
                                %
                            @endif
                        </h3>
                        <h3 style="padding-top: 3px;">Ghi chú:
                            <?php
                                $ghi_chu = $userBooking->ghi_chu;

                                if (!empty($ghi_chu)) {
                                    echo $ghi_chu;
                                } else {
                                    echo "Bạn chưa có ghi chú";
                                }
                            ?>
                        </h3>
                        <h3 style="padding-top: 3px;">Tổng tiền phòng:
                            <?php
                                $tong_tien = $userBooking->tong_tien; // giả sử $DatPhong->tong_tien chứa giá trị 3520000
                                // Định dạng số thành chuỗi dạng tiền tệ
                                $tong_tien_format = number_format($tong_tien, 0, ',', '.');
                                echo $tong_tien_format . " VNĐ"; // Kết quả sẽ là 3.520.000 VNĐ
                            ?>
                        </h3>
                        <h2 style="font-size: 20px; padding-top:15px; color:red">Tổng tiền phải trả hiện tại (chưa có dịch vụ): {{ number_format($thanh_tien[0], 0, ',', '.') }} VNĐ</h2>
                    </div>
                </div>

            </div>
        </article>



    </div>
</div>







@endsection

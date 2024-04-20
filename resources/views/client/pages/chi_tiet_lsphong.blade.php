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
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6  mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                        </svg>
                        {{ $userBooking->ho_ten }}
                    </li>
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Zm0 0c0 1.657 1.007 3 2.25 3S21 13.657 21 12a9 9 0 1 0-2.636 6.364M16.5 12V8.25" />
                        </svg>
                        {{ $userBooking->email }}
                    </li>
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75V18m-7.5-6.75h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V13.5Zm0 2.25h.008v.008H8.25v-.008Zm0 2.25h.008v.008H8.25V18Zm2.498-6.75h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V13.5Zm0 2.25h.007v.008h-.007v-.008Zm0 2.25h.007v.008h-.007V18Zm2.504-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5Zm0 2.25h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V18Zm2.498-6.75h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V13.5ZM8.25 6h7.5v2.25h-7.5V6ZM12 2.25c-1.892 0-3.758.11-5.593.322C5.307 2.7 4.5 3.65 4.5 4.757V19.5a2.25 2.25 0 0 0 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V4.757c0-1.108-.806-2.057-1.907-2.185A48.507 48.507 0 0 0 12 2.25Z" />
                        </svg>
                        {{ $userBooking->so_dien_thoai }}
                    </li>
                </ul>
            </div>
            <div>
                <p class="dark:text-white text-lg mb-6">Cảm ơn khách hàng đã tin tưởng và đồng hàng cùng chúng tôi!</p>


                <ul class="space-y-4 text-sm text-gray-500 dark:text-gray-400">
                
                    
            <li>

            <a href="<?= env('APP_URL') ?>/chi_tiet_loai_phong/<?= $userBooking->Loai_phong->id  ?>" class="text-slate-900 text-lg hover:text-red-500"> Đánh giá phòng <i class="mdi mdi-arrow-right"></i></a>

            </li>
                    
                </ul>
            </div>
            <div class="col-span-2 mt-6 md:mt-0  ">
                <div class="flex justify-between items-start mb-5">
                    <div class="pe-4">
                        <footer class="flex font-medium mb-2">
                            <p class="mr-20  text-lg text-gray-900 dark:text-gray-400">Thời gian đến: <time datetime="">{{ $thoi_gian_den->toDateString() }}</time></p>

                            <p class=" text-lg text-gray-900 dark:text-gray-400">Thời gian đi: <time datetime="">{{ $thoi_gian_di->toDateString() }}</time></p>

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
                                    Tên Phòng
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Số lượng Phòng
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Số lượng người
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Mã giảm giá
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Ghi chú
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Dịch vụ
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Tổng tiền
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-gray-200 dark:border-gray-700 ">
                                <th scope="row" class="px-6 py-4 ">
                                    {{ $userBooking->Loai_phong->ten }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $userBooking->phong->ten_phong }}
                                </td>
                                <td class="px-6 py-4 bg-gray-50 dark:bg-gray-800">
                                    {{ $userBooking->so_luong_phong }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $userBooking->so_luong_nguoi }}
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    if ($userBooking->khuyen_Mai !== null) {
                                        // Nếu đối tượng "khuyen_Mai" tồn tại, thì truy cập thuộc tính "ma_giam_gia"
                                        $ma_giam_gia = $userBooking->khuyen_Mai->ma_giam_gia;
                                        echo $ma_giam_gia;
                                    } else {
                                        // Nếu đối tượng "khuyen_Mai" không tồn tại, hiển thị thông báo tương ứng
                                        echo "Bạn chưa sử dụng dịch vụ";
                                    }
                                    ?>


                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $ghi_chu = $userBooking->ghi_chu;

                                    if (!empty($ghi_chu)) {
                                        echo $ghi_chu;
                                    } else {
                                        echo "Bạn chưa có ghi chú";
                                    }
                                    ?>
                                </td>

                                <td class="px-6 py-4">
                                    {{ $userBooking->dichVu->ten_dich_vu  }}({{ $userBooking->dichVu->so_luong  }})
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                        $tong_tien = $userBooking->tong_tien; // giả sử $DatPhong->tong_tien chứa giá trị 3520000
                        // Định dạng số thành chuỗi dạng tiền tệ
                        $tong_tien_format = number_format($tong_tien, 0, ',', '.');
                        echo $tong_tien_format . " VNĐ"; // Kết quả sẽ là 3.520.000 VNĐ
                        ?>
                                </td>

                            </tr>

                        </tbody>
                    </table>
                </div>

            </div>
        </article>



    </div>
</div>







@endsection
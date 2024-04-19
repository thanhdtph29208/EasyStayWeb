@extends('client.layouts.master')
@section('content')



<div class="container mx-auto py-8 ">
    <!-- Grid with Two Columns -->

    <div class="grid w-full  gap-4 mt-28">
        <h3 class="text-3xl leading-normal tracking-wider font-semibold mb-4 ">Chi tiết đơn hàng </h3>


        <!-- First Column -->

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-800 dark:text-gray-800">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Khách hàng
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Loại Phòng
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Tên Phòng
                        </th>
                    
                        <th scope="col" class="px-6 py-3">
                            Số phòng
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Số người
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Khuyến mãi
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Ngày đến
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Ngày đi
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Ghi chú
                        </th>
                        <th scope="col" class="px-6 py-3">
                          Dịch vụ
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Tổng Tiền
                        </th>
                    </tr>
                </thead>
                <tbody>

                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $userBooking->user->ten_nguoi_dung }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $userBooking->Loai_phong->ten }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $userBooking->phong->ten_phong  }}
                        </td>
                      
                        <td class="px-6 py-4">
                            {{ $userBooking->so_luong_phong }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $userBooking->so_luong_nguoi }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $userBooking->khuyen_Mai->ma_giam_gia}} <br>{{ $userBooking->khuyen_Mai->gia_tri_giam }}%
                        </td>
                        <td class="px-6 py-4">
                            {{ $thoi_gian_den->toDateString() }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $thoi_gian_di->toDateString() }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $userBooking->ghi_chu }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $userBooking->dichVu->ten_dich_vu  }}
                            <br>  {{ $userBooking->dichVu->so_luong  }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $userBooking->tong_tien }}
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>



    </div>
</div>







@endsection
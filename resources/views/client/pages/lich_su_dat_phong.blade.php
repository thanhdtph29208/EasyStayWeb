@extends('client.layouts.master')
@section('content')

<div class="container mx-auto py-8 ">
    <!-- Grid with Two Columns -->

    <div class="grid  mt-28">
        <!-- First Column -->
        <h3 class="text-3xl leading-normal tracking-wider font-semibold mb-4 ">Lịch sử đặt phòng </h3>
        <div class="bg-white rounded-lg  shadow-md">

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-800 dark:text-gray-800">
        <thead class="text-base text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
            <th scope="col" class="px-8 py-3">
                   Mã đơn đặt
                </th>
                <th scope="col" class="px-8 py-3">
                    Khách hàng
                </th>
                <th scope="col" class="px-8 py-3">
                    Email
                </th>
                <th scope="col" class="px-8 py-3">
                    Loại phòng
                </th>
                <th scope="col" class="px-8 py-3">
                    Số lượng phòng
                </th>
                <th scope="col" class="px-8 py-3">
                    Tổng tiền
                </th>
                <th scope="col" class="px-8 py-3">
                   Thao tác
                </th>
            </tr>
        </thead>
    
        <tbody>
        @foreach($userBookings as $DatPhong)

            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
            <td class="px-8 py-4">
                {{ $DatPhong->thoi_gian_den}}
                </td>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                {{ $DatPhong->user->ten_nguoi_dung }}
                </th>
                <td class="px-8 py-4">
                {{ $DatPhong->user->email  }}
                </td>
                <td class="px-8 py-4">
                {{ $DatPhong->Loai_phong->ten }}
                </td>
                <td class="px-8 py-4">
                {{ $DatPhong->so_luong_phong }}
                </td>
                <td class="px-8 py-4">
                {{ $DatPhong->tong_tien }}
                </td>
                <td class="px-8 py-4 text-right">
                    <a href="{{ route('chi_tiet_lsphong', $DatPhong->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">Xem chi tiết đơn đặt</a>
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>
</div>
        </div>
        <!-- Second Column -->




    </div>
</div>

@endsection
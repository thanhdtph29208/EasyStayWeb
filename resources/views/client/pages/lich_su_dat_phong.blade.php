@extends('client.layouts.master1')
@section('content')

<div class="container mx-auto py-8 ">
        <!-- Grid with Two Columns -->
        <div class="grid grid-cols-[1200px_minmax(800px,_1fr)_100px] gap-4 mt-28">
            <!-- First Column -->

            <div class="bg-white rounded-lg  shadow-md">
                
            <table class="table-auto mx-aotu">
                <tr>
                <th class="p-4">Khách hàng</th>
                <th class="p-4">Email</th>
                    <th class="p-4">Loại Phòng</th>
                    <th class="p-4">Số lượng người</th>
                    <th class="p-4">Số lượng phòng</th>
                    <th class="p-4">Khuyến mãi</th>
                    <th class="p-4">Thời gian đến</th>
                    <th class="p-4">Thời gian đi</th>
                    <th class="p-4">Ghi chú</th>
                    <th class="p-4">Tổng Tiền</th>

                </tr>
                @foreach($userBookings as $DatPhong)
                <tr>
                
                <th class="p-4"> {{ $DatPhong->user->ten_nguoi_dung }}</th>
                <th class="p-4"> {{ $DatPhong->user->email  }}</th>
                <th class="p-4"> {{ $DatPhong->Loai_phong->ten }}</th>
                <th class="p-4"> {{ $DatPhong->so_luong_nguoi }}</th>
                <th class="p-4"> {{ $DatPhong->so_luong_phong }}</th>
                <th class="p-4"> {{ $DatPhong->Khuyen_Mai->ma_giam_gia}} ({{ $DatPhong->Khuyen_Mai->gia_tri_giam}})</th>
                <th class="p-4"> {{ $DatPhong->thoi_gian_den }}</th>
                <th class="p-4"> {{ $DatPhong->thoi_gian_di }}</th>
                    <th class="p-4"> {{ $DatPhong->ghi_chu }}</th>
                    <th class="p-4">  {{ $DatPhong->tong_tien }}</th>
                
                </tr>
                @endforeach
            </table>
            </div>
            <!-- Second Column -->


          
   
        </div>
    </div>   

         


@endsection

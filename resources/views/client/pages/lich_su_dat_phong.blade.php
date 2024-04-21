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

<div class="container mx-auto py-8 ">
    <!-- Grid with Two Columns -->

    <div class="grid ">
        <!-- First Column -->
        <h3 class="text-3xl leading-normal tracking-wider font-semibold mb-4 ">Lịch sử đặt phòng </h3>
        <div class="">

        <div class="">
        <table id="example" class="table table-striped">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên khách hàng</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Thời gian đến</th>
                <th>Thời gian đi</th>
                <th>Tổng tiền</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
        <?php $i=1;$count = 0 ?>
        @foreach($userBookings as $DatPhong)
        @if($DatPhong->ho_ten !== null && $DatPhong->email !== null && $DatPhong->so_dien_thoai !== null)
        <tr>
            <td style="text-align: center;">{{ $i++ }}</td>
            <td>{{ $DatPhong->ho_ten }}</td>
            <td>{{ $DatPhong->email }}</td>
            <td>{{ $DatPhong->so_dien_thoai }}</td>
            <td>{{ $DatPhong->thoi_gian_den }}</td>
            <td>{{ $DatPhong->thoi_gian_di }}</td>
            <td>
                <?php
                    $tong_tien = $DatPhong->tong_tien;
                    $tong_tien_format = number_format($tong_tien, 0, ',', '.');
                    echo $tong_tien_format . " VNĐ";
                ?>
            </td>
            <td>
                <ul>
                    <li>
                        <a href="{{ route('chi_tiet_lsphong', $DatPhong->id) }}" class="no-underline font-medium text-blue-600 dark:text-blue-500 hover:underline">Xem chi tiết đơn đặt</a>
                    </li>
                </ul>
            </td>
        </tr>
        @else
        <tr>
            <td style="text-align: center;">{{ $i++ }}</td>
            <td>{{ $DatPhong->user->ten_nguoi_dung }}</td>
            <td>{{ $DatPhong->user->email }}</td>
            <td>{{ $DatPhong->user->so_dien_thoai }}</td>
            <td>{{ $DatPhong->thoi_gian_den }}</td>
            <td>{{ $DatPhong->thoi_gian_di }}</td>
            <td>
                <?php
                    $tong_tien = $DatPhong->tong_tien;
                    $tong_tien_format = number_format($tong_tien, 0, ',', '.');
                    echo $tong_tien_format . " VNĐ";
                ?>
            </td>
            <td>
                <ul>
                    <li>
                        <a href="{{ route('chi_tiet_lsphong', $DatPhong->id) }}" class="no-underline font-medium text-blue-600 dark:text-blue-500 hover:underline">Xem chi tiết đơn đặt</a>
                    </li>
                </ul>
            </td>
        </tr>
        @endif
        @endforeach

        </tbody>


    </table>
</div>
        </div>
        <!-- Second Column -->




    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.bootstrap5.js"></script>

<script>
    new DataTable('#example');
</script>
@endsection

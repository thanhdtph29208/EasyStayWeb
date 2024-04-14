@extends('admin.layouts.master')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        @include('admin.layouts.components.content-header', [
        'name' => 'Chi tiết đặt phòng',
        'key' => 'EasyStay',
        ])
    </div>

    <div class="container" id="invoice-print">
        <div class="card">
            <div class="card-header">
                <h5>Chi tiết đặt phòng: {{$datPhong->id}}</h5>
            </div>

            <div class="card-body row">
                <div class="col-6">
                    <h5 class="fw-bold">Thông tin khách hàng</h5>
                    <p>KHÁCH HÀNG:<span class="fw-bold"> {{$datPhong->user->ten_nguoi_dung}}</span></p>
                    <p>EMAIL: <span class="fw-bold">{{$datPhong->user->email}}</span></p>
                    <p>SỐ ĐIỆN THOẠI: <span class="fw-bold">{{$datPhong->user->so_dien_thoai}}</span></p>
                    <p>THỜI GIAN ĐẾN: <span class="fw-bold">{{$datPhong->thoi_gian_den}}</span></p>
                    <p>THỜI GIAN ĐI: <span class="fw-bold">{{$datPhong->thoi_gian_di}}</span></p>
                    <p>TRẠNG THÁI: <span class="{{ $datPhong->trang_thai ? 'badge text-bg-success' : 'badge text-bg-danger' }} fw-bold">{{ $datPhong->trang_thai ? 'Xác nhận' : 'Chưa xác nhận' }}</span></p>
                </div>
                <div class="col-6">
                    <h5 class="fw-bold">Thông tin phòng:</h5>
                    <p>LOẠI PHÒNG:</p>
                    <table class="table">
                        <tr>
                            @foreach ($tenLoaiPhongs as $tenLoaiPhong)
                            <th>
                                {{$tenLoaiPhong}}
                            </th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach ($so_luong_phong as $soLuongPhong)
                            <td>
                                {{$soLuongPhong}}
                            </td>
                            @endforeach
                        </tr>
                        </table>
                    <p>PHÒNG:</p>
                    <table class="table">
                        <tr>
                            @foreach($tenPhongs as $tenPhong)
                            <th>
                                {{$tenPhong}}
                            </th>
                            @endforeach
                        </tr>
                    </table>
                    <p>DỊCH VỤ:</p>
                        <table class="table">
                            <tr>
                                @foreach ($tenDichVus as $tenDichVu)
                                <th>
                                    {{$tenDichVu}}
                                </th>
                                @endforeach
                            </tr>
                            <tr>
                                @foreach ($so_luong_dich_vu as $soLuongDichVu)
                                <td>
                                    {{$soLuongDichVu}}
                                </td>
                                @endforeach
                            </tr>
                        </table>
                    <p>KHUYẾN MÃI: <span class="fw-bold">{{$datPhong->khuyen_mai->ten_khuyen_mai}}</span></p>
                    <p>HÌNH THỨC THANH TOÁN: <span class="fw-bold">{{$datPhong->payment}}</span></p>
                    <p class="text-danger fw-bold">TỔNG TIỀN: <span>{{$datPhong->tong_tien}}</span> </p>
                </div>

            </div>


        </div>
        <a href="{{route('admin.dat_phong.index')}}" class="btn btn-danger mt-3">Quay lại</a>
        <button type="button" class="btn btn-primary mt-3" id="print">
            <i class="bi bi-printer"></i>
        </button>
    </div>

</main>
@endsection
@push('scripts')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
@push('scripts')
<script>
    $(document).ready(function() {
        $('#print').on('click', function() {
           let printBody = $('#invoice-print');
           let originalContents = $('body').html();

           $('body').html(printBody);

           window.print();

           $('body').html(originalContents);
        })
    })
</script>
@endpush

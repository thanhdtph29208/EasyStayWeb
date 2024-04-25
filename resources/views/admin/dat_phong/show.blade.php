@extends('admin.layouts.master')

@section('content')
    <main class="app-main">
        <div class="app-content-header">
            @include('admin.layouts.components.content-header', [
                'name' => 'Chi tiết đặt phòng',
                'key' => 'EasyStay',
            ])
        </div>

        <div class="container" id="invoice-print" style="width:50%">
            <div class="card">
                <div class="card-header" style="text-align:center">
                    <h5>{{ $thongTinHotel[0]->ten }}</h5>
                    <h6>Địa chỉ: {{ $thongTinHotel[0]->dia_chi }}</h6>
                    <h6>Điện thoại: {{ $thongTinHotel[0]->so_dien_thoai }}</h6>
                    <h6>Email: {{ $thongTinHotel[0]->email }}</h6>
                    <h5>Hóa đơn: {{ $datPhong->id }}</h5>
                </div>

                <div class="card-body">
                    @if ($datPhong->ho_ten == null && $datPhong->so_dien_thoai == null && $datPhong->email == null)
                        <div class="">
                            <h5 class="fw-bold">Thông tin khách hàng</h5>
                            <p>KHÁCH HÀNG:<span class="fw-bold"> {{ $datPhong->user->ten_nguoi_dung }}</span></p>
                            <p>EMAIL: <span class="fw-bold">{{ $datPhong->user->email }}</span></p>
                            <p>SỐ ĐIỆN THOẠI: <span class="fw-bold">{{ $datPhong->user->so_dien_thoai }}</span></p>
                            <p>THỜI GIAN ĐẾN: <span
                                    class="fw-bold">{{ date('d-m-Y', strtotime($datPhong->thoi_gian_den)) }}</span></p>
                            <p>THỜI GIAN ĐI: <span
                                    class="fw-bold">{{ date('d-m-Y', strtotime($datPhong->thoi_gian_di)) }}</span></p>
                            <p>TRẠNG THÁI: <span
                                    class="{{ $datPhong->trang_thai ? 'badge text-bg-success' : 'badge text-bg-danger' }} fw-bold">{{ $datPhong->trang_thai ? 'Xác nhận' : 'Chưa xác nhận' }}</span>
                            </p>
                        </div>
                    @else
                        <div class="">
                            <h5 class="fw-bold">Thông tin khách hàng</h5>
                            <p>KHÁCH HÀNG:<span class="fw-bold"> {{ $datPhong->ho_ten }}</span></p>
                            <p>EMAIL: <span class="fw-bold">{{ $datPhong->email }}</span></p>
                            <p>SỐ ĐIỆN THOẠI: <span class="fw-bold">{{ $datPhong->so_dien_thoai }}</span></p>
                            <p>THỜI GIAN ĐẾN: <span
                                    class="fw-bold">{{ date('d-m-Y', strtotime($datPhong->thoi_gian_den)) }}</span></p>
                            <p>THỜI GIAN ĐI: <span
                                    class="fw-bold">{{ date('d-m-Y', strtotime($datPhong->thoi_gian_di)) }}</span></p>
                            <p>TRẠNG THÁI: <span
                                    class="{{ $datPhong->trang_thai ? 'badge text-bg-success' : 'badge text-bg-danger' }} fw-bold">{{ $datPhong->trang_thai ? 'Xác nhận' : 'Chưa xác nhận' }}</span>
                            </p>
                        </div>
                    @endif
                    <div class="">
                        <h5 class="fw-bold">Thông tin phòng:</h5>
                        <table class="table">
                            <tr>
                                <th>Dịch vụ</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                            @foreach ($loaiPhong as $array)
                                <tr>
                                    <td>{{ $array[0] }}</td>
                                    <td>{{ number_format($array[1], 0, ',', '.') }}</td>
                                    <td>{{ $array[2] }}</td>
                                    <td>{{ number_format($array[1] * $array[2], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td>
                                    Tên Phòng:
                                    @foreach ($tenPhongs as $tenPhong)
                                        {{ $tenPhong }} |
                                    @endforeach
                                </td>
                            </tr>
                            @foreach ($dichVu as $item)
                                <tr>
                                    <td>{{ $item[0] }}</td>
                                    <td>{{ number_format($item[1], 0, ',', '.') }}</td>
                                    <td>{{ $item[2] }}</td>
                                    <td>{{ number_format($item[1] * $item[2], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach

                        </table>
                        <div style="text-align:right">
                            @if ($datPhong->khuyen_mai_id)
                                <div>
                                    <p>
                                        Khuyến mãi:
                                        <span class="fw-bold">
                                            {{ $datPhong->khuyen_mai->ten_khuyen_mai }}
                                        </span>
                                    </p>
                                    <p>
                                        Giá trị giảm:
                                        <span class="fw-bold">
                                            {{ $datPhong->khuyen_mai->gia_tri_giam }}
                                            @if ($datPhong->khuyen_mai->loai_giam_gia = 1)
                                                %
                                            @endif
                                        </span>
                                    </p>
                                </div>
                            @endif
                            <p>
                                Tổng tiền phòng:
                                <span class="fw-bold">{{ number_format($datPhong->tong_tien, 0, ',', '.') }} VNĐ</span>
                            </p>
                            <p>Hình thức thanh toán: <span class="fw-bold">{{ $datPhong->payment }}</span></p>
                            <p class="text-danger fw-bold">TỔNG THANH TOÁN:
                                <span>{{ number_format($thanhTien, 0, ',', '.') }} VNĐ</span>
                            </p>
                        </div>
                        <div style="text-align: center;">
                            <span>Cảm ơn quý khách đã sử dụng dịch vụ</span>
                            <br>
                            <span>Hẹn gặp lại quý khách</span>
                            <br>
                            <span>Powered by Pham Hoang Long</span>
                        </div>
                    </div>

                </div>


            </div>
        </div>
        <a href="{{ route('admin.dat_phong.index') }}" class="btn btn-danger mt-3">Quay lại</a>
        <button type="button" class="btn btn-primary mt-3" id="print">
            <i class="bi bi-printer"></i>
        </button>

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

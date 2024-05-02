<!DOCTYPE html>
<html>
<head>
    <title>Xác nhận đặt phòng khách sạn</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">

    <div style="background-color: #fff; border-radius: 5px; padding: 20px;">
        <h2 style="color: #333;">Xin chào {{$ttNguoiDung->ten_nguoi_dung}},</h2>
        <p style="color: #333;">Cảm ơn bạn đã sử dụng dịch vụ của khách sạn chúng tôi - {{$ttKhachSan[0]->ten}}.</p>

        <p style="color: #333;">Chúng tôi rất hân hạnh xác nhận thông tin đơn hàng của bạn:</p>

        <ul style="list-style: none; padding-left: 0;">
            <li><strong>Họ và tên:</strong> {{ $datPhong->ho_ten}}</li>
            <li><strong>Email:</strong> {{ $datPhong->email }}</li>
            <li><strong>Số điện thoại:</strong> {{ $datPhong->so_dien_thoai}}</li>
            <p>Trạng thái: <span class="{{ $datPhong->trang_thai ? 'badge text-bg-success' : 'badge text-bg-danger' }} fw-bold">{{ $datPhong->trang_thai ? 'Xác nhận' : 'Chưa xác nhận' }}</span></p>
            <p>Hình thức thanh toán: <span class="fw-bold">{{$datPhong->payment}}</span></p>
        </ul>

        <p style="color: #333;">Thông tin chi tiết đơn hàng:</p>
        <div class="flex">
            <div>Thời gian đến: {{ $datPhong->thoi_gian_den }}</div>
            <div>Thời gian đi: {{ $datPhong->thoi_gian_di }}</div>
        </div>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border: 1px solid #ccc; padding: 8px;">Số lượng phòng</th>
                    <th style="border: 1px solid #ccc; padding: 8px;">Loại phòng</th>
                    <th style="border: 1px solid #ccc; padding: 8px;">Tổng Tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loaiPhong as $item)
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 8px; text-align: center;">{{ $item[1] }}</td>
                        <td style="border: 1px solid #ccc; padding: 8px; text-align: center;">{{ $item[0] }}</td>
                        <td style="border: 1px solid #ccc; padding: 8px; text-align: center;">
                            <?php
                                $tong_tien = $datPhong->tong_tien;
                                $tong_tien_format = number_format($tong_tien, 0, ',', '.');
                                echo $tong_tien_format . " VNĐ";
                            ?>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p style="color: #333;">Chúng tôi sẽ gọi để xác nhận đơn đặt!</p>

        <br>
        <p style="color: #333;">Trân trọng,</p>
    </div>

</body>
</html>

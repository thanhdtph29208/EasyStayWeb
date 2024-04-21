<h2>Xin chào {{$ttNguoiDung->ten_nguoi_dung}}</h2>
<p>Cảm ơn ông/bà đã sử dụng dịch vụ của khách sạn chúng tôi – {{$ttKhachSan[0]->ten}}.</p>
<p>Chúng tôi rất hân hạnh xác nhận rằng chúng tôi đã đặt
    @foreach($loaiPhong as $item)
        {{$item[1]}}
         phòng loại
        {{$item[0]}}
    @endforeach
    cho ông/bà từ {{$datPhong->thoi_gian_den}} đến {{$datPhong->thoi_gian_di}}.</p>
<p>Chúng tôi mong đợi chuyến thăm của ông/bà.</p>
<br>
<p>Trân trọng.</p>

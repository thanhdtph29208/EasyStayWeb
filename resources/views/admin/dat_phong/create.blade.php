@extends('admin.layouts.master')

@section('content')
<main class="app-main">
    <div class="app-content-header">
        @include('admin.layouts.components.content-header', [
        'name' => 'Đặt phòng',
        'key' => 'EasyStay',
        ])
    </div>

    <div class="mx-3">
        @if (\Session::has('msg'))
        <div class="alert alert-success">
            {{ \Session::get('msg') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="">
                <div class="card">
                    <div class="card-header">
                        <h5>Đặt phòng mới</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.dat_phong.store') }}" method="post" id="addServiceForm">
                            @csrf

                            <div class="form-group mx-auto ">
                                <label for="user_id_input">Email Khách Hàng</label>
                                <input type="text" id="user_id_input" class="form-control" list="user">
                                <input id="user_id" name="user_id" hidden>
                                <datalist id="user">
                                    @foreach ($user as $id => $email)
                                    <option value="{{$email}}" data-id="{{$id}}" id="option_user_{{$email}}">{{$email}}</option>
                                    @endforeach
                                </datalist>
                                <span class="text-danger error-user_id"></span>
                            </div>

                            <div class="form-group mt-3 mx-auto">
                                <label for="email">Email</label>
                                <input type="text" class="form-control" name="email" id="email">
                                <span class="text-danger error-email"></span>
                            </div>

                            <div class="form-group mt-3 mx-auto" style="display: inline-block; width:629px">
                                <label for="ho_ten">Họ tên</label>
                                <input type="text" class="form-control" name="ho_ten" id="ho_ten">
                                <span class="text-danger error-ho_ten"></span>
                            </div>

                            <div class="form-group mt-3 mx-auto" style="display: inline-block; width:629px">
                                <label for="so_dien_thoai">Số điện thoại</label>
                                <input type="text" class="form-control" name="so_dien_thoai" id="so_dien_thoai">
                                <span class="text-danger error-so_dien_thoai"></span>
                            </div>

                            <div id="dynamic-form">
                                <div class="form-group mt-3 mx-auto" style="display: inline-block; width:629px">
                                    <label for="loai_phong_ids_{{$i}}">Loại Phòng</label>
                                    <select name="loai_phong_ids[{{$i}}][id]" id="loai_phong_ids_{{$i}}" class="form-control">
                                        @foreach ($loai_phong as $id => $ten)
                                        <option value="{{$id}}">{{$ten}}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger error-loai_phong_id_{{$i}}"></span>
                                </div>

                                <div class="form-group mt-3 mx-auto" style="display: inline-block; width:629px">
                                    <label for="so_luong_phong_{{$i}}">Số Lượng phòng</label>
                                    <input type="number" class="form-control" name="so_luong_phong[{{$i}}][so_luong_phong]" id="so_luong_phong_{{$i}}" value="0" min="0">
                                    <span class="text-danger error-so_luong_phong"></span>
                                </div>
                            </div>

                            <button type="button" id="add-button" class="btn btn-primary">Thêm</button>
                            <div class="form-group mt-3 mx-auto ">
                                <label for="so_luong_nguoi">Số Lượng người</label>
                                <input type="number" class="form-control" id="so_luong_nguoi" name="so_luong_nguoi">
                                <span class="text-danger error-so_luong_nguoi"></span>
                            </div>
                            <div>
                                <div class="form-group mt-3 mx-auto " style="display: inline-block; width:629px">
                                    <label for="thoi_gian_den">Thời gian đến</label>
                                    <input type="date" class="form-control" id="thoi_gian_den" name="thoi_gian_den">
                                    <span class="text-danger error-thoi_gian_den"></span>
                                </div>
                                <div class="form-group mt-3 mx-auto " style="display: inline-block; width:629px">
                                    <label for="thoi_gian_di">Thời gian đi</label>
                                    <input type="date" class="form-control" id="thoi_gian_di" name="thoi_gian_di" min="" onchange="setMinDate()">
                                    <span class="text-danger error-thoi_gian_di"></span>
                                </div>
                            </div>
                            <div class="form-group mt-3 mx-auto ">
                                <label for="khuyen_mai_input">Khuyến mãi</label>
                                <input list="khuyen_mai" id="khuyen_mai_input" class="form-control">
                                <input id="khuyen_mai_id" name="khuyen_mai_id" hidden>
                                <datalist id="khuyen_mai">
                                    @foreach ($khuyen_mai as $id => $ten_khuyen_mai)
                                    <option value="{{$ten_khuyen_mai}}" data-id="{{$id}}" id="option_km_{{$ten_khuyen_mai}}">{{$ten_khuyen_mai}}</option>
                                    @endforeach
                                </datalist>
                                <span class="text-danger error-trn_khuyen_mai_id"></span>
                            </div>
                            <div class="form-group mt-3 mx-auto ">
                                <label for="payment">Payment</label>
                                <select name="payment" id="payment" class="form-control">
                                    <option value="Offline">Offline</option>
                                </select>
                                <span class="text-danger error-payment"></span>
                            </div>
                            <div class="form-group mt-3 mx-auto ">
                                <label for="ghi_chu">Ghi chú</label>
                                <textarea type="number" class="form-control" id="ghi_chu" name="ghi_chu"></textarea>
                                <span class="text-danger error-ghi_chu"></span>
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" class="btn btn-success mt-3">Gửi</button>
                                <a href="{{ route('admin.dat_phong.index') }}" class="btn btn-danger mt-3 ms-3">Quay
                                    lại</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    var i = 1;

    document.getElementById('add-button').addEventListener('click', function() {
        var form = document.getElementById('dynamic-form');
        var html = `
             <div class="form-group mt-3 mx-auto" style="display: inline-block; width:629px">
                 <label for="loai_phong_ids_${i}">Loại Phòng</label>
                 <select name="loai_phong_ids[${i}][id]" id="loai_phong_ids_${i}" class="form-control" list="loai_phong">
                     @foreach ($loai_phong as $id => $ten)
                         <option value="{{$id}}">{{$ten}}</option>
                     @endforeach
                 </select>
                 <span class="text-danger error-loai_phong_ids_${i}"></span>
             </div>

             <div class="form-group mt-3 mx-auto" style="display: inline-block; width:629px">
                 <label for="so_luong_phong_${i}">Số Lượng phòng</label>
                 <input type="number" class="form-control" name="so_luong_phong[${i}][so_luong_phong]" id="so_luong_phong_${i}" value="0" min="0">
                 <span class="text-danger error-so_luong_phong"></span>
             </div>
         `;

        form.insertAdjacentHTML('beforeend', html);
        i++;
    });
    document.getElementById('khuyen_mai_input').addEventListener('input', function() {
        var selectedOption = document.getElementById('option_km_' + this.value);
        if (selectedOption) {
            var id_km = selectedOption.getAttribute('data-id');
            document.getElementById('khuyen_mai_id').value = id_km;
        }
    });
    document.getElementById('user_id_input').addEventListener('input', function() {
        var selectedOption = document.getElementById('option_user_' + this.value);
        // console.log(this.value);
        // console.log(selectedOption);
        if (selectedOption) {
            var id_user = selectedOption.getAttribute('data-id');
            // console.log(id_user);
            document.getElementById('user_id').value = id_user;
        }
    });
    function setMinDate() {
        // Lấy giá trị của input "Thời gian đến"
        var thoi_gian_den_value = document.getElementById("thoi_gian_den").value;

        // Chuyển đổi giá trị thành đối tượng Date
        var minDate = new Date(thoi_gian_den_value);

        // Thêm một ngày cho giá trị tối thiểu cho input "Thời gian đi"
        minDate.setDate(minDate.getDate() + 1);

        // Định dạng ngày tháng để gán cho thuộc tính "min" của input "Thời gian đi"
        var formattedMinDate = minDate.toISOString().slice(0, 10);

        // Thiết lập giá trị tối thiểu cho input "Thời gian đi"
        document.getElementById("thoi_gian_di").setAttribute("min", formattedMinDate);
    }
</script>
@endsection

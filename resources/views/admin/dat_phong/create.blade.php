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
                                <label for="user_id">Email Khách Hàng</label>
                                <input type="text" name="user_id" id="user_id" class="form-control" list="user">
                                <datalist id="user">
                                    @foreach ($user as $id => $email)
                                        <option value="{{$id}}">{{$email}}</option>
                                    @endforeach
                                </datalist>
                                <span class="text-danger error-user_id"></span>
                            </div>
                            <div id="inputs-container">
                                <div class="form-group mt-3 mx-auto" style="display: inline-block; width:629px">
                                    <label for="loai_phong_id">Loại Phòng</label>
                                    <input type="text" name="loai_phong_ids[id]" id="loai_phong_id" class="form-control" list="loai_phong">
                                    <datalist id="loai_phong">
                                        @foreach ($loai_phong as $id => $ten)
                                            <option value="{{$id}}">{{$ten}}</option>
                                        @endforeach
                                    </datalist>
                                    <span class="text-danger error-loai_phong_id"></span>
                                </div>

                                <div class="form-group mt-3 mx-auto" style="display: inline-block; width:629px">
                                    <label for="so_luong_phong">Số Lượng phòng</label>
                                    <input type="number" class="form-control" name="so_luong_phong[so_luong_phong]" id="so_luong_phong">
                                    <span class="text-danger error-so_luong_phong"></span>
                                </div>
                            </div>
                            <button id="add-input" class="btn btn-primary">Thêm</button>
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
                                    <input type="date" class="form-control" id="thoi_gian_di" name="thoi_gian_di">
                                    <span class="text-danger error-thoi_gian_di"></span>
                                </div>
                            </div>
                            <div class="form-group mt-3 mx-auto ">
                                <label for="khuyen_mai_id">Khuyến mãi</label>
                                <input type="text" name="khuyen_mai_id" id="khuyen_mai_id" class="form-control" list="khuyen_mai">
                                <datalist id="khuyen_mai">
                                    @foreach ($khuyen_mai as $id => $ten_khuyen_mai)
                                        <option value="{{$id}}">{{$ten_khuyen_mai}}</option>
                                    @endforeach
                                </datalist>
                                <span class="text-danger error-trn_khuyen_mai_id"></span>
                            </div>
                            <div class="form-group mt-3 mx-auto ">
                                <label for="payment">Payment</label>
                                <select name="payment" id="payment" class="form-control">
                                    <option value="Online">Online</option>
                                    <option value="Offline">Offline</option>
                                </select>
                                <span class="text-danger error-payment"></span>
                            </div>
                            <div class="form-group mt-3 mx-auto ">
                                <label for="ghi_chu">Ghi chú</label>
                                <input type="number" class="form-control" id="ghi_chu" name="ghi_chu">
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
    document.getElementById('add-input').addEventListener('click', function(event) {
        // Ngăn chặn hành động mặc định của nút
        event.preventDefault();

        // Clone container của inputs và thêm vào
        var inputsContainer = document.getElementById('inputs-container');
        var newInputsContainer = inputsContainer.cloneNode(true);

        // Reset giá trị của inputs trong container mới
        var inputs = newInputsContainer.querySelectorAll('input');
        inputs.forEach(function(input) {
            input.value = '';
        });

        // Thêm container mới vào cuối
        var addButton = document.getElementById('add-input');
        addButton.insertAdjacentElement('beforebegin', newInputsContainer);
    });
</script>

@endsection

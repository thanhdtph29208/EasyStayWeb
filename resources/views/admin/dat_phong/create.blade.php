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

                            <!-- <div class="form-group mt-3 mx-auto" style="display: inline-block; width:629px">
                                <label for="loai_phong_id">Loại Phòng</label>
                                <select name="loai_phong_id" id="loai_phong_id" class="form-control">
                                    @foreach ($loai_phong as $id => $ten)
                                        <option value="{{$id}}">{{$ten}}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-loai_phong_id"></span>
                            </div> -->

                            <!-- <div class="form-group mt-3 mx-auto" style="display: inline-block; width:629px">
                                <label for="so_luong_phong">Số Lượng phòng</label>
                                <input type="number" class="form-control" name="so_luong_phong" id="so_luong_phong" value="0" min="0">
                                <span class="text-danger error-so_luong_phong"></span>
                            </div> -->

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
                                    <input type="date" class="form-control" id="thoi_gian_di" name="thoi_gian_di">
                                    <span class="text-danger error-thoi_gian_di"></span>
                                </div>
                            </div>
                            <div class="form-group mt-3 mx-auto ">
                                <label for="khuyen_mai_input">Khuyến mãi</label>
                                <!-- <input type="text" name="khuyen_mai_id" id="khuyen_mai_id" class="form-control" list="khuyen_mai"> -->
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
                                    <option value="Online">Online</option>
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
        // console.log(this.value);
        // console.log(selectedOption);
        if (selectedOption) {
            var id = selectedOption.getAttribute('data-id');
            // console.log(id);
            document.getElementById('khuyen_mai_id').value = id;
        }
    });
</script>
@endsection

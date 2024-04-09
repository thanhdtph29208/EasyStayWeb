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
        <div class="card">
            <div class="card-header">
                <h5>Cập nhật đặt phòng</h5>
            </div>

            <div class="card-body">
                <form action="{{route('admin.dat_phong.update',$datPhong)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div id="dich-vu-form">
                        <div class="form-group mt-3 mx-auto"  style="display: inline-block; width:629px">
                            <label for="dich_vu_ids_{{ $j }}"></label>
                            <select  name="dich_vu_ids[{{ $j }}][id]" id="dich_vu_ids_{{ $j }}" class="form-control">>
                                @foreach ($dich_vus as $dich_vu)
                                    <option value="{{ $dich_vu->id }}">{{ $dich_vu->ten_dich_vu }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-dich_vu_id_{{$j}}"></span>
                        </div>

                        <div class="form-group mt-3 mx-auto" style="display: inline-block; width:629px">
                            <label for="so_luong_{{ $j }}">Số lượng:</label>
                            <input type="number" class="form-control" name="so_luong[{{ $j }}][so_luong]" id="so_luong_{{ $j }}" value="0" min="0">
                        </div>
                    </div>
                    <button type="button" id="add-input" class="btn btn-primary">Thêm</button>
                    <div class="form-group mt-3 mx-auto ">
                        <label for="ghi_chu">Ghi chú</label>
                        <textarea type="text" class="form-control" id="ghi_chu" name="ghi_chu"></textarea>
                        <span class="text-danger error-ghi_chu"></span>
                    </div>
                    <div class="d-flex justify-content-center">

                        <button type="submit" class="btn btn-success mt-3">Gửi</button>
                        <a href="{{ route('admin.dat_phong.index') }}" class="btn btn-danger mt-3 ms-3">Quay
                            lại</a>
                </form>
            </div>
        </div>
    </div>
</main>
<script>
    var j = 1;

document.getElementById('add-input').addEventListener('click', function() {
    var form = document.getElementById('dich-vu-form');
    var html = `
            <div id="dich-vu-form">
                <div class="form-group mt-3 mx-auto"  style="display: inline-block; width:629px">
                    <label for="dich_vu_ids_${j}">Dịch vụ </label>
                    <select  name="dich_vu_ids[${j}][id]" id="dich_vu_ids_${j}" class="form-control">>
                        @foreach ($dich_vus as $dich_vu)
                            <option value="{{ $dich_vu->id }}">{{ $dich_vu->ten_dich_vu }}</option>
                        @endforeach
                    </select>
                    <span class="text-danger error-dich_vu_id_${j}"></span>
                </div>

                <div class="form-group mt-3 mx-auto" style="display: inline-block; width:629px">
                    <label for="so_luong_${j}">Số lượng</label>
                    <input type="number" class="form-control" name="so_luong[${j}][so_luong]" id="so_luong_${j}" value="0" min="0">
                 <span class="text-danger error-so_luong_${j}"></span>
                </div>
            </div>
     `;

    form.insertAdjacentHTML('beforeend', html);
    j++;
});
</script>
@endsection

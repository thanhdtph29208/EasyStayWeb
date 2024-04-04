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

                    <div class="form-group mt-3 mx-auto">
                        @for ($i = 0; $i < $so_luong_dich_vu; $i++)
                        <div id="form-container">
                            <label for="dich_vu_ids_{{ $i }}">Dịch vụ {{ $i + 1 }}:</label>
                            <input type="text" name="dich_vu_ids[{{ $i }}][id]" id="dich_vu_ids_{{ $i }}" list="dich_vu">
                            <datalist id="dich_vu">
                                @foreach ($dich_vus as $dich_vu)
                                    <option value="{{ $dich_vu->id }}">{{ $dich_vu->ten_dich_vu }}</option>
                                @endforeach
                            </datalist>
                            <label for="so_luong_{{ $i }}">Số lượng:</label>
                            <input type="number" name="dich_vu_ids[{{ $i }}][so_luong]" id="so_luong_{{ $i }}" value="0" min="0">
                        </div>
                        @break;
                        @endfor
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
    var count = 0; // Số lần lặp đã được thực hiện

    // console.log(count);
    document.getElementById("add-input").addEventListener("click", function() {
        count++; // Tăng biến đếm lên 1
        console.log(count);
        var formGroup = `
        <label for="dich_vu_ids_{{ $count }}">Dịch vụ {{ $count + 1 }}:</label>
                            <input type="text" name="dich_vu_ids[{{ $count }}][id]" id="dich_vu_ids_{{ $count }}" list="dich_vu">
                            <datalist id="dich_vu">
                                @foreach ($dich_vus as $dich_vu)
                                    <option value="{{ $dich_vu->id }}">{{ $dich_vu->ten_dich_vu }}</option>
                                @endforeach
                            </datalist>
                            <label for="so_luong_{{ $count }}">Số lượng:</label>
                            <input type="number" name="dich_vu_ids[{{ $count }}][so_luong]" id="so_luong_{{ $count }}" value="0" min="0">
        `;
        document.getElementById("form-container").innerHTML += formGroup;
    });
</script>
@endsection

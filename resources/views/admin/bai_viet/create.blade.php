@extends('admin.layouts.master')

@section('content')
<style>
    .ck-editor__editable {min-height: 400px;}
</style>
    <main class="app-main">
        <div class="app-content-header">
            @include('admin.layouts.components.content-header', [
                'name' => 'Bài viết',
                'key' => 'EasyStay',
            ])
        </div>

        <!-- <div class="mx-3">
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
        </div> -->
        <div class="container">
            <div class="row justify-content-center">
                <div class="">
                    <div class="card">
                        <div class="card-header">
                            <h5>Tạo mới bài viết</h5>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('admin.bai_viet.store') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="mb-3 mx-auto ">
                                    <label for="tieu_de" class="form-label">Tiêu đề:</label>
                                    <input type="text" name="tieu_de" id="tieu_de" class="form-control">
                                </div>

                                <div class="mb-3 mx-auto ">
                                    <label for="anh" class="form-label">Ảnh:</label>
                                    <input type="file" name="anh" id="anh" class="form-control">
                                </div>

                                <div class="mb-3 mx-auto ">
                                    <label for="mo_ta_ngan" class="form-label">Mô tả ngắn:</label>
                                    <textarea name="mo_ta_ngan" id="mo_ta_ngan" cols="20" rows="5" class="form-control"></textarea>
                                </div>

                                <div class="mb-3 mx-auto ">
                                    <label for="noi_dung" class="form-label">Nội dung:</label>
                                    <textarea name="noi_dung_create" id="NoidungCreate" cols="30" rows="8" class="form-control"></textarea>
                                </div>

                                <div class="mb-3 mx-auto ">
                                    <label class="form-label">Trạng thái:</label> <br>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="trang_thai" id="trang_thai1"
                                            value="{{ \App\Models\Bai_viet::XUAT_BAN }}">
                                        <label class="form-check-label" for="trang_thai1">Xuất bản</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="trang_thai" id="trang_thai2"
                                            value="{{ \App\Models\Bai_viet::NHAP }}">
                                        <label class="form-check-label" for="trang_thai2">Nháp</label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-center">
                                    <button type="submit" class="btn btn-success">Thêm mới</button>
                                    <a class="btn btn-danger ms-2" href="{{ route('admin.bai_viet.index') }}">Quay lại</a>
                                </div>


                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
<script>
    CKEDITOR.replace('noi_dung_create', {
        filebrowserUploadUrl: "{{ route('admin.bai_viet.upload', ['_token' => csrf_token()]) }}",
        filebrowserUploadMethod: 'form'
    });

</script>
@endpush

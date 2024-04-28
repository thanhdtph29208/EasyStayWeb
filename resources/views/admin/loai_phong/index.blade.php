@extends('admin.layouts.master')

@section('content')
@if(session('error'))
<script>
    alert("{{ session('error') }}");
</script>
@endif
<!-- <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h4 class="">All Product</h4>
                        <a href="{{ route('admin.loai_phong.create') }}" class="btn btn-success">
                            <i class="bi bi-plus"></i>
                            Tạo mới</a>
                    </div>

                    <div class="card-body">
                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>
    </div> -->

<main class="app-main">
    <div class="app-content-header">
        @include('admin.layouts.components.content-header', [
        'name' => 'Loại phòng',
        'key' => 'EasyStay',
        ])
    </div>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5>Danh sách</h5>
            </div>
            <div class="card-body">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Lọc<i class="bi bi-filter ms-2"></i>
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Bộ lọc</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="" id="searchLoaiPhongForm">
                                    <div class="modal-body px-2">
                                        <label for="startTime" class="form-label mb-2">Thời gian bắt đầu - Thời gian kết
                                            thúc</label>
                                        <div class="d-flex align-items-center mb-4">
                                            <input type="date" name="startTime" id="startTime" class="form-control">
                                            <i class="bi bi-arrow-left-right mx-2"></i>
                                            <input type="date" name="endTime" class="form-control">
                                        </div>

                                        <select class="form-select" name="status" aria-label="Default select example">
                                            <option selected value="2">Chọn Trạng thái</option>
                                            <option value="1">Còn phòng</option>
                                            <option value="0">Hết phòng</option>
                                        </select>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Thoát</button>
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                                            id="btnSubmitSearchLoaiPhong">Tìm kiếm</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
</main>
@endsection
@push('scripts')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}

    <script type="module">

        $(document).ready(function() {
            $('#btnSubmitSearchLoaiPhong').click(function(e) {
                // Lấy dữ liệu từ form tìm kiếm
                // Gửi yêu cầu tìm kiếm bằng AJAX\
                var formData = $('#searchLoaiPhongForm').serializeArray();
                // console.log(1);
                $("#loaiphong-table").DataTable().destroy();
                $(function() {
                    window.LaravelDataTables = window.LaravelDataTables || {};
                    window.LaravelDataTables["loaiphong-table"] = $("#loaiphong-table").DataTable({
                        serverSide: true,
                        processing: true,
                        ajax: {
                            url: "{{ route('admin.search_loai_phong') }}",
                            type: "GET",
                            //  data: formData,
                            data: function(data) {
                                toastr.success('Tìm kiếm thành công', 'Thành công');
                                console.log(data);

                                $.each(formData, function(index, item) {
                                    data[item.name] = item.value;
                                });
                                for (var i = 0, len = data.columns.length; i <
                                    len; i++) {
                                    if (!data.columns[i].search.value) delete data
                                        .columns[i].search;
                                    if (data.columns[i].searchable === true)
                                        delete data.columns[i].searchable;
                                    if (data.columns[i].orderable === true)
                                        delete data.columns[i].orderable;
                                    if (data.columns[i].data === data.columns[i].name)
                                        delete data.columns[i].name;
                                }
                                delete data.search.regex;
                            },
                        },
                        success: function(response) {
                            console.log("thanh công");
                        },
                        columns: [
                            {
                                data: "id",
                                name: "id",
                                title: "Id",
                                orderable: true,
                                searchable: true,
                            },
                            {
                                data: "loai_phong.ten",
                                name: "ten",
                                title: "Tên",
                                orderable: true,
                                searchable: true,
                            },
                            {
                                data: "loai_phong.anh",
                                name: "anh",
                                title: "Ảnh",
                                orderable: true,
                                searchable: true,
                            },
                            {
                                data: "loai_phong.gia",
                                name: "gia",
                                title: "Giá",
                                orderable: true,
                                searchable: true,
                            },
                            {
                                data: "loai_phong.so_luong",
                                name: "so_luong",
                                title: "Số lượng",
                                orderable: true,
                                searchable: true,
                            },
                            {
                                data: "loai_phong.phong_trong",
                                name: "phong_trong",
                                title: "Phòng trống",
                                orderable: true,
                                searchable: true,
                            },
                            {
                                data: "loai_phong.trang_thai",
                                name: "trang_thai",
                                title: "Trạng thái",
                                orderable: true,
                                searchable: true,
                            },
                            {
                                data: "action",
                                name: "action",
                                title: "Action",
                                orderable: false,
                                searchable: false,
                                width: 160,
                                className: "text-center",
                            },
                        ],
                        order: [
                            [1, "desc"]
                        ],
                        select: {
                            style: "single"
                        },
                        buttons: [],
                    });
                });
                //    $.ajax({
                //        type: 'POST',
                //        url: '{{ route('admin.search_dat_phong') }}',
                //        data: formData,
                //        success: function(response){


                //        },
                //        error: function(xhr, status, error){
                //            console.error(xhr.responseText);
                //        }
                //    });
            });
        });
</script>
@endpush

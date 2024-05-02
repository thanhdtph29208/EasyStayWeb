@extends('admin.layouts.master')
@if (session('error'))
    <script>
        alert("{{ session('error') }}");
    </script>
@endif
@section('content')
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
                    <h5>Loại phòng</h5>
                </div>
                <div class="card-body">
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Lọc <i class="bi bi-filter ms-2"></i>
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
                                <form action="" id="searchForm">
                                    <div class="modal-body px-2">
                                        <label for="startTime" class="form-label mb-2">Thời gian bắt đầu - Thời gian kết
                                            thúc</label>
                                        <div class="d-flex align-items-center mb-4">
                                            <input type="date" name="thoi_gian_den" id="startTime" class="form-control" min="{{ date('Y-m-d') }}">
                                            <i class="bi bi-arrow-left-right mx-2"></i>
                                            <input type="date" name="thoi_gian_di" class="form-control" min="{{ date('Y-m-d') }}">
                                        </div>

                                        <label for="gia_min" class="form-label mb-2">Giá tối thiểu</label>
                                        <input type="number" name="gia_min" id="gia_min" class="form-control mb-3">

                                        <label for="gia_max" class="form-label mb-2">Giá tối đa</label>
                                        <input type="number" name="gia_max" id="gia_max" class="form-control mb-3">


                                        <select class="form-select mb-3" name="trang_thai"
                                            aria-label="Default select example">
                                            <option selected value="">Tất cả</option>
                                            <option value="0">Hết phòng</option>
                                            <option value="1">Còn phòng</option>
                                        </select>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Thoát</button>
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                                            id="btnSubmitSearch">Tìm kiếm</button>
                                        <button type="button" class="btn btn-secondary" id="btnReset">Reset</button>
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
        $('#btnReset').click(function(e) {
            $('#searchForm')[0].reset();
        });

        $(document).ready(function() {
            $('#btnSubmitSearch').click(function(e) {
                var formData = $('#searchForm').serializeArray();
                $("#loaiphong-table").DataTable().destroy();
                $(function() {
                    window.LaravelDataTables = window.LaravelDataTables || {};
                    window.LaravelDataTables["loaiphong-table"] = $("#loaiphong-table").DataTable({
                        serverSide: true,
                        processing: true,
                        ajax: {
                            url: "{{ route('admin.search_loai_phong') }}",
                            type: "GET",
                            data: function(data) {
                                toastr.success('Tìm kiếm thành công', 'Thành công');
                                $.each(formData, function(index, item) {
                                    data[item.name] = item.value;
                                });
                                for (var i = 0, len = data.columns.length; i <
                                    len; i++) {
                                    if (!data.columns[i].search.value) delete data
                                        .columns[i].search;
                                    if (data.columns[i].searchable === true) delete data
                                        .columns[i].searchable;
                                    if (data.columns[i].orderable === true) delete data
                                        .columns[i].orderable;
                                    if (data.columns[i].data === data.columns[i].name)
                                        delete data.columns[i].name;
                                }
                                delete data.search.regex;
                            },
                        },
                        success: function(response) {
                            console.log("Thành công");
                        },
                        columns: [{
                                data: "id",
                                name: "id",
                                title: "Id",
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: "ten",
                                name: "ten",
                                title: "Tên",
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: "anh",
                                name: "anh",
                                title: "Ảnh",
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: "gia",
                                name: "gia",
                                title: "Giá",
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: "so_luong",
                                name: "so_luong",
                                title: "Số lượng",
                                orderable: true,
                                searchable: true,
                                width: 90,
                            },
                            {
                                data: "phong_trong",
                                name: "phong_trong",
                                title: "Phòng trống",
                                orderable: true,
                                searchable: true,
                                width: 90
                            },
                            {
                                data: "trang_thai",
                                name: "trang_thai",
                                title: "Trạng thái",
                                orderable: true,
                                searchable: true,
                                width: 150
                            },
                            {
                                data: "tinh_trang",
                                name: "tinh_trang",
                                title: "Tình trạng",
                                orderable: true,
                                searchable: true,
                                width: 150
                            },
                            {
                                data: "action",
                                name: "action",
                                title: "Action",
                                orderable: false,
                                searchable: false,
                                width: 280,
                                className: "text-center"
                            }
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
            });
        });
    </script>
@endpush

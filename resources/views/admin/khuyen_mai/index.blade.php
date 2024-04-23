@extends('admin.layouts.master')

@section('content')
    @if (session('error'))
        <script>
            alert("{{ session('error') }}");
        </script>
    @endif
    <main class="app-main">
        <div class="app-content-header">
            @include('admin.layouts.components.content-header', [
                'name' => 'Khuyến mãi',
                'key' => 'EasyStay',
            ])
        </div>

        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h5>Danh sách Khuyến Mãi</h5>
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
                                <form action="" id="searchForm">
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
                                            <option value="0">Bắt đầu</option>
                                            <option value="1">Kết thúc</option>
                                        </select>
                                        <label for="create_date" class="form-label mb-2">Thời gian tạo</label>
                                        <input type="date" name="create_date_time" id="create_date" class="form-control">

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Thoát</button>
                                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                                            id="btnSubmitSearch">Tìm kiếm</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- DataTable -->
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
            $('#btnSubmitSearch').click(function(e) {
                // Lấy dữ liệu từ form tìm kiếm
                var formData = $('#searchForm').serializeArray();
                $("#khuyenmai-table").DataTable().destroy();
                $(function() {
                    window.LaravelDataTables = window.LaravelDataTables || {};
                    window.LaravelDataTables["khuyenmai-table"] = $("#khuyenmai-table").DataTable({
                        serverSide: true,
                        processing: true,
                        ajax: {
                            url: "{{ route('admin.search_khuyen_mai') }}",
                            type: "GET",
                            data: function(data) {
                                toastr.success('Tìm kiếm thành công', 'Thành công');
                                console.log(data);
                                $.each(formData, function(index, item) {
                                    data[item.name] = item.value;
                                });
                                for (var i = 0; i < data.columns.length; i++) {
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
                                title: "ID",
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: "ten_khuyen_mai",
                                name: "ten_khuyen_mai",
                                title: "Tên khuyến mãi",
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: "loai_phong_id",
                                name: "loai_phong_id",
                                title: "Loại phòng",
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: "ma_giam_gia",
                                name: "ma_giam_gia",
                                title: "Mã giảm giá",
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: "loai_giam_gia",
                                name: "loai_giam_gia",
                                title: "Loại giảm giá",
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: "gia_tri_giam",
                                name: "gia_tri_giam",
                                title: "Giá trị giảm",
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: "ngay_bat_dau",
                                name: "ngay_bat_dau",
                                title: "Ngày bắt đầu",
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: "ngay_ket_thuc",
                                name: "ngay_ket_thuc",
                                title: "Ngày kết thúc",
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: "trang_thai",
                                name: "trang_thai",
                                title: "Trạng thái",
                                orderable: true,
                                searchable: true
                            },
                            {
                                data: "action",
                                name: "action",
                                title: "Action",
                                orderable: false,
                                searchable: false,
                                width: 160,
                                className: "text-center"
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
            });
        });
    </script>
@endpush

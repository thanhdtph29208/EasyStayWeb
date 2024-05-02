@extends('admin.layouts.master')
@section('content')

@if(session('error'))
<script>
    alert("{{ session('error') }}");
</script>
@endif
<main class="app-main">
    <div class="app-content-header">
        @include('admin.layouts.components.content-header', [
        'name' => 'Banner',
        'key' => 'EasyStay',
        ])
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5>Banner</h5>
                    </div>

                    <div class="card-body">
                        <form enctype="multipart/form-data" action="{{ route('admin.banners.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Tải ảnh</label>
                                <input type="file" class="form-control" multiple name="anh[]">
                            </div>
                            <button type="submit" class="btn btn-success">Tải lên</button>

                        </form>
                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

@push('scripts')
{{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush

<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('body').on('click', '.change-status', function() {
           
            let isChecked = $(this).is(':checked')
            console.log(isChecked);

            let id = $(this).data('id')
            $.ajax({
                url: "{{ route('admin.banner.change-status') }}",
                method: 'PUT',
                data: {
                    status: isChecked,
                    id: id
                },
                success: function(data) {
                    toastr.success(data.message);
                    // alert("Trạng thái của đối tượng sau khi click: " + data.id); // Hiển thị trạng thái của đối tượng
                }
            })
        })
    })
</script>
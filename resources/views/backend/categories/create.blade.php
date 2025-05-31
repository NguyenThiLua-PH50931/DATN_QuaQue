@extends('layouts.backend')

@section('title', 'Thêm danh mục')

@section('content')
            <div class="page-body">

                <!-- New Product Add Start -->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-sm-8 m-auto">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-header-2">
                                                <h5>Thông tin danh mục</h5>
                                            </div>

                                            <form class="theme-form theme-form-2 mega-form" action="{{ route('admin.categories.store') }}" method="POST">
                                                @csrf
                                                <div class="mb-4 row align-items-center">
                                                    <label class="form-label-title col-sm-3 mb-0">Tên danh mục</label>
                                                    <div class="col-sm-9">
                                                        <input class="form-control" type="text"
                                                            placeholder="Category Name" name="name">
                                                    </div>
                                                </div>
                                                <div class="mb-4 row align-items-center">
                                                    <div class="col-sm-3"></div>
                                                    <div class="col-sm-9">
                                                        <div class="d-flex align-items-center">
                                                            <button type="submit" class="btn btn-success me-2">Lưu</button>
                                                            <a href="{{ route('admin.categories.index') }}" class="btn btn-danger">Hủy</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- New Product Add End -->
@includeIf('backend.footer')
</div>
@endsection

@push('scripts')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script> --}}
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet"> --}}
<script>
    $(document).ready(function() {
        // Initialize Dropzone
        // new Dropzone("#imageDropzone", {
        //     url: '{{ route('admin.categories.store') }}',
        //     paramName: "image",
        //     maxFiles: 1,
        //     acceptedFiles: "image/*",
        //     init: function() {
        //         this.on("addedfile", function(file) { console.log("File added:", file); });
        //         this.on("error", function(file, error) { alert(error); });
        //     }
        // });

        // Handle icon selection
        // $('.dropdown-item').on('click', function(e) {
        //     e.preventDefault();
        //     const icon = $(this).data('icon');
        //     $('#icon').val(icon);
        //     $('#dropdownMenuButton1').text($(this).find('img').attr('alt') || 'Chọn biểu tượng');
        // });

        // Handle form submission
        // $('#createCategoryForm').on('submit', function(e) {
        //     e.preventDefault();

        //     let formData = new FormData(this);
        //     formData.append('slug', slugify($('#name').val()));
        //     $('#saveBtn .spinner-border').removeClass('d-none');
        //     $('#saveBtn').prop('disabled', true);

        //     $.ajax({
        //         url: '{{ route('admin.categories.store') }}',
        //         method: 'POST',
        //         data: formData,
        //         contentType: false,
        //         processData: false,
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //         },
        //         success: function(response) {
        //             toastr.success('Thêm danh mục thành công!');
        //             setTimeout(function() {
        //                 window.location.href = '{{ route('admin.categories.index') }}';
        //             }, 1000);
        //         },
        //         error: function(xhr) {
        //             if (xhr.status === 422) {
        //                 let errors = xhr.responseJSON.errors;
        //                 // Clear previous errors
        //                 $('.text-danger').remove();
        //                 $.each(errors, function(key, value) {
        //                     $('#' + key).after('<span class="text-danger">' + value[0] + '</span>');
        //                 });
        //             } else {
        //                 toastr.error(xhr.responseJSON.message || 'Lỗi khi thêm danh mục');
        //             }
        //         },
        //         complete: function() {
        //             $('#saveBtn .spinner-border').addClass('d-none');
        //             $('#saveBtn').prop('disabled', false);
        //         }
        //     });
        // });

        function slugify(text) {
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')
                .replace(/[^\w-]+/g, '')
                .replace(/--+/g, '-')
                .replace(/^-+/, '')
                .replace(/-+$/, '');
        }
    });
</script>
@endpush

@extends('layouts.backend')

@section('title', 'Chỉnh sửa danh mục')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card" style="margin-left: 250px; padding: 20px;">
                    <div class="card-header">
                        <h3 class="card-title">Chỉnh sửa danh mục</h3>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">Tên danh mục</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $category->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <div class="col-sm-3"></div>
                                <div class="col-sm-9">
                                    <div class="d-flex align-items-center">
                                        <button type="submit" class="btn btn-success me-2">Cập nhật</button>
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
@endsection

@push('scripts')
{{-- Removed AJAX script as requested --}}
{{--
<script>
    $(document).ready(function() {
        $('#editCategoryForm').on('submit', function(e) {
            e.preventDefault();
            $('#updateBtn .spinner-border').removeClass('d-none');
            $('#updateBtn').prop('disabled', true);

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    toastr.success('Cập nhật danh mục thành công!');
                    setTimeout(function() {
                        window.location.href = '{{ route('admin.categories.index') }}';
                    }, 500);
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON.message || 'Lỗi khi cập nhật danh mục');
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $('.invalid-feedback').remove();
                        $.each(errors, function(key, value) {
                            $('#' + key).addClass('is-invalid').after('<span class="invalid-feedback">' + value[0] + '</span>');
                        });
                    } else {
                        toastr.error(xhr.responseJSON.message || 'Lỗi khi cập nhật danh mục');
                    }
                },
                complete: function() {
                    $('#updateBtn .spinner-border').addClass('d-none');
                    $('#updateBtn').prop('disabled', false);
                }
            });
        });
    });
</script>
--}}
@endpush

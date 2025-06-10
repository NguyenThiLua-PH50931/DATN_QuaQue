@extends('layouts.backend')

@section('title', 'Thêm thuộc tính')

@section('content')
<div class="page-body">

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="col-xxl-8 col-lg-10 m-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header-2">
                                <h5>Thêm thuộc tính</h5>
                            </div>

                            <form method="POST" action="{{ route('admin.attributes.store') }}" class="theme-form theme-form-2 mega-form">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Tên thuộc tính</label>
                                    <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                                    @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-4 row align-items-start">
                                    <label class="col-sm-3 col-form-label form-label-title">Giá trị thuộc tính</label>
                                    <div class="col-sm-9">
                                        <div id="attribute-values-wrapper">
                                            @if(old('values'))
                                            @foreach(old('values') as $oldValue)
                                            <div class="d-flex mb-2 attribute-value-row align-items-center">
                                                <input
                                                    class="form-control me-3 @error('values.' . $loop->index) is-invalid @enderror"
                                                    type="text"
                                                    name="values[]"
                                                    value="{{ $oldValue }}"
                                                    placeholder="Nhập giá trị thuộc tính">
                                                <button type="button" class="btn btn-outline-danger btn-remove-value">Remove</button>
                                                @error('values.' . $loop->index)
                                                <small class="text-danger ms-3 mt-1 d-block">{{ $message }}</small>
                                                @enderror
                                            </div>
                                            @endforeach
                                            @else
                                            <div class="d-flex mb-2 attribute-value-row align-items-center">
                                                <input
                                                    class="form-control me-3"
                                                    type="text"
                                                    name="values[]"
                                                    placeholder="Nhập giá trị thuộc tính">
                                                <button type="button" class="btn btn-outline-danger btn-remove-value">Remove</button>
                                            </div>
                                            @endif
                                        </div>

                                        <button type="button" id="btn-add-value" class="btn btn-primary mt-2">Add Value</button>

                                        @error('values.*')
                                        <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn theme-bg-color text-white">Thêm thuộc tính</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @includeIf('backend.footer')
</div>
<style>
    .btn-remove-value {
        transition: color .2s, background-color .2s, border-color .2s;
    }

    /* Hover đổi màu tím */
    .btn-remove-value:hover {
        color: #fff !important;
        /* màu tím bootstrap */
        border-color: #6f42c1 !important;
    }
</style>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        // Thêm input giá trị mới
        $('#btn-add-value').click(function() {
            var newInput = `
                <div class="d-flex mb-2 attribute-value-row">
                    <input class="form-control me-2" type="text" name="values[]" placeholder="Nhập giá trị thuộc tính">
                    <button type="button" class="btn btn-outline-danger btn-remove-value">Remove</button>
                </div>`;
            $('#attribute-values-wrapper').append(newInput);
        });

        // Xóa input giá trị
        $(document).on('click', '.btn-remove-value', function() {
            $(this).closest('.attribute-value-row').remove();
        });
    });
    $(document).ready(function() {
        var firstInvalid = $('.is-invalid').first();
        if (firstInvalid.length) {
            firstInvalid.focus();
        }
    });
</script>
@endpush
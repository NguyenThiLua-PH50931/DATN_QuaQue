@extends('layouts.backend')

@section('title', 'Thêm Banner Mới')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="title-header option-title">
                            <h5>Thêm Banner Mới</h5>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="title" class="form-label">Tiêu đề:</label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Ảnh:</label>
                                <input type="file" name="image" id="image" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="link" class="form-label">Link:</label>
                                <input type="url" name="link" id="link" class="form-control" value="{{ old('link') }}">
                            </div>

                            <div class="mb-3">
                                <label for="display_at" class="form-label">Hiển thị lúc:</label>
                                <input type="date" name="display_at" id="display_at" class="form-control" value="{{ old('display_at') }}">
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" name="active" id="active" class="form-check-input" {{ old('active') ? 'checked' : '' }}>
                                <label for="active" class="form-check-label">Hoạt động</label>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">Tạo Banner</button>
                                <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@includeIf('backend.footer')
@endsection

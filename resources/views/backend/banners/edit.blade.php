@extends('layouts.backend')

@section('title', 'Sửa Banner')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="title-header option-title">
                            <h5>Sửa Banner: {{ $banner->title }}</h5>
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

                        <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="title" class="form-label">Tiêu đề:</label>
                                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $banner->title) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Ảnh hiện tại:</label>
                                @if ($banner->image)
                                    <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="img-fluid mb-2" style="max-width: 200px;">
                                @else
                                    <p>Không có ảnh.</p>
                                @endif
                                <label for="image" class="form-label">Chọn ảnh mới (nếu muốn thay đổi):</label>
                                <input type="file" name="image" id="image" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label for="link" class="form-label">Link:</label>
                                <input type="url" name="link" id="link" class="form-control" value="{{ old('link', $banner->link) }}">
                            </div>

                            <div class="col-xxl-4 col-sm-6">
                                <label for="display_at" class="form-label">Ngày bắt đầu hiển thị</label>
                                <input type="date" class="form-control" id="display_at" name="display_at"
                                    value="{{ old('display_at', $banner->display_at ? $banner->display_at->format('Y-m-d') : '') }}">
                                @error('display_at')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-xxl-4 col-sm-6">
                                <label for="display_end_at" class="form-label">Ngày dừng hiển thị</label>
                                <input type="date" class="form-control" id="display_end_at" name="display_end_at"
                                    value="{{ old('display_end_at', $banner->display_end_at ? $banner->display_end_at->format('Y-m-d') : '') }}">
                                @error('display_end_at')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-xxl-4 col-sm-6">
                                <label for="active" class="form-label">Hoạt động</label>
                                <div class="form-check">
                                    <input type="checkbox" name="active" id="active" class="form-check-input" {{ old('active', $banner->active) ? 'checked' : '' }}>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <button type="submit" class="btn btn-primary">Cập nhật Banner</button>
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

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
                                <label for="image" class="form-label">Ảnh:</label>
                                @if ($banner->image)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $banner->image) }}" alt="Ảnh banner hiện tại" style="max-width: 200px; height: auto;">
                                    </div>
                                @endif
                                <input type="file" name="image" id="image" class="form-control">
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">Link</label>
                                <div class="col-sm-9">
                                    <input class="form-control" name="link" type="url" placeholder="Link" value="{{ old('link', $banner->link) }}">
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">Vị trí hiển thị (Location)</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="location">
                                        <option value="">-- Chọn vị trí --</option>
                                        <option value="main_hero_banner" {{ old('location', $banner->location) == 'main_hero_banner' ? 'selected' : '' }}>Banner Chính Đầu Trang</option>
                                        <option value="small_promo_banner_top" {{ old('location', $banner->location) == 'small_promo_banner_top' ? 'selected' : '' }}>Banner Đầu Trang Nhỏ Bên Phải (Trên)</option>
                                        <option value="small_promo_banner_bottom" {{ old('location', $banner->location) == 'small_promo_banner_bottom' ? 'selected' : '' }}>Banner Đầu Trang Nhỏ Bên Phải (Dưới)</option>
                                        <option value="slider_banner" {{ old('location', $banner->location) == 'slider_banner' ? 'selected' : '' }}>Banner Trượt (Slider)</option>
                                        <option value="product_section_promo_left_top" {{ old('location', $banner->location) == 'product_section_promo_left_top' ? 'selected' : '' }}>Banner Sản Phẩm Dọc - Trên</option>
                                        <option value="product_section_promo_left_bottom" {{ old('location', $banner->location) == 'product_section_promo_left_bottom' ? 'selected' : '' }}>Banner Sản Phẩm Dọc - Dưới</option>
                                        <option value="category_section_promo_left" {{ old('location', $banner->location) == 'category_section_promo_left' ? 'selected' : '' }}>Banner Sản Phẩm Theo Danh Mục - Trái</option>
                                        <option value="category_section_promo_right" {{ old('location', $banner->location) == 'category_section_promo_right' ? 'selected' : '' }}>Banner Sản Phẩm Theo Danh Mục - Phải</option>
                                        <option value="new_products_cashback_banner" {{ old('location', $banner->location) == 'new_products_cashback_banner' ? 'selected' : '' }}>Banner Sản Phẩm Mới</option>
                                        <option value="new_products_promo_left" {{ old('location', $banner->location) == 'new_products_promo_left' ? 'selected' : '' }}>Banner Sản Phẩm Mới (Trái)</option>
                                        <option value="new_products_promo_right" {{ old('location', $banner->location) == 'new_products_promo_right' ? 'selected' : '' }}>Banner Sản Phẩm Mới (Phải)</option>
                                        <option value="last_page_promo_banner" {{ old('location', $banner->location) == 'last_page_promo_banner' ? 'selected' : '' }}>Banner Cuối Trang</option>
                                    </select>
                                </div>
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

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">Kích hoạt</label>
                                <div class="col-sm-9">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="active" value="1" {{ old('active', $banner->active) ? 'checked' : '' }}>
                                    </div>
                                    @error('active')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
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

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function() {
        $('#description').summernote({
            placeholder: 'Nhập mô tả banner tại đây',
            tabsize: 2,
            height: 120,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>
@endpush

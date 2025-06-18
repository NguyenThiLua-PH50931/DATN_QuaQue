@extends('layouts.backend')

@section('title', 'Chi tiết Banner')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="title-header option-title">
                            <h5>Chi tiết Banner</h5>
                        </div>

                        <div class="p-6 rounded-lg shadow-md">
                            <div class="mb-3 row">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">ID:</label>
                                </div>
                                <div class="col-md-9">
                                    <p class="form-control-plaintext">{{ $banner->id }}</p>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-3">
                                    <h5 class="fw-bold">Tiêu đề:</h5>
                                </div>
                                <div class="col-md-9">
                                    <div class="ck-content">{!! $banner->title !!}</div>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Ảnh:</label>
                                </div>
                                <div class="col-md-9">
                                    @if ($banner->image)
                                        <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="img-fluid mb-2" style="max-width: 200px;">
                                    @else
                                        <p>Không có ảnh.</p>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Link:</label>
                                </div>
                                <div class="col-md-9">
                                    <p class="form-control-plaintext">{{ $banner->link ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Hoạt động:</label>
                                </div>
                                <div class="col-md-9">
                                    <p class="form-control-plaintext">{{ $banner->active ? 'Có' : 'Không' }}</p>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Vị trí:</label>
                                </div>
                                <div class="col-md-9">
                                    <p class="form-control-plaintext">{{ $banner->location ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Ngày hiển thị:</label>
                                </div>
                                <div class="col-md-9">
                                    <p class="form-control-plaintext">{{ $banner->display_at ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="mb-3 row">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Ngày kết thúc hiển thị:</label>
                                </div>
                                <div class="col-md-9">
                                    <p class="form-control-plaintext">{{ $banner->display_end_at ?? 'N/A' }}</p>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                                    Quay lại danh sách
                                </a>
                                <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-primary">
                                    Sửa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@includeIf('backend.footer')
@endsection

@extends('layouts.backend')

@section('title', 'Xem tin tức')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Xem chi tiết tin tức</h4>
                    </div>
                    <div class="card-body">

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">Tiêu đề</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $blog->title }}" disabled>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">Đường link</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $blog->slug }}" disabled>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-start">
                            <label class="form-label-title col-sm-3 mb-0">Nội dung</label>
                            <div class="col-sm-9">
                                <div class="border rounded p-3 bg-white" style="min-height: 200px;">
                                    {!! $blog->content !!}
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">Ảnh</label>
                            <div class="col-sm-9">
                                @if($blog->thumbnail)
                                    <img src="{{ asset($blog->thumbnail) }}" alt="Thumbnail" class="img-thumbnail" style="max-width: 200px;">
                                @else
                                    <p class="text-muted mb-0">Không có ảnh</p>
                                @endif
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">Ngày bắt đầu hiển thị</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $blog->start_date ? $blog->start_date->format('m/d/Y') : '' }}" disabled>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">Ngày dừng hiển thị</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $blog->end_date ? $blog->end_date->format('m/d/Y') : '' }}" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 d-flex justify-content-end mt-4">
                                <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary"> Quay lại</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@includeIf('backend.footer')

<style>
    .ck-content {
        color: #222;
        background-color: #fff;
        line-height: 1.6;
        white-space: pre-wrap;
        word-break: break-word;
    }
</style>
@endsection

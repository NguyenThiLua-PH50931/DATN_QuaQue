@extends('layouts.backend')

@section('title', 'Xem tin tức')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tin tức chi tiết</h4>
                    </div>
                    <div class="card-body">

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">Tiêu đề</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" value="{{ $blog->title }}" disabled>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">Đường link</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" value="{{ $blog->slug }}" disabled>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label class="form-label-title col-sm-3 mb-0">Nội dung</label>
                            <div class="col-sm-9">
                                <div class="ck-content border rounded p-3 bg-white" style="min-height: 200px;">
                                    {!! $blog->content !!}
                                </div>
                            </div>
                        </div>

                        <div class="mb-4 row">
                            <label class="form-label-title col-sm-3 mb-0">Ảnh</label>
                            <div class="col-sm-9">
                                @if($blog->thumbnail)
                                    <div class="mb-2">
                                        <img src="{{ asset($blog->thumbnail) }}" alt="Current thumbnail" 
                                            class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                @else
                                    <p class="text-muted">Không có ảnh</p>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-9 offset-sm-3 d-flex justify-content-end">
                                <a href="{{ route('admin.blog.index') }}" class="col-sm-2 btn btn-secondary">Quay lại</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@includeIf('backend.footer')

{{-- CKEditor style để hiển thị nội dung --}}
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

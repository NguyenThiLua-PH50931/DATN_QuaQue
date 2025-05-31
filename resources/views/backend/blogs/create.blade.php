@extends('layouts.backend')

@section('title', 'Thêm tin tức')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tin tức</h4>
                    </div>
                    <div class="card-body">
                        {{-- Hiển thị lỗi validate --}}
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">Tiêu đề</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" name="title" value="{{ old('title') }}" required>
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">Đường link</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" name="slug" value="{{ old('slug') }}">
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">Nội dung</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="content" rows="6" required>{{ old('content') }}</textarea>
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="form-label-title col-sm-3 mb-0">Ảnh</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="file" name="thumbnail" accept="image/*" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-9 offset-sm-3 d-flex justify-content-end">
                                    <a href="{{ route('admin.blog.index') }}" class="col-sm-2 btn btn-secondary me-2">Hủy</a>
                                    <button type="submit" class="col-sm-2 btn btn-primary" >Tạo mới</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- New Product Add End -->
@includeIf('backend.footer')
@endsection
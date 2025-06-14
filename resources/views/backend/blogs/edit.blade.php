@extends('layouts.backend')

@section('title', 'Cập nhật tin tức')

@section('content')
        <div class="page-body">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-10 m-auto">
                        <div class="card">
                            <div class="card-body">
                                <div class="title-header option-title">
                                    <h5>Cập nhật tin tức</h5>
                                </div>

                                {{-- Hiển thị lỗi --}}
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form class="theme-form theme-form-2 mega-form" 
                                      action="{{ route('admin.blog.update', $blog->id) }}" 
                                      method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-lg-2 col-md-3 mb-0">Tiêu đề</label>
                                        <div class="col-md-9 col-lg-10">
                                            <input class="form-control" type="text" name="title"
                                                   value="{{ old('title', $blog->title) }}" required>
                                        </div>
                                    </div>

                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-lg-2 col-md-3 mb-0">Đường link</label>
                                        <div class="col-md-9 col-lg-10">
                                            <input class="form-control" type="text" name="slug"
                                                   value="{{ old('slug', $blog->slug) }}"
                                                   placeholder="Tự động tạo nếu để trống">
                                        </div>
                                    </div>

                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-lg-2 col-md-3 mb-0">Nội dung</label>
                                        <div class="col-md-9 col-lg-10">
                                            <textarea class="form-control content-editor" name="content" rows="6"
                                                      placeholder="Nhập nội dung bài viết">{{ old('content', $blog->content) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-lg-2 col-md-3 mb-0">Ảnh đại diện</label>
                                        <div class="col-md-9 col-lg-10">
                                            @if($blog->thumbnail)
                                                <div class="mb-2">
                                                    <img src="{{ asset($blog->thumbnail) }}" alt="Ảnh hiện tại"
                                                         class="img-thumbnail" style="max-width: 200px;">
                                                </div>
                                            @endif
                                            <input class="form-control" type="file" name="thumbnail" accept="image/*">
                                            <small class="text-muted">Bỏ trống nếu không thay đổi</small>
                                        </div>
                                    </div>

                                    <div class="mb-4 row align-items-center">
                                        <label for="start_date" class="col-lg-2 col-md-3 col-form-label form-label-title">Ngày hiển thị</label>
                                        <div class="col-md-3 col-lg-3">
                                            <input type="date" class="form-control" id="start_date" name="start_date"
                                                   value="{{ old('start_date', optional($blog->start_date)->format('Y-m-d')) }}">
                                            @error('start_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4 row align-items-center">
                                        <label for="end_date" class="col-lg-2 col-md-3 col-form-label form-label-title">Ngày dừng hiển thị</label>
                                        <div class="col-md-3 col-lg-3">
                                            <input type="date" class="form-control" id="end_date" name="end_date"
                                                   value="{{ old('end_date', optional($blog->end_date)->format('Y-m-d')) }}">
                                            @error('end_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>                                    

                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end mt-4">
                                            <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary me-2">Hủy</a>
                                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @includeIf('backend.footer')
        </div>

        <style>
            .ck-editor__editable {
                color: #222 !important;
                background: #fff;
                height: 300px !important;
            }
        </style>

        {{-- CKEditor --}}
        <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
        <script>
            let contentEditors = [];

            function initContentEditors() {
                document.querySelectorAll('.content-editor').forEach((textarea) => {
                    if (!textarea.classList.contains('ck-editor-initialized')) {
                        ClassicEditor.create(textarea).then(editor => {
                            contentEditors.push({ editor, textarea });
                            textarea.classList.add('ck-editor-initialized');
                        });
                    }
                });
            }

            initContentEditors();

            document.querySelector('form').addEventListener('submit', function (e) {
                let isValid = true;

                contentEditors.forEach(({ editor, textarea }) => {
                    const data = editor.getData().trim();
                    textarea.value = data;

                    if (!data) {
                        isValid = false;
                        alert("Nội dung không được để trống.");
                    }
                });

                if (!isValid) e.preventDefault();
            });
        </script>
@endsection

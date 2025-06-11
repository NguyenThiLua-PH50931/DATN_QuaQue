@extends('layouts.backend')

@section('title', 'Thêm tin tức')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-sm-10 m-auto">
                            <div class="card">
                                <div class="card-body">
                                    <div class="title-header option-title">
                                        <h5>Thêm tin tức mới</h5>
                                    </div>

                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-home" type="button">Tin tức</button>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel">
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            <form class="theme-form theme-form-2 mega-form" method="POST"
                                                action="{{ route('admin.blog.store') }}" enctype="multipart/form-data">
                                                @csrf

                                                <div class="mb-4 row align-items-center">
                                                    <label class="col-lg-2 col-md-3 col-form-label form-label-title">Tiêu đề</label>
                                                    <div class="col-md-9 col-lg-10">
                                                        <input class="form-control" type="text" name="title" value="{{ old('title') }}">
                                                        @error('title')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="mb-4 row align-items-center">
                                                    <label class="col-lg-2 col-md-3 col-form-label form-label-title">Đường link</label>
                                                    <div class="col-md-9 col-lg-10">
                                                        <input class="form-control" type="text" name="slug" value="{{ old('slug') }}">
                                                        @error('slug')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="mb-4 row align-items-center">
                                                    <label class="col-lg-2 col-md-3 col-form-label form-label-title">Nội dung</label>
                                                    <div class="col-md-9 col-lg-10">
                                                        <textarea class="form-control content-editor" name="content">{{ old('content') }}</textarea>
                                                        @error('content')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="mb-4 row align-items-center">
                                                    <label class="col-lg-2 col-md-3 col-form-label form-label-title">Ảnh</label>
                                                    <div class="col-md-9 col-lg-10">
                                                        <input class="form-control" type="file" name="thumbnail" accept="image/*">
                                                        @error('thumbnail')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12 d-flex justify-content-end mt-4">
                                                        <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary me-2">Hủy</a>
                                                        <button type="submit" class="btn btn-primary">Tạo mới</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div> <!-- tab-pane -->
                                    </div> <!-- tab-content -->
                                </div> <!-- card-body -->
                            </div> <!-- card -->
                        </div> <!-- col -->
                    </div> <!-- row -->
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

    {{-- CKEditor 5 --}}
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

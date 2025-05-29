@extends('layouts.backend')

@section('title', 'Xem tin tức')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Blog Detail</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">Blog Title</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" name="title" 
                                    value="{{ old('title', $blog->title) }}" placeholder="Blog Name" disabled>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">Slug</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" name="slug" 
                                    value="{{ old('slug', $blog->slug) }}" placeholder="Slug (auto-generated if blank)" disabled>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">Content</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" name="content" rows="6" 
                                    placeholder="Blog content" disabled>{{ old('content', $blog->content) }}</textarea>
                            </div>
                        </div>

                        <div class="mb-4 row align-items-center">
                            <label class="form-label-title col-sm-3 mb-0">Thumbnail</label>
                            <div class="col-sm-9">
                                @if($blog->thumbnail)
                                    <div class="mb-2">
                                        <img src="{{ asset($blog->thumbnail) }}" alt="Current thumbnail" 
                                            class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                @endif
                                <input class="form-control" type="file" name="thumbnail" accept="image/*" disabled>
                                <small class="text-muted">Leave blank if no change needed</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-9 offset-sm-3 d-flex justify-content-end">
                                <a href="{{ route('admin.blogs.index') }}" class="col-sm-2 btn btn-secondary">Back</a>
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

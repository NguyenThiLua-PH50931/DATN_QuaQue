@extends('layouts.backend') {{-- Kế thừa layout chính --}}

@section('title', 'Quản lý tin tức') {{-- Tiêu đề trang --}}

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="title-header option-title d-sm-flex d-block">
                            <h5>Tin tức</h5>
                            <div class="right-options">
                                <ul>
                                    <li>
                                        <a class="btn btn-solid" href="{{ route('admin.blog.create') }}">Thêm mới</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <div class="table-responsive">
                                <table class="table all-package theme-table table-product" id="table_id">
                                    <thead>
                                        <tr>
                                            <th>Ảnh</th>
                                            <th>Tiêu đề</th>
                                            <th>Đường Link</th>
                                            <th>Ngày tạo</th>
                                            <th>Hàng động</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($blog as $item)
                                        <tr>
                                            <td>
                                                <div class="table-image">
                                                    @if($item->thumbnail)
                                                        <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->title }}" width="100">
                                                    @endif
                                                </div>
                                            </td>

                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->slug }}</td>
                                            <td>{{ $item->created_at->format('d/m/Y') }}</td>

                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="{{ route('admin.blog.show', $item->id) }}">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="{{ route('admin.blog.edit', $item->id) }}">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                    </li>                                                    

                                                    <li>
                                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal{{ $item->id }}">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @foreach($blog as $item)
                                    <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $item->id }}">Delete Confirmation</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want to delete the post <strong>{{ $item->title }}</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('admin.blog.destroy', $item->id) }}" method="POST" class="d-flex justify-content-end">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Hủy</button>
                                                        <button type="submit" class="btn btn-danger">Xóa</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->

@includeIf('backend.footer')
@endsection
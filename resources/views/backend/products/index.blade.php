@extends('layouts.backend')

@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="title-header option-title d-sm-flex d-block">
                            <h5>Danh sách sản phẩm</h5>
                            <div class="right-options">
                                <ul>
                                    <li>
                                        <a href="javascript:void(0)" style="color: black">Import</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" style="color: black">Export</a>
                                    </li>
                                    <li>
                                        <a class="btn btn-solid" href="{{ route('admin.products.create') }}">Thêm sản phẩm</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <div class="table-responsive">
                                <table class="table all-package theme-table table-product" id="table_id">
                                    <thead>
                                        <tr>
                                            <th style="color: black; background-color: #f8f9fa;">Tên sản phẩm</th>
                                            <th style="color: black; background-color: #f8f9fa;">Ảnh</th>
                                            <th style="color: black; background-color: #f8f9fa;">Danh mục</th>
                                            <th style="color: black; background-color: #f8f9fa;">Vùng miền</th>
                                            <th style="color: black; background-color: #f8f9fa;">Cập nhật lúc</th>
                                            <th style="color: black; background-color: #f8f9fa;">Trạng thái</th>
                                            <th style="color: black; background-color: #f8f9fa;">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($products as $product)
                                            <tr class="product-row">
                                                <td>{{ $product->name }}</td>
                                                <td>
                                                    <div class="table-image">
                                                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid" alt="{{ $product->name ?? '' }}" style="width:64px;height:64px;object-fit:cover;border-radius:8px;">
                                                    </div>
                                                </td>
                                                <td>{{ $product->category->name ?? '' }}</td>
                                                <td>{{ $product->region->name ?? '' }}</td>
                                                <td>{{ $product->updated_at->format('d/m/Y H:i:s') }}</td>
                                                <td>
                                                    <span class="badge {{ $product->active ? 'bg-success' : 'bg-danger' }}" style="cursor:pointer">
                                                        {{ $product->active ? 'Đang bán' : 'Ngừng bán' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <ul class="d-flex gap-2 list-unstyled">
                                                        <li>
                                                            <a href="{{ route('admin.products.show', $product->slug) }}" class="action-link" title="Xem chi tiết">
                                                                <i class="ri-eye-line"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('admin.products.edit', $product->id) }}" class="action-link" title="Chỉnh sửa">
                                                                <i class="ri-pencil-line"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="#" class="action-link text-danger" data-bs-toggle="modal" data-bs-target="#deleteOneModal" data-id="{{ $product->id }}" data-name="{{ $product->name ?? '' }}" title="Xóa">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Không có sản phẩm nào.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3">
                                {!! $products->links() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteOneModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="deleteOneModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="delete-one-form" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-body text-center">
                        <h5 class="modal-title mb-2">Xóa sản phẩm?</h5>
                        <p id="delete-one-message">Bạn chắc chắn muốn xóa sản phẩm này?</p>
                        <div class="button-box mt-4">
                            <button type="button" class="btn btn--no btn-secondary" data-bs-dismiss="modal">Không</button>
                            <button type="submit" class="btn btn--yes btn-danger">Có, xóa</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @includeIf('backend.footer')
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#table_id').DataTable({
            language: {
                search: "Tìm kiếm:",
                lengthMenu: "Hiển thị _MENU_ sản phẩm",
                info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ sản phẩm",
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    next: "Sau",
                    previous: "Trước"
                },
                zeroRecords: "Không tìm thấy sản phẩm nào.",
            }
        });
    });
</script>

<script>
    $(function() {
        $('#deleteOneModal').on('show.bs.modal', function(e) {
            var button = $(e.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');

            var modal = $(this);
            modal.find('#delete-one-message').text('Bạn chắc chắn muốn xóa sản phẩm "' + name + '"?');

            modal.find('#delete-one-form').attr('action', '{{ route('admin.products.destroy', ':id') }}'.replace(':id', id));
        });
    });
</script>
@endpush

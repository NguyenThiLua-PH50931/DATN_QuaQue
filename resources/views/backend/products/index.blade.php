
@extends('layouts.backend')
@section('title', 'Quản lý sản phẩm')
@section('content')
<style>
    .product-table-wrapper {
        background: #f8fafd;
        border-radius: 12px;
        box-shadow: 0 2px 14px #dbeafe40;
        padding: 16px;
    }

    .product-table thead tr,
    .product-table tfoot tr {
        background: #f1f5f9;
    }

    .product-row {
        background: #fff;
        border-radius: 10px;
        border-bottom: 1px solid #e5e7eb;
        transition: box-shadow .2s;
    }

    .product-row:hover {
        background: #eef6ff;
        box-shadow: 0 2px 8px #93c5fd22;
    }

    .action-link {
        color: #7c3aed !important;
        font-weight: 500;
    }

    .bg-purple {
        background: #a78bfa;
        color: #fff;
    }
</style>
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
                                    <li><a href="#">Nhập file</a></li>
                                    <li><a href="#">Xuất file</a></li>
                                    <li>
                                        <a class="btn btn-solid" href="">Thêm sản phẩm</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <div class="table-responsive">
                                <div class="product-table-wrapper">
                                    <form id="bulk-delete-form" method="POST" action="{{ route('admin.products.bulkDelete') }}">
                                        @csrf
                                        <table class="table product-table align-middle">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="select-all"></th>
                                                    <th>Thông tin</th>
                                                    <th>Ảnh</th>
                                                    <th>Danh mục</th>
                                                    <th>Vùng miền</th>
                                                    <th>Cập nhật lúc</th>
                                                    <th>Trạng thái</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $product)
                                                <tr class="product-row">
                                                    <td>
                                                        <input type="checkbox" class="row-checkbox" name="ids[]" value="{{ $product->id }}">
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <a href="" class="fw-bold text-primary" style="font-size:16px;">
                                                                {{ $product->name }}
                                                            </a>
                                                            <div class="small text-muted mt-1">
                                                                {{ $product->short_desc ?? '' }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <img src="{{ asset('storage/' . $product->image) }}" class="product-thumb" alt="" style="width:64px;height:64px;object-fit:cover;border-radius:8px;">
                                                    </td>
                                                    <td>{{ $product->category->name ?? '' }}</td>
                                                    <td>{{ $product->region->name ?? '' }}</td>
                                                    <td>{{ $product->updated_at->format('d/m/Y H:i:s') }}</td>
                                                    <td>
                                                        <span class="badge {{ $product->active ? 'bg-success' : 'bg-danger' }} status-badge"
                                                            style="cursor:pointer"
                                                            data-id="{{ $product->id }}"
                                                            data-name="{{ $product->name ?? '' }}"
                                                            data-status="{{ $product->active }}">
                                                            {{ $product->active ? 'Đang bán' : 'Ngừng bán' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="" class="action-link text-decoration-none me-2"><i class="ri-eye-line"></i> </a>
                                                        <a href="" class="action-link text-decoration-none me-2"><i class="ri-pencil-line"></i> </a>
                                                        <a href="#" class="action-link text-decoration-none text-danger" data-bs-toggle="modal"
                                                            data-bs-target="#deleteOneModal"
                                                            data-id="{{ $product->id }}"
                                                            data-name="{{ $product->name }}">
                                                            <i class="ri-delete-bin-line"></i> 
                                                        </a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>

                                        <button type="button" id="delete-selected" class="btn btn-danger btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#deleteBulkModal" disabled>
                                            Xóa đã chọn
                                        </button>
                                    </form>
                                    <div class="mt-2">
                                        {!! $products->links('pagination::bootstrap-5') !!}
                                    </div>
                                </div>
                                <div>
                                    {!! $products->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @includeIf('backend.footer')
</div>
<!-- Modal đổi trạng thái sản phẩm -->
<div class="modal fade" id="statusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="modal-title mb-3" id="statusModalLabel">Đổi trạng thái sản phẩm</h5>
                <p id="modal-status-text"></p>
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="button-box text-end mt-4">
                    <button type="button" class="btn btn--no btn-secondary me-2" data-bs-dismiss="modal">Không</button>
                    <form id="status-toggle-form" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn--yes btn-primary">Đồng ý</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Xác Nhận Xóa Nhiều -->
<div class="modal fade" id="deleteBulkModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="deleteBulkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h5 class="modal-title mb-2">Xóa sản phẩm đã chọn?</h5>
                <p>Bạn chắc chắn muốn xóa các sản phẩm đã chọn?</p>
                <div class="button-box mt-4">
                    <button type="button" class="btn btn--no btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" id="confirm-bulk-delete" class="btn btn--yes btn-danger">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Xác Nhận Xóa Một -->
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
                        <button type="button" class="btn btn--no btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn btn--yes btn-danger">Yes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('#productTable').DataTable({
            "lengthMenu": [5, 10, 25, 50, 100, -1],
            "language": {
                "lengthMenu": "Hiển thị _MENU_ dòng/trang",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "info": "Hiển thị _START_ đến _END_ của _TOTAL_ dòng",
                "infoEmpty": "Không có dữ liệu",
                "infoFiltered": "(lọc từ _MAX_ dòng)",
                "search": "Tìm kiếm:",
                "paginate": {
                    "first": "Đầu",
                    "last": "Cuối",
                    "next": "Tiếp",
                    "previous": "Trước"
                },
            },
            "columnDefs": [{
                    "orderable": false,
                    "targets": [0, 6]
                } // Disable sort ảnh và tùy chọn
            ]
        });
    });
</script>
@push('scripts')
@push('scripts')
<script>
    $('.status-badge').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name'); // phải có data-name ở trên
        var status = $(this).data('status');

        let nextStatus = (status == 1) ? 'Ngừng bán' : 'Đang bán';
        // Nếu name rỗng (undefined/null), show id cho dễ debug
        if (!name) name = '(ID ' + id + ')';
        $('#modal-status-text').html('Bạn muốn chuyển trạng thái sản phẩm <b>' + name + '</b> sang <span class="text-primary">' + nextStatus + '</span>?');

        // Set action cho form
        let url = '{{ route("admin.products.toggle", ":id") }}';
        url = url.replace(':id', id);
        $('#status-toggle-form').attr('action', url);

        // Show modal
        var modal = new bootstrap.Modal(document.getElementById('statusModal'));
        modal.show();
    });
</script>
@endpush

<script>
    $(function() {
        // Select all
        $('#select-all').on('change', function() {
            $('.row-checkbox').prop('checked', $(this).is(':checked'));
            updateBulkDeleteBtn();
        });
        $('.row-checkbox').on('change', function() {
            updateBulkDeleteBtn();
        });

        function updateBulkDeleteBtn() {
            $('#delete-selected').prop('disabled', $('.row-checkbox:checked').length === 0);
        }

        // Modal xóa 1
        $('#deleteOneModal').on('show.bs.modal', function(e) {
            var button = $(e.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            $('#delete-one-form').attr('action', '/admin/products/' + id);
            $('#delete-one-message').text('Bạn chắc chắn muốn xóa sản phẩm "' + name + '"?');
        });

        // Xác nhận xóa nhiều
        $('#confirm-bulk-delete').on('click', function() {
            $('#bulk-delete-form').submit();
        });
    });
</script>
@endpush

@endpush

@endsection
@extends('layouts.backend')

@section('title', 'Quản lý thuộc tính')

@section('content')
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

<style>
    /* Giữ nguyên toàn bộ CSS từ bảng sản phẩm */
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

    .bulk-delete-btn[disabled] {
        background: #e1e8f3 !important;
        color: #66708a !important;
        border: none !important;
        cursor: not-allowed !important;
        opacity: 1 !important;
    }

    .bulk-delete-btn[disabled] .delete-bulk-icon {
        color: #66708a !important;
    }

    .bulk-delete-btn:not([disabled]) {
        background: #becde4 !important;
        color: #495057 !important;
        border: none !important;
        cursor: pointer !important;
        box-shadow: 0 2px 8px #becde480;
        transition: background .5s, color .5s;
    }

    .bulk-delete-btn:not([disabled]) .delete-bulk-icon {
        color: #495057 !important;
    }

    .bulk-delete-btn:not([disabled]):hover {
        background: #aac4e7 !important;
    }

    /* Phân trang DataTables */
    .dataTables_paginate .pagination .page-item .page-link {
        min-width: 36px;
        height: 36px;
        font-size: 16px;
        border-radius: 8px;
        margin: 0 1.5px;
        color: #495057;
        background: none;
        border: none !important;
        font-weight: 500;
        box-shadow: none;
        transition: background 0.15s, color 0.15s;
        cursor: pointer;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .dataTables_paginate .pagination .page-item.active .page-link,
    .dataTables_paginate .pagination .page-item.active .page-link:hover,
    .dataTables_paginate .pagination .page-item.active .page-link:focus,
    .dataTables_wrapper .dataTables_paginate .paginate_button.active,
    .dataTables_wrapper .dataTables_paginate .paginate_button.active:hover,
    .dataTables_wrapper .dataTables_paginate .paginate_button.active:focus {
        background: #0da487 !important;
        border-radius: 7px;
        color: #fff !important;
        background-image: none !important;
        box-shadow: 0 2px 8px #0da48725 !important;
        border: none !important;
        outline: none !important;
    }

    .dataTables_paginate .pagination .page-item .page-link {
        min-width: 28px;
        height: 28px;
        font-size: 14px;
        border-radius: 6px;
        margin: 0 1px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .dataTables_paginate .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
        background: #fff !important;
        color: #0da487 !important;
        box-shadow: 0 2px 8px #0da48725;
        border: none !important;
    }

    .dataTables_paginate .pagination .page-item.disabled .page-link {
        color: #e5e7eb !important;
        background: none !important;
        pointer-events: none;
    }

    .dataTables_paginate .pagination .page-item .ellipsis {
        pointer-events: none;
        color: #b1bacf !important;
        background: none !important;
        font-size: 16px;
        border: none !important;
    }

    .dataTables_paginate .pagination .page-item.active .page-link:hover,
    .dataTables_paginate .pagination .page-item.active .page-link:focus {
        background: #0da487 !important;
        color: #fff !important;
        box-shadow: 0 2px 8px #0da48725 !important;
        border: none !important;
        outline: none !important;
        pointer-events: none;
    }
</style>

<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="title-header option-title d-sm-flex d-block">
                            <h5>Danh sách thuộc tính</h5>
                            <div class="right-options">
                                <ul>
                                    <li><a href="#">Nhập file</a></li>
                                    <li><a href="#">Xuất file</a></li>
                                    <li>
                                        <a class="btn btn-solid" href="{{ route('admin.attributes.create') }}">Thêm thuộc tính</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <div class="product-table-wrapper">
                                <form id="bulk-delete-form" method="POST" action="{{ route('admin.attributes.bulkDelete') }}">
                                    @csrf
                                    <table class="table product-table align-middle" id="attributeTable">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="select-all"></th>
                                                <th>Tên Thuộc Tính</th>
                                                <th>Giá rị Thuộc Tính</th>
                                                <th>Hành Động</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($attributes as $attribute)
                                            <tr class="product-row">
                                                <td>
                                                    <input type="checkbox" class="row-checkbox" name="ids[]" value="{{ $attribute->slug }}">
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.attributes.edit', $attribute->id) }}" class="fw-bold text-primary" style="font-size:16px;">
                                                        {{ $attribute->name }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @php
                                                    $values = $attribute->values->pluck('value')->toArray();
                                                    @endphp
                                                    {{ implode(', ', $values) }}
                                                </td>
                                                <td>

                                                    <a href="{{ route('admin.attributes.edit', $attribute->slug) }}" class="action-link text-decoration-none me-2">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" class="action-link text-danger" data-bs-toggle="modal" data-bs-target="#deleteAttributeModal" data-id="{{ $attribute->id }}" data-name="{{ $attribute->name }}">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>

                                    </table>

                                    <button type="button" id="delete-selected" class="btn bulk-delete-btn btn-sm mt-2 d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#deleteBulkModal" disabled>
                                        <i class="ri-delete-bin-line delete-bulk-icon"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @includeIf('backend.footer')
</div>

<!-- Modal Xác nhận Xóa Một -->
<div class="modal fade" id="deleteAttributeModal" tabindex="-1" aria-labelledby="deleteAttributeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="delete-attribute-form" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center">
                    <h5 class="modal-title mb-2">Xóa thuộc tính?</h5>
                    <p id="delete-attribute-message">Bạn chắc chắn muốn xóa thuộc tính này?</p>
                    <div class="button-box mt-4">
                        <button type="button" class="btn btn--no btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn btn--yes btn-danger">Yes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Xác nhận Xóa Nhiều -->
<div class="modal fade" id="deleteBulkModal" tabindex="-1" aria-labelledby="deleteBulkModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <h5 class="modal-title mb-2">Xóa các thuộc tính đã chọn?</h5>
                <p>Bạn chắc chắn muốn xóa các thuộc tính đã chọn?</p>
                <div class="button-box mt-4">
                    <button type="button" class="btn btn--no btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" id="confirm-bulk-delete" class="btn btn--yes btn-danger">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Khởi tạo DataTable
        $('#attributeTable').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [10, 25, 50, 100],
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
                    "next": "Sau",
                    "previous": "Trước"
                }
            },
            "columnDefs": [{
                "orderable": false,
                "targets": [0, 3]
            }]
        });

        // Fix nhãn "last" thành số cuối, ẩn khi chỉ 1 trang
        $('#attributeTable').on('draw.dt', function() {
            var table = $('#attributeTable').DataTable();
            var totalPages = table.page.info().pages;

            if (totalPages === 1) {
                $('.paginate_button').not('.previous,.active,.next').hide();
                $('.paginate_button.last').hide();
            } else {
                $('.paginate_button').show();
                $('.paginate_button.last a').text(totalPages);
                $('.paginate_button.first a').text(1);
            }
        });

        // Select all checkbox
        $('#select-all').on('change', function() {
            $('.row-checkbox').prop('checked', $(this).is(':checked'));
            updateBulkDeleteBtn();
        });

        // Checkbox từng dòng
        $('.row-checkbox').on('change', function() {
            updateBulkDeleteBtn();
        });

        function updateBulkDeleteBtn() {
            $('#delete-selected').prop('disabled', $('.row-checkbox:checked').length === 0);
        }

        // Modal xóa một thuộc tính
        $('#deleteAttributeModal').on('show.bs.modal', function(e) {
            var button = $(e.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var form = $('#delete-attribute-form');
            form.attr('action', '/admin/attributes/' + id);
            $('#delete-attribute-message').text('Bạn chắc chắn muốn xóa thuộc tính "' + name + '"?');
        });

        // Xác nhận xóa nhiều thuộc tính
        $('#confirm-bulk-delete').on('click', function() {
            $('#bulk-delete-form').submit();
        });
    });
</script>
@endpush

@endsection
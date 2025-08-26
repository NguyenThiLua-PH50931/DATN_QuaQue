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
                                        <a class="btn btn-solid" href="{{ route('admin.products.create') }}">Thêm sản phẩm</a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                        @endif

                        @if (session('success_modal'))
                        <div class="alert alert-success">
                            {{ session('success_modal') }}
                        </div>
                        @endif

                        @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                        @endif

                        <nav class="navbar navbar-expand-lg navbar-filters mb-3">
                            <ul class="navbar-nav align-items-center">
                                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">

                                <li class="nav-item me-3 d-none d-lg-block">
                                    <a class="nav-link" style="color:#8ca0b3; font-size:20px; cursor:pointer;">
                                        <span class="la la-filter"></span>
                                        {{-- Hoặc dùng Font Awesome: <i class="fa fa-filter"></i> --}}
                                    </a>
                                    {{-- Filter: Danh mục --}}
                                <li class="nav-item me-3">
                                    <div class="filter-dropdown" id="filterCategoryDropdown">
                                        <div class="filter-dropdown-toggle btn btn-outline-primary d-flex align-items-center" tabindex="0" role="button" aria-haspopup="true" aria-expanded="false">
                                            Danh mục
                                            <span class="arrow ms-2"></span>
                                        </div>
                                        <div class="filter-dropdown-menu bg-white border rounded shadow p-3">
                                            <div class="filter-selected-items mb-2" tabindex="0" aria-live="polite" aria-atomic="true"></div>
                                            <input type="text" class="filter-search-input form-control form-control-sm mb-2" placeholder="Tìm kiếm danh mục..." aria-label="Tìm kiếm danh mục" />
                                            <div class="filter-options-list">
                                                @foreach($categories as $category)
                                                <label class="d-block mb-1" style="cursor: pointer;">
                                                    <input type="radio" name="category" value="{{ $category->id }}" class="me-1 d-none" />
                                                    <span>{{ $category->name }}</span>
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                {{-- Filter: Vùng miền --}}
                                <li class="nav-item me-3">
                                    <div class="filter-dropdown" id="filterRegionDropdown">
                                        <div class="filter-dropdown-toggle btn btn-outline-primary d-flex align-items-center" tabindex="0" role="button" aria-haspopup="true" aria-expanded="false">
                                            Vùng miền
                                            <span class="arrow ms-2"></span>
                                        </div>
                                        <div class="filter-dropdown-menu bg-white border rounded shadow p-3">
                                            <div class="filter-selected-items mb-2" tabindex="0" aria-live="polite" aria-atomic="true"></div>
                                            <input type="text" class="filter-search-input form-control form-control-sm mb-2" placeholder="Tìm kiếm vùng miền..." aria-label="Tìm kiếm vùng miền" />
                                            <div class="filter-options-list">
                                                @foreach($regions as $region)
                                                <label class="d-block mb-1" style="cursor: pointer;">
                                                    <input type="radio" name="region" value="{{ $region->id }}" class="me-1 d-none" />
                                                    <span>{{ $region->name }}</span>
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                {{-- Filter: Trạng thái --}}
                                <li class="nav-item me-3">
                                    <div class="filter-dropdown" id="filterStatusDropdown">
                                        <div class="filter-dropdown-toggle btn btn-outline-primary d-flex align-items-center" tabindex="0" role="button" aria-haspopup="true" aria-expanded="false">
                                            Trạng thái
                                            <span class="arrow ms-2"></span>
                                        </div>
                                        <div class="filter-dropdown-menu bg-white border rounded shadow p-3">
                                            <div class="filter-selected-items mb-2" tabindex="0" aria-live="polite" aria-atomic="true"></div>
                                            <input type="text" class="filter-search-input form-control form-control-sm mb-2" placeholder="Tìm kiếm trạng thái..." aria-label="Tìm kiếm trạng thái" />
                                            <div class="filter-options-list">
                                                <label class="d-block mb-1" style="cursor: pointer;">
                                                    <input type="radio" name="status" value="1" class="me-1 d-none" />
                                                    <span>Đang bán</span>
                                                </label>
                                                <label class="d-block mb-1" style="cursor: pointer;">
                                                    <input type="radio" name="status" value="0" class="me-1 d-none" />
                                                    <span>Ngừng bán</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                {{-- Remove Filters --}}
                                <li class="nav-item ms-3">
                                    <a href="#" id="removeFilters" class="nav-link text-danger d-none" title="Xóa lọc">
                                        <i class="fa fa-eraser"></i> Xóa lọc
                                    </a>
                                </li>

                            </ul>
                        </nav>

                        <div class="table-responsive" style="overflow-x:unset;"> {{-- bỏ thuộc tính max-width nếu có --}}
                            <table class="table w-100" id="productTable" style="min-width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="color: black; background-color: #f8f9fa; width: 30px;">
                                            <input type="checkbox" id="select-all-checkbox">
                                        </th>
                                        <th style="color: black; background-color: #f8f9fa;">Tên sản phẩm</th>
                                        <th style="color: black; background-color: #f8f9fa;">Ảnh</th>
                                        <th style="color: black; background-color: #f8f9fa;">Danh mục</th>
                                         <th style="color: black; background-color: #f8f9fa;">Vùng miền</th> 
                                        <th style="color: black; background-color: #f8f9fa;">Cập nhật lúc</th>
                                        <th style="color: black; background-color: #f8f9fa;">Trạng thái</th>
                                        <th style="color: black; background-color: #f8f9fa;">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $product)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="row-checkbox" name="selected_ids[]" value="{{ $product->id }}">
                                        </td>
                                        <td>
                                            <div>
                                                <a href="javascript:void(0)"
                                                    class="fw-bold text-primary product-name"
                                                    style="font-size:16px;"
                                                    data-id="{{ $product->id }}"
                                                    data-slug="{{ $product->slug }}"
                                                    data-name="{{ $product->name }}"
                                                    data-desc="{{ $product->short_desc ?? '' }}"
                                                    data-image="{{ asset('storage/' . $product->image) }}"
                                                    data-category="{{ $product->categories->pluck('name')->implode(', ') }}"
                                                    data-region="{{ $product->region->name ?? '' }}"
                                                    data-updated="{{ $product->updated_at->format('d-m-Y H:i:s') }}"
                                                    data-status="{{ $product->active ? 'Đang bán' : 'Ngừng bán' }}">
                                                    {{ Str::limit($product->name, 20) }}
                                                </a>
                                                <div class="small text-muted mt-1">
                                                    {{ $product->short_desc ?? '' }}
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                class="w-20 h-20 object-cover" width="100px">
                                        </td>
                                        <td>{{ $product->categories->pluck('name')->implode(', ') }}</td>
                                        <td>{{ $product->region->name ?? '' }}</td>
                                        <td>{{ $product->updated_at->format('d-m-Y H:i:s') }}</td>
                                        <td class="{{ $product->active ? 'status-close' : 'status-danger' }}">
                                            <span class="badge status-badge"
                                                style="cursor:pointer"
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                                data-status="{{ $product->active }}">
                                                {{ $product->active ? 'Đang bán' : 'Ngừng bán' }}
                                            </span>
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center justify-content-center gap-2 action-icons">
                                                <a href="{{ route('client.product.detail', $product->slug) }}" class="text-primary" title="Xem (Client)" target="_blank" rel="noopener">
                                                    <i class="ri-links-line"></i>
                                                </a>
                                                <a href="{{ route('admin.products.show', $product->slug) }}" class="text-secondary" title="Xem">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product->slug) }}" class="text-primary" title="Sửa">
                                                    <i class="ri-pencil-line"></i>
                                                </a>
                                                <a href="javascript:void(0)" class="delete-btn text-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    data-id="{{ $product->id }}"
                                                    data-name="{{ $product->name }}"
                                                    title="Xóa">
                                                    <i class="ri-delete-bin-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="py-4 px-4 text-center">Không có sản phẩm nào.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <button type="button"
                                id="delete-selected-variants"
                                class="btn bulk-delete-btn btn-sm mt-2 d-inline-flex align-items-center gap-2"
                                data-bs-toggle="modal"
                                data-bs-target="#bulkDeleteModal"
                                disabled>
                                <i class="ri-delete-bin-line delete-bulk-icon"></i> Xóa chọn
                            </button>
                        </div>
                        <form id="bulk-delete-form" action="{{ route('admin.products.bulkDelete') }}" method="POST" style="display: none;">
                            @csrf
                            <input type="hidden" name="ids" id="bulk-delete-ids">
                        </form>
                    </div>
                    </li>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="status-toggle-form" method="POST" action="">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Đổi trạng thái sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div id="modal-status-text">Bạn có chắc chắn muốn đổi trạng thái không?</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Xác nhận</button>
                </div>
            </div>
        </form>
    </div>
</div>
{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa sản phẩm này không? Sản phẩm sẽ được xóa mềm và có thể khôi phục sau này.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteForm" method="POST" action="" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa mềm</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Bulk Delete Modal --}}
<div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkDeleteModalLabel">Xác nhận xóa hàng loạt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa mềm <span id="selectedProductCount"></span> sản phẩm đã chọn không?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirm-bulk-delete-btn">Xóa hàng loạt</button>
            </div>
        </div>
    </div>
</div>

{{-- Success Message Modal --}}
<div class="modal fade" id="successMessageModal" tabindex="-1" aria-labelledby="successMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successMessageModalLabel">Thành công!</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="successMessageContent">
                <!-- Message will be inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

{{-- Error Message Modal --}}
<div class="modal fade" id="errorMessageModal" tabindex="-1" aria-labelledby="errorMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="errorMessageModalLabel">Lỗi!</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="errorMessageContent">
                <!-- Message will be inserted here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="productInfoModal" tabindex="-1" aria-labelledby="productInfoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productInfoLabel">Thông tin sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th>Thông tin:</th>
                            <td id="modalNameDesc"></td>
                        </tr>
                        <tr>
                            <th>Ảnh thumb:</th>
                            <td><img id="modalImage" src="" alt="" style="max-width:100px;"></td>
                        </tr>
                        <tr>
                            <th>Thể loại:</th>
                            <td id="modalCategory"></td>
                        </tr>
                        <tr>
                            <th>Vùng miền:</th>
                            <td id="modalRegion"></td>
                        </tr>
                        <tr>
                            <th>Cập nhật lúc:</th>
                            <td id="modalUpdated"></td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td id="modalStatus"></td>
                        </tr>
                        <tr>
                            <th>Hành động:</th>
                            <td>
                                <div class="d-flex align-items-center justify-content-center gap-2 action-icons">

                                    <!-- Link client.product.detail -->
                                    <a href="#" id="modalClientView" class="text-primary" title="Xem (Client)" target="_blank" rel="noopener">
                                        <i class="ri-links-line"></i>
                                    </a>

                                    <!-- Link admin.products.show -->
                                    <a href="#" id="modalAdminView" class="text-secondary" title="Xem (Admin)">
                                        <i class="ri-eye-line"></i>
                                    </a>

                                    <!-- Link admin.products.edit -->
                                    <a href="#" id="modalEdit" class="text-primary" title="Sửa">
                                        <i class="ri-pencil-line"></i>
                                    </a>

                                    <!-- Nút xóa modal -->
                                    <a href="javascript:void(0)" id="modalDelete" class="delete-btn text-danger" title="Xóa"
                                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="ri-delete-bin-line"></i>
                                    </a>
                                </div>
                            </td>

                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('backend.footer')
<script src="{{ asset('backend/js/product.js') }}"></script>
<style>
    .table td,
    .table th {
        max-width: 150px;
        /* Giới hạn chiều rộng từng cột, tùy chỉnh theo cột */
        white-space: nowrap;
        /* Không xuống dòng */
        overflow: hidden;
        /* Ẩn phần tràn */
        text-overflow: ellipsis;
        /* Hiển thị dấu ... */
        vertical-align: middle;
        /* Căn giữa dọc */
    }

    #modalCategory {
        white-space: normal;
        /* cho phép xuống dòng tự nhiên */
        word-break: break-word;
        /* ngắt từ khi quá dài */
        max-width: 300px;
        /* giới hạn chiều rộng (tuỳ chỉnh theo modal) */
    }

    table tbody tr:nth-child(odd) {
        background-color: #ffffff;
        /* màu trắng */
    }

    table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
        /* xám nhạt, bạn có thể đổi màu theo ý thích */
    }

    .modal-content {
        border: none !important;
    }

    .modal-body table th {
        width: 120px;
        text-align: right;
        vertical-align: top;
        padding-right: 10px;
        white-space: nowrap;
    }

    .modal-body table td {
        text-align: left;
        vertical-align: top;
    }

    /* Nếu cần icon căn lề đẹp hơn */
    .modal-body a {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    /* Container dropdown filter */
    .filter-dropdown {
        position: relative;
    }

    /* Toggle button */
    .filter-dropdown-toggle {
        min-width: 130px;
        cursor: pointer;
        font-weight: 600;
        user-select: none;
        transition: color 0.2s ease;
        padding: 0.3rem 0.6rem;
    }

    .filter-dropdown-toggle.btn {
        display: inline-flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Arrow icon */
    .filter-dropdown-toggle .arrow {
        border: solid #0d6efd;
        border-width: 0 2px 2px 0;
        display: inline-block;
        padding: 3px;
        transform: rotate(45deg);
        transition: transform 0.2s ease;
        margin-left: 0.5rem;
    }

    /* Arrow rotated when open */
    .filter-dropdown.open .filter-dropdown-toggle .arrow {
        transform: rotate(-135deg);
    }

    /* Dropdown menu */
    .filter-dropdown-menu {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        width: 250px;
        max-height: 350px;
        overflow: hidden;
        z-index: 1050;
        border: 1px solid #ddd;
        background: #fff;
        border-radius: 0.25rem;
        box-shadow: 0 2px 8px rgb(0 0 0 / 0.15);
        padding: 0.75rem 1rem;
        display: none;
    }

    /* Selected items container */
    .filter-selected-items {
        min-height: 30px;
        font-weight: 600;
        color: #212529;
        user-select: none;
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: nowrap;
    }

    /* Selected item styling */
    .filter-selected-items .selected-item {
        background-color: #0d6efd;
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        cursor: default;
        user-select: none;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Remove button inside selected item */
    .filter-selected-items .selected-item .remove-btn {
        cursor: pointer;
        font-weight: bold;
        font-size: 1.1em;
        line-height: 1;
        user-select: none;
    }

    /* Search input */
    .filter-search-input {
        width: 100%;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border: 1px solid #ccc;
        border-radius: 0.25rem;
        margin-bottom: 0.5rem;
    }

    /* Options list */
    .filter-options-list {
        max-height: 150px;
        overflow-y: auto;
    }

    /* Option label */
    .filter-options-list label {
        display: block;
        padding: 0.25rem 0.5rem;
        cursor: pointer;
        user-select: none;
        border-radius: 0.25rem;
        transition: background-color 0.2s ease;
    }

    /* Hover and selected option styling */
    .filter-options-list label:hover,
    .filter-options-list label.selected {
        background-color: #e9f1ff;
        color: #0d6efd;
        font-weight: 600;
    }

    /* Hide default radio inputs */
    .filter-options-list input[type="radio"] {
        display: none;
    }

    /* Remove Filters link */
    #removeFilters {
        cursor: pointer;
        font-weight: 600;
        user-select: none;
    }

    #removeFilters i {
        margin-right: 4px;
    }

    .filter-dropdown-toggle.active-filter {
        background: #e7f5ea !important;
        /* Xanh nhạt */
        color: #188041 !important;
        /* Xanh chủ đạo */
        font-weight: 700;
        border-color: #3ba55d;
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

    .action-icons a i {
        font-size: 20px;
        transition: color 0.2s;
    }

    .action-icons a.text-primary:hover {
        color: #006f3c !important;
    }

    .action-icons a.text-danger:hover {
        color: #d7263d !important;
    }

    .action-icons a.text-secondary:hover {
        color: #3ba55d !important;
    }


    body.dark .filter-dropdown-menu {
        background-color: #2c2c2c !important;
        /* Đảm bảo nền tối hơn */
        color: #007bff !important;
        /* Màu chữ xanh dương sáng hơn */
    }

    body.dark .filter-dropdown-menu .filter-search-input {
        background-color: #3a3a3a !important;
        color: #007bff !important;
        border-color: #555 !important;
    }

    body.dark .filter-dropdown-menu .filter-search-input::placeholder {
        color: #888 !important;
        /* Thay đổi màu placeholder cho phù hợp */
    }

    body.dark .filter-dropdown-menu label {
        color: #007bff !important;
        /* Đảm bảo màu chữ hiển thị rõ */
    }

    body.dark .filter-dropdown-menu label span {
        color: #007bff !important;
        /* Đảm bảo màu chữ hiển thị rõ */
    }

    body.dark .filter-dropdown-menu .filter-selected-items {
        color: #007bff !important;
    }

    body.dark .filter-dropdown-toggle {
        color: #007bff !important;
        /* Đảm bảo nút filter hiển thị rõ chữ */
        border-color: #555 !important;
        background-color: #3a3a3a !important;
    }

    body.dark .filter-dropdown-toggle:hover {
        background-color: #4a4a4a !important;
    }
</style>

<script>
    $(document).ready(function() {
        // Hàm xóa dấu tiếng Việt
        function removeVietnameseTones(str) {
            if (!str) return '';
            return str.normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/đ/g, 'd').replace(/Đ/g, 'D');
        }

        // Ghi đè search DataTable (không dấu)
        // $.fn.dataTable.ext.type.search.string = function(data) {
        //     return !data ? '' : removeVietnameseTones(data.toString().toLowerCase());
        // };
        // $.fn.dataTable.ext.type.search.html = function(data) {
        //     return !data ? '' : removeVietnameseTones($('<div>').html(data).text().toLowerCase());
        // };


        // --- Khởi tạo Select2 ---
        $('.select2').select2({
            width: '100%',
            placeholder: 'Chọn...',
            allowClear: true,
            dropdownParent: $('.dropdown-menu'),
            minimumResultsForSearch: Infinity
        });

        function updateFilterActiveClass() {
            $('.filter-dropdown').each(function() {
                var $toggle = $(this).find('.filter-dropdown-toggle');
                if ($(this).find('input[type=radio]:checked').length > 0) {
                    $toggle.addClass('active-filter');
                } else {
                    $toggle.removeClass('active-filter');
                }
            });
        }
        var openDropdownId = null;

        // Bắt sự kiện click dropdown toggle của select2
        $('.nav-item.dropdown > .nav-link.dropdown-toggle').on('click', function(e) {
            e.preventDefault();
            var $dropdown = $(this).parent();
            var id = $dropdown.find('select.select2').attr('id');

            if (openDropdownId && openDropdownId !== id) {
                $('#' + openDropdownId).select2('close');
                $('[aria-labelledby="' + openDropdownId + '"]').parent('.dropdown-menu').removeClass('show');
                $('[aria-labelledby="' + openDropdownId + '"]').removeClass('show');
                openDropdownId = null;
            }

            if (openDropdownId === id) {
                $('#' + id).select2('close');
                openDropdownId = null;
                $dropdown.find('.dropdown-menu').removeClass('show');
            } else {
                openDropdownId = id;
                $dropdown.find('.dropdown-menu').addClass('show');
                $('#' + id).select2('open');
            }
        });

        // Khi chọn hoặc bỏ chọn option trong select2 -> tự đóng dropdown và apply filter
        $('.select2').on('select2:select select2:unselect', function() {
            var id = $(this).attr('id');
            openDropdownId = null;
            $(this).select2('close');
            $(this).closest('.dropdown-menu').removeClass('show');
            applyFilter();
        });

        // --- XỬ LÝ CUSTOM DROPDOWN FILTER ---
        $('.filter-dropdown-toggle').off('click').on('click', function(e) {
            e.preventDefault();
            var $dropdown = $(this).closest('.filter-dropdown');
            var isOpen = $dropdown.hasClass('open');
            $('.filter-dropdown').not($dropdown).removeClass('open').find('.filter-dropdown-menu').hide();
            if (isOpen) {
                $dropdown.removeClass('open');
                $dropdown.find('.filter-dropdown-menu').hide();
            } else {
                $dropdown.addClass('open');
                $dropdown.find('.filter-dropdown-menu').show();
                $dropdown.find('.filter-search-input').val('').trigger('input').focus();
            }
        });

        // Lọc option trong dropdown custom khi nhập search (KHÔNG lọc cho Trạng thái)
        $('.filter-dropdown').on('input', '.filter-search-input', function() {
            if ($(this).closest('#filterStatusDropdown').length) return;

            var val = removeVietnameseTones($(this).val().toLowerCase());
            var $dropdown = $(this).closest('.filter-dropdown');
            $dropdown.find('.filter-options-list label').each(function() {
                var labelText = removeVietnameseTones($(this).text().toLowerCase());
                $(this).toggle(labelText.indexOf(val) > -1);
            });
        });

        // Chọn radio trong dropdown custom
        $('.filter-options-list input[type=radio]').on('change', function() {
            var $dropdown = $(this).closest('.filter-dropdown');
            var selectedText = $(this).next('span').text();
            var $selectedContainer = $dropdown.find('.filter-selected-items');
            $selectedContainer.html('<div class="selected-item">' + selectedText + '<span class="remove-btn" title="Bỏ chọn" style="cursor:pointer;">×</span></div>');
            $dropdown.removeClass('open').find('.filter-dropdown-menu').hide();

            toggleRemoveFilters();
            applyFilter();
            updateFilterActiveClass();
        });

        // Xử lý click nút xóa trong selected item
        $('.filter-selected-items').on('click', '.remove-btn', function(e) {
            e.stopPropagation();
            var $dropdown = $(this).closest('.filter-dropdown');
            var $radio = $dropdown.find('input[type=radio]:checked');
            $radio.prop('checked', false);
            $dropdown.find('.filter-selected-items').text('Chưa chọn').css('color', '#6c757d');
            toggleRemoveFilters();
            applyFilter();
            updateFilterActiveClass();
        });

        // Đóng dropdown khi click ra ngoài (cho cả custom dropdown và select2)
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.filter-dropdown, .nav-item.dropdown').length) {
                $('.filter-dropdown').removeClass('open').find('.filter-dropdown-menu').hide();
                if (openDropdownId) {
                    $('#' + openDropdownId).select2('close');
                    openDropdownId = null;
                }
                $('.dropdown-menu.show').removeClass('show');
            }
        });

        // Ẩn/hiện nút Remove filters
        function toggleRemoveFilters() {
            var hasFilter = false;
            $('.filter-dropdown').each(function() {
                if ($(this).find('input[type=radio]:checked').length) {
                    hasFilter = true;
                    return false;
                }
            });
            $('.select2').each(function() {
                if ($(this).val() && $(this).val().length > 0) {
                    hasFilter = true;
                    return false;
                }
            });

            if (hasFilter) {
                $('#removeFilters').removeClass('d-none');
            } else {
                $('#removeFilters').addClass('d-none');
            }
        }

        // Nút Remove filters click
        $('#removeFilters').on('click', function(e) {
            e.preventDefault();

            $('.filter-dropdown input[type=radio]').prop('checked', false);
            $('.filter-selected-items').text('Chưa chọn').css('color', '#6c757d');
            $('.select2').val(null).trigger('change');

            toggleRemoveFilters();
            applyFilter();
            updateFilterActiveClass();
        });

        // --- Khởi tạo DataTable ---
        var table = $('#productTable').DataTable({
            paging: true,
            ordering: true,
            info: true,
            lengthChange: true,
            pageLength: 10,
            autoWidth: true,
            responsive: true,
            pagingType: "full_numbers",
            language: {
                "sProcessing": "Đang xử lý...",
                "sLengthMenu": "Hiển thị _MENU_ mục",
                "sZeroRecords": "Không tìm thấy dòng nào phù hợp",
                "sInfo": "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ mục",
                "sInfoEmpty": "Hiển thị 0 đến 0 của 0 mục",
                "sInfoFiltered": "(lọc từ _MAX_ mục)",
                "sSearch": "Tìm kiếm:",
                "oPaginate": {
                    "sFirst": "Đầu",
                    "sPrevious": "<",
                    "sNext": ">",
                    "sLast": "Cuối"
                }
            },
            columnDefs: [{
                orderable: false,
                targets: [0, 2, 6]
            }]
        });

        // --- KHÔNG cần đoạn này nữa vì đã ghi đè search type ở trên! ---
        // $('#productTable_filter input[type="search"]').on('input', function() {
        //     table.search(removeVietnameseTones(this.value)).draw();
        // });

        // Hàm apply filter lọc DataTable
        function applyFilter() {
            table.column(3).search('');
            table.column(4).search('');
            table.column(6).search('');

            var categoryText = $('#filterCategoryDropdown input[type=radio]:checked').next('span').text().trim();
            if (categoryText) {
                table.column(3).search(categoryText, true, false);
            }
            var regionText = $('#filterRegionDropdown input[type=radio]:checked').next('span').text().trim();
            if (regionText) {
                table.column(4).search(regionText, true, false);
            }
            var statusText = $('#filterStatusDropdown input[type=radio]:checked').next('span').text().trim();
            if (statusText) {
                table.column(6).search(statusText, true, false);
            }

            table.draw();
            toggleRemoveFilters();
        }
        // --- Checkbox chọn tất cả và từng dòng ---
        function toggleBulkDeleteButton() {
            if ($('.row-checkbox:checked').length > 0) {
                $('#delete-selected-variants').prop('disabled', false);
            } else {
                $('#delete-selected-variants').prop('disabled', true);
            }
        }
        $('#select-all-checkbox').on('click', function() {
            var checked = $(this).prop('checked');
            $('.row-checkbox').prop('checked', checked);
            toggleBulkDeleteButton();
        });
        $('.row-checkbox').on('change', function() {
            toggleBulkDeleteButton();
            if ($('.row-checkbox:checked').length !== $('.row-checkbox').length) {
                $('#select-all-checkbox').prop('checked', false);
            } else {
                $('#select-all-checkbox').prop('checked', true);
            }
        });



        // Xử lý xóa hàng loạt
        // --- Checkbox chọn tất cả và từng dòng ---
        function toggleBulkDeleteButton() {
            if ($('.row-checkbox:checked').length > 0) {
                $('#delete-selected-variants').prop('disabled', false);
            } else {
                $('#delete-selected-variants').prop('disabled', true);
            }
        }

        $('#select-all-checkbox').on('click', function() {
            var checked = $(this).prop('checked');
            $('.row-checkbox').prop('checked', checked);
            toggleBulkDeleteButton();
        });

        $('.row-checkbox').on('change', function() {
            toggleBulkDeleteButton();
            if ($('.row-checkbox:checked').length !== $('.row-checkbox').length) {
                $('#select-all-checkbox').prop('checked', false);
            } else {
                $('#select-all-checkbox').prop('checked', true);
            }
        });

        // Xử lý xóa hàng loạt (Bulk Delete)
        $('#delete-selected-variants').on('click', function(e) {
            var selectedIds = [];
            $('.row-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                // Không có gì được chọn thì chặn mở modal, show modal báo lỗi
                e.preventDefault();
                $('#errorMessageContent').text('Vui lòng chọn ít nhất một sản phẩm để xóa.');
                $('#errorMessageModal').modal('show');
                return false;
            } else {
                // Có chọn, show modal xác nhận xóa hàng loạt
                $('#selectedProductCount').text(selectedIds.length);
                $('#bulk-delete-ids').val(selectedIds.join(',')); // nếu cần submit id sang backend
                // Modal sẽ tự hiện vì đã có data-bs-toggle/data-bs-target trên button
            }
        });

        // Xác nhận xóa hàng loạt trong modal
        $('#confirm-bulk-delete-btn').off('click').on('click', function() {
            var selectedIds = $('#bulk-delete-ids').val();
            $.ajax({
                url: '', // API xóa hàng loạt của bạn
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids: selectedIds
                },
                success: function(response) {
                    $('#bulkDeleteModal').modal('hide');
                    if (response.status === 'success') {
                        $('#successMessageContent').text(response.message || 'Xóa sản phẩm đã chọn thành công!');
                        $('#successMessageModal').modal('show');
                    } else {
                        $('#errorMessageContent').text(response.message || 'Lỗi khi xóa sản phẩm đã chọn.');
                        $('#errorMessageModal').modal('show');
                    }
                    $('#successMessageModal, #errorMessageModal').on('hidden.bs.modal', function() {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    $('#bulkDeleteModal').modal('hide');
                    let errorMessage = 'Lỗi khi xóa sản phẩm đã chọn';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    $('#errorMessageContent').text(errorMessage);
                    $('#errorMessageModal').modal('show');
                    $('#errorMessageModal').on('hidden.bs.modal', function() {
                        window.location.reload();
                    });
                }
            });
        });

        // Xử lý xóa từng sản phẩm
        $(document).on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');

            if (id) {
                var formAction = '{{ url("admin/products") }}' + '/' + id;
                $('#deleteForm').attr('action', formAction);
            } else {
                $('#errorMessageContent').text('Không thể xóa sản phẩm này do thiếu thông tin ID.');
                $('#errorMessageModal').modal('show');
            }
        });

        // Xử lý đổi trạng thái sản phẩm
        $(document).on('click', '.status-badge', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var status = $(this).data('status');
            var nextStatus = status == 1 ? 'Ngừng bán' : 'Đang bán';

            $('#modal-status-text').html('Bạn muốn chuyển trạng thái sản phẩm <b>' + name + '</b> sang <span class="text-primary">' + nextStatus + '</span>?');
            $('#status-toggle-form').attr('action', '{{ url("admin/products") }}/' + id + '/toggle');
            $('#statusModal').modal('show');
        });

        // Hiển thị modal lỗi nếu có session error
        @if(session('error'))
        $('#errorMessageContent').text("{{ session('error') }}");
        $('#errorMessageModal').modal('show');
        @endif

    });
    // modal thong tin nhanh
    $(document).ready(function() {
        // Khi click vào tên sản phẩm mở modal thông tin
        $(document).on('click', '.product-name', function() {
            var el = $(this);

            // Cập nhật thông tin modal
            $('#modalNameDesc').html(el.data('name') + '<br>' + el.data('desc'));
            $('#modalImage').attr('src', el.data('image')).attr('alt', el.data('name'));
            $('#modalCategory').text(el.data('category'));
            $('#modalRegion').text(el.data('region'));
            $('#modalUpdated').text(el.data('updated'));

            var statusText = el.data('status');
            var statusClass = (statusText === 'Đang bán') ? 'status-close' : 'status-danger';
            $('#modalStatus').text(statusText).removeClass('status-close status-danger').addClass(statusClass);

            var productId = el.data('id');
            var productSlug = el.data('slug');

            // Cập nhật link hành động trong modal
            $('#modalClientView').attr('href', '/client/san-pham/' + productSlug); // Client detail
            $('#modalAdminView').attr('href', '/admin/products/' + productSlug); // Admin xem chi tiết
            $('#modalPreview').attr('href', '/admin/products/' + productSlug + '/preview'); // Admin xem trước
            $('#modalEdit').attr('href', '/admin/products/' + productSlug + '/edit'); // Admin sửa

            // Cập nhật nút xóa
            $('#modalDelete').attr('data-id', productId);
            $('#modalDelete').attr('data-name', el.data('name'));

            // Mở modal thông tin
            $('#productInfoModal').modal('show');
        });

        // Khi click nút xóa trong modal thông tin mở modal xác nhận xóa
        $('#modalDelete').off('click').on('click', function() {
            var productId = $(this).attr('data-id');
            var deleteUrl = '/admin/products/' + productId;

            // Set action cho form delete
            $('#deleteForm').attr('action', deleteUrl);

            // Đóng modal thông tin
            $('#productInfoModal').modal('hide');

            // Mở modal xác nhận xóa
            $('#deleteModal').modal('show');
        });
    });
</script>
@endsection

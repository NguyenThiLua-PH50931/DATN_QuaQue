@extends('layouts.backend')

@section('title', 'Vùng miền đã xóa')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="title-header option-title">
                                <h5>Vùng miền đã xóa</h5>
                                <form class="d-inline-flex">
                                    <a href="{{ route('admin.regions.index') }}"
                                        class="align-items-center btn btn-theme d-flex">
                                        <i data-feather="list"></i> Quay lại danh sách
                                    </a>
                                </form>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="table-responsive region-table">
                                <table class="table all-package theme-table" id="trashed_regions_table">
                                    <thead>
                                        <tr>
                                            <th style="color: black; background-color: #f8f9fa;">Tên vùng miền</th>
                                            <th style="color: black; background-color: #f8f9fa;">Slug</th>
                                            <th style="color: black; background-color: #f8f9fa;">Ngày xóa</th>
                                            <th style="color: black; background-color: #f8f9fa;">Tùy chọn</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($regions as $region)
                                            <tr>
                                                <td>{{ $region->name }}</td>
                                                <td>{{ $region->slug }}</td>
                                                <td>{{ $region->deleted_at->format('d-m-Y') }}</td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <form
                                                                action="{{ route('admin.regions.restore', $region->id) }}"
                                                                method="POST" style="display:inline;">
                                                                @csrf
                                                                <button type="submit" class="btn btn-success btn-sm"
                                                                    onclick="return confirm('Bạn có chắc chắn muốn khôi phục?')">
                                                                    <i class="ri-arrow-go-back-line"></i> Khôi phục
                                                                </button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form
                                                                action="{{ route('admin.regions.forceDelete', $region->id) }}"
                                                                method="POST" style="display:inline;" class="force-delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger btn-sm"
                                                                    onclick="return confirm('Bạn có chắc chắn xoá vĩnh viễn?')"><i data-feather="trash-2"></i>
                                                                    Xoá vĩnh viễn</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add any necessary modals here, e.g., confirmation modals -->

    </div>
    @includeIf('backend.footer')
@endsection

@push('scripts')
    {{-- Include Toastr CSS and JS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable and store the instance
            const table = $('#trashed_regions_table').DataTable({
                language: {
                    search: "Tìm kiếm:",
                    lengthMenu: "Hiển thị _MENU_ vùng miền đã xóa",
                    info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ vùng miền đã xóa",
                    paginate: {
                        first: "Đầu",
                        last: "Cuối",
                        next: "Sau",
                        previous: "Trước"
                    },
                    zeroRecords: "Không tìm thấy vùng miền đã xóa nào.",
                }
            });

            // Handle force delete using AJAX
            $(document).on('submit', '.force-delete-form', function(e) {
                e.preventDefault(); // Prevent default form submission
                const form = $(this);
                const url = form.attr('action');
                // Get the DataTables row object associated with this form's parent row
                const row = table.row(form.closest('tr'));

                if (confirm('Bạn có chắc chắn muốn xóa vĩnh viễn vùng miền này?')) {
                    $.ajax({
                        url: url,
                        method: 'DELETE', // Use DELETE method for force deletion
                        data: form.serialize(),
                        success: function(response) {
                            // Remove the row using DataTables API
                            console.log('Force delete success via AJAX!', response);
                            if (row && row.remove) {
                                row.remove().draw(); // Remove row and redraw the table
                            } else {
                                console.error('Could not get valid DataTables row object. Removing row directly.');
                                form.closest('tr').remove();
                            }
                            toastr.success(response.message || 'Xóa vĩnh viễn vùng miền thành công!');
                        },
                        error: function(xhr) {
                            console.error('Force delete AJAX failed!', xhr);
                            let errorMessage = 'Lỗi khi xóa vĩnh viễn vùng miền';
                             if (xhr.responseJSON && xhr.responseJSON.message) {
                                 errorMessage = xhr.responseJSON.message;
                             } else if (xhr.responseText) {
                                errorMessage = 'Lỗi server: ' + xhr.responseText.substring(0, 100) + '...';
                            } else {
                                errorMessage = 'Lỗi không xác định';
                            }
                            toastr.error(errorMessage);
                        }
                    });
                }
            });

            // Handle restore (if needed to be AJAX, otherwise current form submission is fine)
            // If you want restore to be AJAX, you'd add a similar script for a restore form.

        });
    </script>
@endpush 
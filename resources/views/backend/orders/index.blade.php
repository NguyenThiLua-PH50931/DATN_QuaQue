@extends('layouts.backend')

@section('title', 'Đơn hàng')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <!-- Table Start -->
                    <div class="card-body">
                        <div class="title-header option-title">
                            <h5>Menu List</h5>
                        </div>
                        <div>
                            <div class="table-responsive">
                                <table class="user-table menu-list-table table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <span class="form-check user-checkbox">
                                                    <input class="checkbox_animated checkall"
                                                        type="checkbox" value="">
                                                </span>
                                            </th>
                                            <th style="color: black; background-color: #f8f9fa;">Name</th>
                                            <th style="color: black; background-color: #f8f9fa;">Status</th>
                                            <th style="color: black; background-color: #f8f9fa;">Created On</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="form-check user-checkbox">
                                                    <input class="checkbox_animated check-it"
                                                        type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td>Product Menu</td>
                                            <td class="menu-status">
                                                <span class="warning">Restitute</span>
                                            </td>
                                            <td>2018-04-18T00:00:00</td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="form-check user-checkbox">
                                                    <input class="checkbox_animated check-it"
                                                        type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td>Category Menu</td>
                                            <td class="menu-status">
                                                <span class="success">Success</span>
                                            </td>
                                            <td>2018-04-18T00:00:00</td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="form-check user-checkbox">
                                                    <input class="checkbox_animated check-it"
                                                        type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td>Subcategory Menu</td>
                                            <td class="menu-status">
                                                <span class="success">Success</span>
                                            </td>
                                            <td>2018-04-18T00:00:00</td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="form-check user-checkbox">
                                                    <input class="checkbox_animated check-it"
                                                        type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td>Sales Menu</td>
                                            <td class="menu-status">
                                                <span class="warning">Restitute</span>
                                            </td>
                                            <td>2018-04-18T00:00:00</td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="form-check user-checkbox">
                                                    <input class="checkbox_animated check-it"
                                                        type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td>Vendor Menu</td>
                                            <td class="menu-status">
                                                <span class="success">Success</span>
                                            </td>
                                            <td>2018-04-18T00:00:00</td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="form-check user-checkbox">
                                                    <input class="checkbox_animated check-it"
                                                        type="checkbox" value="">
                                                </div>
                                            </td>
                                            <td>Category Menu</td>
                                            <td class="menu-status">
                                                <span class="success">Success</span>
                                            </td>
                                            <td>2018-04-18T00:00:00</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
    @includeIf('backend.footer')
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        $('#table_id').DataTable({
            language: {
                search: "Tìm kiếm:",
                lengthMenu: "Hiển thị _MENU_ đơn hàng",
                info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ đơn hàng",
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    next: "Sau",
                    previous: "Trước"
                },
                zeroRecords: "Không tìm thấy đơn hàng nào.",
            }
        });
    });
</script>
@endpush
@endsection


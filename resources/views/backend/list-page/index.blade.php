@extends('layouts.backend')

@section('title', 'Danh sách trang')

@section('content')
    <!-- List Page Table Start -->
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="title-header option-title">
                                <h5>List Page</h5>
                            </div>
                            <div>
                                <div class="table-responsive">
                                    <table class="table list-table theme-table" id="table_id">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="check-box-contain">
                                                        <span class="form-check user-checkbox">
                                                            <input class="checkbox_animated checkall" type="checkbox"
                                                                value="">
                                                        </span>
                                                        <span style="color: black; background-color: #f8f9fa;">Title</span>
                                                    </div>
                                                </th>
                                                <th style="color: black; background-color: #f8f9fa;">Author</th>
                                                <th style="color: black; background-color: #f8f9fa;">Date</th>
                                                <th style="color: black; background-color: #f8f9fa;">Options</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="check-box-contain">
                                                        <span class="form-check user-checkbox">
                                                            <input class="checkbox_animated check-it" type="checkbox"
                                                                value="">
                                                        </span>
                                                        <span class="theme-color">Blog</span>
                                                    </div>
                                                </td>
                                                <td>Root</td>
                                                <td>
                                                    <a href="javascript:void(0)">
                                                        <span class="d-block theme-color">Published</span>
                                                        <span>23-10-2020 at 6:12 AM</span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <i class="ri-pencil-line"></i>
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalToggle">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="check-box-contain">
                                                        <span class="form-check user-checkbox">
                                                            <input class="checkbox_animated check-it" type="checkbox"
                                                                value="" value="">
                                                        </span>
                                                        <span class="theme-color">Cart</span> - Cart Page
                                                    </div>
                                                </td>

                                                <td>Root</td>

                                                <td>
                                                    <a href="javascript:void(0)">
                                                        <span class="d-block theme-color">Published</span>
                                                        <span>23-10-2020 at 6:12 AM</span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <i class="ri-pencil-line"></i>
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalToggle">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="check-box-contain">
                                                        <span class="form-check user-checkbox">
                                                            <input class="checkbox_animated check-it" type="checkbox"
                                                                value="">
                                                        </span>
                                                        <span class="theme-color">Checkout</span> - Checkout
                                                        Page
                                                    </div>
                                                </td>

                                                <td>Root</td>

                                                <td>
                                                    <a href="javascript:void(0)">
                                                        <span class="d-block theme-color">Published</span>
                                                        <span>23-10-2020 at 6:12 AM</span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <i class="ri-pencil-line"></i>
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalToggle">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="check-box-contain">
                                                        <span class="form-check user-checkbox">
                                                            <input class="checkbox_animated check-it" type="checkbox"
                                                                value="">
                                                        </span>
                                                        <span class="theme-color">Home</span> - Elementor
                                                    </div>
                                                </td>

                                                <td>Root</td>

                                                <td>
                                                    <a href="javascript:void(0)">
                                                        <span class="d-block theme-color">Published</span>
                                                        <span>23-10-2020 at 6:12 AM</span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <i class="ri-pencil-line"></i>
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalToggle">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="check-box-contain">
                                                        <span class="form-check user-checkbox">
                                                            <input class="checkbox_animated check-it" type="checkbox"
                                                                value="">
                                                        </span>
                                                        <span class="theme-color">Home2</span> <span>-
                                                            Elementor</span>
                                                    </div>
                                                </td>

                                                <td>Root</td>

                                                <td>
                                                    <a href="javascript:void(0)">
                                                        <span class="d-block theme-color">Published</span>
                                                        <span>23-10-2020 at 6:12 AM</span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <i class="ri-pencil-line"></i>
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalToggle">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="check-box-contain">
                                                        <span class="form-check user-checkbox">
                                                            <input class="checkbox_animated check-it" type="checkbox"
                                                                value="">
                                                        </span>
                                                        <span class="theme-color">Home3</span> - Elementor
                                                    </div>
                                                </td>

                                                <td>Root</td>

                                                <td>
                                                    <a href="javascript:void(0)">
                                                        <span class="d-block theme-color">Published</span>
                                                        <span>23-10-2020 at 6:12 AM</span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <i class="ri-pencil-line"></i>
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalToggle">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="check-box-contain">
                                                        <span class="form-check user-checkbox">
                                                            <input class="checkbox_animated check-it" type="checkbox"
                                                                value="">
                                                        </span>
                                                        <span class="theme-color">my Account</span> - My
                                                        Account Page
                                                    </div>
                                                </td>

                                                <td>Root</td>

                                                <td>
                                                    <a href="javascript:void(0)">
                                                        <span class="d-block theme-color">Published</span>
                                                        <span>23-10-2020 at 6:12 AM</span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <i class="ri-pencil-line"></i>
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalToggle">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="check-box-contain">
                                                        <span class="form-check user-checkbox">
                                                            <input class="checkbox_animated check-it" type="checkbox"
                                                                value="">
                                                        </span>
                                                        <span class="theme-color">Privacy Policy</span> - Draft,
                                                        Privacy Policy Page
                                                    </div>
                                                </td>

                                                <td>Root</td>

                                                <td>
                                                    <a href="javascript:void(0)">
                                                        <span class="d-block theme-color">Published</span>
                                                        <span>23-10-2020 at 6:12 AM</span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <i class="ri-pencil-line"></i>
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalToggle">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="check-box-contain">
                                                        <span class="form-check user-checkbox">
                                                            <input class="checkbox_animated check-it" type="checkbox"
                                                                value="">
                                                        </span>
                                                        <span class="theme-color">Refund and Return
                                                            Policy</span>
                                                        - Draft
                                                    </div>
                                                </td>

                                                <td>Root</td>

                                                <td>
                                                    <a href="javascript:void(0)">
                                                        <span class="d-block theme-color">Published</span>
                                                        <span>23-10-2020 at 6:12 AM</span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <i class="ri-pencil-line"></i>
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalToggle">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <div class="check-box-contain">
                                                        <span class="form-check user-checkbox">
                                                            <input class="checkbox_animated check-it" type="checkbox"
                                                                value="">
                                                        </span>
                                                        <span class="theme-color">Simple Page</span>
                                                    </div>
                                                </td>

                                                <td>Root</td>

                                                <td>
                                                    <a href="javascript:void(0)">
                                                        <span class="d-block theme-color">Published</span>
                                                        <span>23-10-2020 at 6:12 AM</span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="javascript:void(0)">
                                                                <i class="ri-pencil-line"></i>
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalToggle">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
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
<<<<<<< HEAD {{ url('/admin//create') }} @push('scripts') <script>
    $(document).ready(function() {
        $('#table_id').DataTable({
            language: {
                search: "Tìm kiếm:",
                lengthMenu: "Hiển thị _MENU_ trang",
                info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ trang",
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    next: "Sau",
                    previous: "Trước"
                },
                zeroRecords: "Không tìm thấy trang nào.",
            }
        });
    });
</script>
@endpush

{{ url('/admin//create') }}

@extends('layouts.backend')

@section('title', '')

@section('content')
<!-- Ticket Section Start -->
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <!-- Table Start -->
                    <div class="card-body">
                        <div class="title-header option-title">
                            <h5>Support Ticket</h5>
                        </div>
                        <div>
                            <div class="table-responsive">
                                <table class="table ticket-table all-package theme-table" id="table_id">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="check-box-contain">
                                                    <span class="form-check user-checkbox checkall">
                                                        <input class="checkbox_animated" type="checkbox"
                                                            value="">
                                                    </span>
                                                    <span>Ticket Number</span>
                                                </div>
                                            </th>
                                            <th>
                                                <span>Date</span>
                                            </th>
                                            <th>
                                                <span>Subject</span>
                                            </th>
                                            <th>
                                                <span>Status</span>
                                            </th>
                                            <th>
                                                <span>Options</span>
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="check-box-contain">
                                                    <span class="form-check user-checkbox">
                                                        <input class="checkbox_animated check-it"
                                                            type="checkbox" value="">
                                                    </span>
                                                    <span>#453</span>
                                                </div>
                                            </td>
                                            <td>25-09-2021</td>

                                            <td>Query about return & exchange</td>

                                            <td class="status-danger">
                                                <span>Pending</span>
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
                                                        <input class="checkbox_animated check-it"
                                                            type="checkbox" value="">
                                                    </span>
                                                    <span>#453</span>
                                                </div>
                                            </td>

                                            <td>20-10-2021</td>
                                            <td>Query about return & exchange</td>
                                            <td class="status-close">
                                                <span>Closed</span>
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
                                                        <input class="checkbox_animated check-it"
                                                            type="checkbox" value="">
                                                    </span>
                                                    <span>#456</span>
                                                </div>
                                            </td>

                                            <td>30-01-2021</td>
                                            <td>Query about return & exchange</td>
                                            <td class="status-danger">
                                                <span>Pending</span>
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
                                                        <input class="checkbox_animated check-it"
                                                            type="checkbox" value="">
                                                    </span>
                                                    <span>#456</span>
                                                </div>
                                            </td>

                                            <td>30-01-2021</td>
                                            <td>Query about return & exchange</td>
                                            <td class="status-danger">
                                                <span>Pending</span>
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
                                                        <input class="checkbox_animated check-it"
                                                            type="checkbox" value="">
                                                    </span>
                                                    <span>#782</span>
                                                </div>
                                            </td>

                                            <td>02-04-2021</td>
                                            <td>Query about return & exchange</td>
                                            <td class="status-close">
                                                <span>Closed</span>
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
                                                        <input class="checkbox_animated check-it"
                                                            type="checkbox" value="">
                                                    </span>
                                                    <span>#214</span>
                                                </div>
                                            </td>

                                            <td>10-01-2021</td>
                                            <td>Query about return & exchange</td>
                                            <td class="status-close">
                                                <span>Closed</span>
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
                                                        <input class="checkbox_animated check-it"
                                                            type="checkbox" value="">
                                                    </span>
                                                    <span>#258</span>
                                                </div>
                                            </td>

                                            <td>26-07-2021</td>
                                            <td>Query about return & exchange</td>
                                            <td class="status-danger">
                                                <span>Pending</span>
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
                                                        <input class="checkbox_animated check-it"
                                                            type="checkbox" value="">
                                                    </span>
                                                    <span>#634</span>
                                                </div>
                                            </td>

                                            <td>30-10-2020</td>
                                            <td>Query about return & exchange</td>
                                            <td class="status-close">
                                                <span>Closed</span>
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
                                                        <input class="checkbox_animated check-it"
                                                            type="checkbox" value="">
                                                    </span>
                                                    <span>#124</span>
                                                </div>
                                            </td>

                                            <td>09-08-2021</td>
                                            <td>Query about return & exchange</td>
                                            <td class="status-danger">
                                                <span>Pending</span>
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
                    <!-- Table End -->
                </div>
            </div>
        </div>
    </div>

    @includeIf('backend.footer')
</div>
<!-- Ticket Section End -->
</div>
<!-- Page Body End-->
@endsection
{{ url('/admin//create') }}
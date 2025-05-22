@extends('layouts.backend')

@section('title', 'Quyền')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <!-- Table Start -->
                    <div class="card-body">
                        <div class="title-header option-title">
                            <h5>Role List</h5>
                            <form class="d-inline-flex">
                                <a href="{{ url('/admin/roles/create') }}" class="align-items-center btn btn-theme d-flex">
                                    <i data-feather="plus"></i>Add Role
                                </a>
                            </form>
                        </div>
                        <div>
                            <div class="table-responsive">
                                <table id="table_id" class="table role-table all-package theme-table">
                                    <thead>
                                        <tr>
                                            <th style="color: black; background-color: #f8f9fa;">No</th>
                                            <th style="color: black; background-color: #f8f9fa;">Name</th>
                                            <th style="color: black; background-color: #f8f9fa;">Create At</th>
                                            <th style="color: black; background-color: #f8f9fa;">Options</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Dummy</td>
                                            <td>3 weeks ago</td>
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
                                            <td>2</td>
                                            <td>Self</td>
                                            <td>3 weeks ago</td>
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
                                            <td>3</td>
                                            <td>Dummy</td>
                                            <td>3 weeks ago</td>
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
                                            <td>4</td>
                                            <td>Author</td>
                                            <td>3 weeks ago</td>
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
    <!-- Container-fluid Ends-->
    @includeIf('backend.footer')
</div>
@endsection

@extends('layouts.backend')

@section('title', 'Đánh giá sản phẩm')

@section('content')
<!-- product review section start -->
<div class="page-body">

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <!-- Table Start -->
                    <div class="card-body">
                        <div class="title-header option-title">
                            <h5>Đánh giá sản phẩm</h5>
                        </div>
                        <div>
                            <div class="table-responsive">
                                <table class="user-table ticket-table review-table theme-table table" id="table_id">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Customer Name</th>
                                            <th>Product Name</th>
                                            <th>Rating</th>
                                            <th>Comment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reviews as $key => $review)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $review->user->name }}</td>
                                            <td>{{ $review->product->name }}</td>
                                            <td>
                                                <ul class="rating">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <li>
                                                        <i class="fas fa-star {{ $i <= $review->rating ? 'theme-color' : '' }}"></i>
                                                        </li>
                                                        @endfor
                                                </ul>
                                            </td>
                                            <td>{{ $review->comment }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <!-- Table End -->
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

@push('scripts')
    {{-- <script>
        $(document).ready(function() {
            $('#table_id').DataTable({
                language: {
                    search: "Tìm kiếm:",
                    lengthMenu: "Hiển thị _MENU_ đánh giá sản phẩm",
                    info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ đánh giá sản phẩm",
                    paginate: {
                        first: "Đầu",
                        last: "Cuối",
                        next: "Sau",
                        previous: "Trước"
                    },
                    zeroRecords: "Không tìm thấy đánh giá nào.",
                }
            });
        });
    </script> --}}
@endpush


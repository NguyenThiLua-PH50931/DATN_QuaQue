@extends('layouts.frontend')
@section('title', 'Yêu cầu hỗ trợ')
@section('contents')
    <!-- Breadcrumb Section Start -->
    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>Yêu cầu hỗ trợ</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('client.home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Yêu cầu hỗ trợ</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Contact Box Section Start -->
    <section class="contact-box-section">
        <div class="container-fluid-lg">
            <div class="row g-lg-5 g-3">
                <div class="col-lg-6">
                    <div class="left-sidebar-box">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="contact-image text-center d-flex align-items-center justify-content-center"
                                    style="min-height: 500px;">
                                    <img src="uploads/anh3.png" class="img-fluid blur-up lazyloaded" alt=""
                                        style="max-width: 600px; width: 100%; height: auto; margin: 0 auto; display: block;">
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="contact-title">
                                    <h3>Nhận hỗ trợ</h3>
                                </div>

                                <div class="contact-detail">
                                    <div class="row g-4">
                                        <div class="col-12">
                                            <div class="contact-detail-box">
                                                <div class="contact-detail-contain">
                                                    <p>Xem và quản lý các yêu cầu hỗ trợ của bạn. Nhấn vào "Tạo yêu cầu mới"
                                                        để gửi ticket.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="title d-xxl-none d-block mb-3">
                        <h2 class="fw-bold text-primary">Support Tickets</h2>
                    </div>

                    <div class="right-sidebar-box bg-white p-4 rounded shadow-sm">
                        <div class="row gy-4">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <!-- Form Tạo Ticket Mới -->
                            <div class="col-12">
                                <h4 class="fw-semibold text-dark mb-3"><i class="fa-solid fa-plus me-2 text-success"></i>
                                    Tạo Yêu Cầu Mới</h4>
                                <form method="POST" action="{{ route('client.support-ticket.store') }}">
                                    @csrf
                                    <!-- Tiêu đề -->
                                    <div class="mb-3">
                                        <label for="title" class="form-label fw-medium">Tiêu đề</label>
                                        <div class="input-group">
                                            <input type="text" name="title" class="form-control" id="title"
                                                placeholder="Nhập tiêu đề">
                                        </div>
                                    </div>

                                    <!-- Nội dung với CKEditor -->
                                    <div class="mb-3">
                                        <label for="editor" class="form-label fw-medium">Nội dung</label>
                                        <div class="input-group"></div>

                                        <textarea name="content" class="form-control" id="editor" placeholder="Nhập nội dung" rows="4"></textarea>
                                    </div>
                            </div>

                            <!-- Thêm đoạn này vào cuối trang (trước </body>) -->
                            <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
                            <script>
                                ClassicEditor
                                    .create(document.querySelector('#editor'))
                                    .catch(error => {
                                        console.error(error);
                                    });
                            </script>

                            <div class="text-end" style="margin-bottom: 25px;">
                                <button type="submit" class="btn btn-danger fw-bold px-4">
                                    <i class="fa-solid fa-paper-plane me-1"></i> Gửi Yêu Cầu
                                </button>
                            </div>
                            </form>
                        </div>

                        <!-- Danh sách Ticket -->
                        <div class="col-12">
                            <h4 class="fw-semibold text-dark mb-3"><i class="fa-solid fa-list-check me-2 text-info"></i>
                                Danh Sách Yêu Cầu</h4>
                            @forelse ($tickets as $ticket)
                                <div class="card shadow-sm border-0 mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title d-flex justify-content-between align-items-center">
                                            {{ $ticket->title }}
                                            <span
                                                class="badge bg-{{ $ticket->status == 'pending' ? 'warning' : 'success' }}">
                                                {{ $ticket->status == 'pending' ? 'Chờ xử lý' : 'Đã giải quyết' }}
                                            </span>
                                        </h5>
                                        <p class="card-text text-muted mb-2">
                                            <i class="fa-regular fa-clock me-1"></i>
                                            Ngày tạo: {{ $ticket->created_at->format('d/m/Y H:i') }}
                                        </p>
                                        <a href="{{ route('client.support-ticket.show', $ticket->id) }}"
                                            class="btn btn-outline-warning btn-sm">
                                            <i class="fa-solid fa-eye me-1"></i> Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted fst-italic">Không có yêu cầu nào.</p>
                            @endforelse

                            <!-- Phân trang -->
                            <div class="mt-3">
                                {{ $tickets->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        </div>
    </section>
    <!-- Contact Box Section End -->

    <!-- Map Section Start -->
    <section class="map-section">
        <div class="container-fluid p-0">
            <div class="map-box">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d3723.863556662289!2d105.74468687445314!3d21.0381447623719!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1zVHLGsOG7nW5nIENhbyDEkeG6s25nIEZQVCBQb2x5dGVjaG5pYw!5e0!3m2!1svi!2s!4v1750352713751!5m2!1svi!2s"
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[action="{{ route('client.support-ticket.store') }}"]');
            form.addEventListener('submit', function(e) {
                let title = form.querySelector('[name="title"]').value.trim();
                let content = form.querySelector('[name="content"]').value.trim();

                // Nếu lỗi thì ngăn submit + hiện popup
                if (!title) {
                    e.preventDefault();
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Vui lòng nhập tiêu đề.',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    return;
                }

                if (!content) {
                    e.preventDefault();
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Vui lòng nhập nội dung.',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                    return;
                }
            });
        });
    </script>

    <!-- Map Section End -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Hiện thông báo thành công --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
    @endif

    {{-- Hiện thông báo lỗi validate --}}
    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @foreach ($errors->all() as $error)
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: '{{ $error }}',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                @endforeach
            });
        </script>
    @endif

@endsection
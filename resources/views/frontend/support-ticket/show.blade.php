@extends('layouts.frontend')
@section('title', 'Chi tiết Yêu cầu')
@section('contents')
    <!-- Breadcrumb Section Start -->
    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>Chi tiết Yêu cầu</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('client.home') }}"><i class="fa-solid fa-house"></i></a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('client.support-ticket.index') }}">Yêu cầu hỗ
                                        trợ
                                    </a></li>
                                <li class="breadcrumb-item active" aria-current="page">Chi tiết</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <section class="contact-box-section py-5">
        <div class="container-fluid-lg">
            <div class="row g-4">
                <!-- Thông tin yêu cầu -->
                <div class="col-lg-6">
                    <div class="bg-white border border-light rounded shadow-sm p-4 h-100">
                        <h4 class="text-uppercase fw-bold mb-4" style="color: #0da487;">
                            <i class="fa-solid fa-circle-info me-2"></i>Thông tin Yêu cầu
                        </h4>

                        <div class="mb-3 d-flex">
                            <label class="form-label fw-semibold me-2" style="min-width: 90px;">Tiêu đề:</label>
                            <div class="form-control-plaintext flex-grow-1">{{ $ticket->title }}</div>
                        </div>

                        <div class="mb-3 d-flex">
                            <label class="form-label fw-semibold me-2" style="min-width: 90px;">Nội dung:</label>
                            <div class="form-control-plaintext flex-grow-1">{{ $ticket->content }}</div>
                        </div>

                        <div class="mb-3 d-flex">
                            <label class="form-label fw-semibold me-2" style="min-width: 90px;">Trạng thái:</label>
                            <span
                                class="badge px-3 py-1 fs-6 bg-{{ $ticket->status == 'pending' ? 'warning' : 'success' }}">
                                {{ $ticket->status == 'pending' ? 'Chờ xử lý' : 'Đã giải quyết' }}
                            </span>
                        </div>

                        <div class="d-flex">
                            <label class="form-label fw-semibold me-2" style="min-width: 90px;">Ngày tạo:</label>
                            <div class="form-control-plaintext">{{ $ticket->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>

                </div>

                <!-- Phản hồi từ Admin -->
                <div class="col-lg-6">
                    <div class="bg-white border border-light rounded shadow-sm p-4 h-100">
                        <h4 class="text-uppercase fw-bold mb-4" style="color: #0da487;">
                            <i class="fa-solid fa-reply me-2"></i>Phản hồi từ Admin
                        </h4>

                        @forelse ($ticket->replies as $reply)
                            <div class="border-start border-4 ps-3 mb-4" style="border-color: #0da487;">
                                <p class="mb-1">
                                    <strong class="text-dark">
                                        <i class="fa-solid fa-user-shield text-success me-1"></i>{{ $reply->admin->name }}:
                                    </strong> {{ $reply->reply }}
                                </p>
                                <p class="text-muted small mb-0">
                                    <i class="fa-regular fa-clock me-1"></i>{{ $reply->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        @empty
                            <p class="text-muted fst-italic">Chưa có phản hồi.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>



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
    <!-- Map Section End -->
@endsection

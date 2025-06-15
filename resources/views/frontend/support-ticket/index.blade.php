@extends('layouts.frontend')
@section('title', 'Support Tickets')
@section('contents')
    <!-- Breadcrumb Section Start -->
    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>Support Tickets</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('client.home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Support Tickets</li>
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
                                <div class="contact-image">
                                    <img src="{{ asset('assets/images/inner-page/support-ticket.png') }}"
                                        class="img-fluid blur-up lazyloaded" alt="Support Ticket">
                                </div>
                            </div>
                            <div class="col-xl-12">
                                <div class="contact-title">
                                    <h3>Get Support</h3>
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
                    <div class="title d-xxl-none d-block">
                        <h2>Support Tickets</h2>
                    </div>
                    <div class="right-sidebar-box">
                        <div class="row">
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            <!-- Form Tạo Ticket Mới -->
                            <div class="col-12">
                                <h4>Tạo Yêu Cầu Mới</h4>
                                <form method="POST" action="{{ route('client.support-ticket.store') }}">
                                    @csrf
                                    <div class="mb-md-4 mb-3 custom-form">
                                        <label for="title" class="form-label">Tiêu đề</label>
                                        <div class="custom-input">
                                            <input type="text" name="title" class="form-control" id="title"
                                                required placeholder="Nhập tiêu đề">
                                            <i class="fa-solid fa-heading"></i>
                                        </div>
                                    </div>
                                    <div class="mb-md-4 mb-3 custom-form">
                                        <label for="content" class="form-label">Nội dung</label>
                                        <div class="custom-textarea">
                                            <textarea name="content" class="form-control" id="content" required placeholder="Nhập nội dung" rows="4"></textarea>
                                            <i class="fa-solid fa-message"></i>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-animation btn-md fw-bold ms-auto">Gửi Yêu
                                        Cầu</button>
                                </form>
                            </div>

                            <!-- Danh sách Ticket -->
                            <div class="col-12 mt-4">
                                <h4>Danh Sách Yêu Cầu</h4>
                                @forelse ($tickets as $ticket)
                                    <div class="contact-detail-box mb-3">
                                        <div class="contact-detail-title">
                                            <h5>{{ $ticket->title }} <span
                                                    class="badge bg-{{ $ticket->status == 'pending' ? 'warning' : 'success' }}">{{ $ticket->status == 'pending' ? 'Chờ xử lý' : 'Đã giải quyết' }}</span>
                                            </h5>
                                        </div>
                                        <div class="contact-detail-contain">
                                            <p>Ngày tạo: {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                                            <a href="{{ route('client.support-ticket.show', $ticket->id) }}"
                                                class="btn btn-sm btn-primary">Xem chi tiết</a>
                                        </div>
                                    </div>
                                @empty
                                    <p>Không có yêu cầu nào.</p>
                                @endforelse
                                {{ $tickets->links() }}
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
                    src="https://www.google.com/maps/embed?pb=!1m23!1m12!1m3!1d2994.3803116994895!2d55.29773782339708!3d25.222534631321!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m8!3e6!4m5!1s0x3e5f43496ad9c645%3A0xbde66e5084295162!2sDubai%20-%20United%20Arab%20Emirates!3m2!1d25.2048493!2d55.2707828!4m0!5e1!3m2!1sen!2sin!4v1652217109535!5m2!1sen!2sin"
                    style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
    <!-- Map Section End -->
@endsection

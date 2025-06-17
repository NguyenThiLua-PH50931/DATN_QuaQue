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
                                <li class="breadcrumb-item"><a href="{{ route('client.support-ticket.index') }}">Support Tickets</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Chi tiết</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <section class="contact-box-section">
        <div class="container-fluid-lg">
            <div class="row g-lg-5 g-3">
                <div class="col-lg-6">
                    <div class="left-sidebar-box">
                        <div class="contact-detail">
                            <h4>Thông tin Yêu cầu</h4>
                            <div class="contact-detail-box">
                                <p><strong>Tiêu đề:</strong> {{ $ticket->title }}</p>
                                <p><strong>Nội dung:</strong> {{ $ticket->content }}</p>
                                <p><strong>Trạng thái:</strong> {{ $ticket->status == 'pending' ? 'Chờ xử lý' : 'Đã giải quyết' }}</p>
                                <p><strong>Ngày tạo:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="right-sidebar-box">
                        <h4>Phản hồi từ Admin</h4>
                        @forelse ($ticket->replies as $reply)
                            <div class="contact-detail-box mb-3">
                                <p><strong>{{ $reply->admin->name }}:</strong> {{ $reply->reply }}</p>
                                <p><small>{{ $reply->created_at->format('d/m/Y H:i') }}</small></p>
                            </div>
                        @empty
                            <p>Chưa có phản hồi.</p>
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
                    src="https://www.google.com/maps/embed?pb=!1m23!1m12!1m3!1d2994.3803116994895!2d55.29773782339708!3d25.222534631321!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!4m8!3e6!4m5!1s0x3e5f43496ad9c645%3A0xbde66e5084295162!2sDubai%20-%20United%20Arab%20Emirates!3m2!1d25.2048493!2d55.2707828!4m0!5e1!3m2!1sen!2sin!4v1652217109535!5m2!1sen!2sin"
                    style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
    <!-- Map Section End -->
@endsection

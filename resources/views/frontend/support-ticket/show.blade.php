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
                                <li class="breadcrumb-item"><a href="{{ route('client.support-ticket.index') }}">Yêu cầu hỗ trợ
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

    <section class="contact-box-section">
        <div class="container-fluid-lg">
            <div class="row g-lg-5 g-3">
                <div class="col-lg-6">
                    <div class="left-sidebar-box">
                        <div class="contact-detail">
                            <h4>Thông tin Yêu cầu</h4>
                            <div class="contact-detail-box">
                                <div>
                                    <p><strong>Tiêu đề:</strong> {{ $ticket->title }}</p>
                                </div><br>
                                <div>
                                    <p><strong>Nội dung:</strong> {{ $ticket->content }}</p>
                                </div><br>
                                <div>
                                    <p><strong>Trạng thái:</strong>
                                        {{ $ticket->status == 'pending' ? 'Chờ xử lý' : 'Đã giải quyết' }}</p>
                                </div><br>
                                <div>
                                    <p><strong>Ngày tạo:</strong> {{ $ticket->created_at->format('d/m/Y H:i') }}</p>
                                </div><br>
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
                    src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d3723.863556662289!2d105.74468687445314!3d21.0381447623719!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1zVHLGsOG7nW5nIENhbyDEkeG6s25nIEZQVCBQb2x5dGVjaG5pYw!5e0!3m2!1svi!2s!4v1750352713751!5m2!1svi!2s"
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>
    <!-- Map Section End -->
@endsection

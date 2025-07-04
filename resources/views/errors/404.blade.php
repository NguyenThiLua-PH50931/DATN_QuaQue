@extends('layouts.frontend')
@section('title', '404 - Không tìm thấy trang')
@section('contents')
    <div class="container d-flex flex-column align-items-center justify-content-center" style="min-height: 80vh;">
        <img src="/frontend/assets/images/inner-page/404.png" alt="404 Error"
            style="max-width: 500px; width: 100%; margin-bottom: 40px;">
        <h1 style="font-size: 2.5rem; font-weight: bold; color: #222; margin-bottom: 16px;">404 - Không tìm thấy trang</h1>
        <p style="font-size: 1.2rem; color: #666; margin-bottom: 32px;">Trang bạn tìm kiếm không tồn tại hoặc đã bị di
            chuyển.</p>
        <a href="{{ route('client.home') }}" class="btn btn-primary px-4 py-2"
            style="font-size: 1.1rem; border-radius: 8px;">Về trang chủ</a>
    </div>
@endsection

@extends('layouts.frontend')
@section('title', 'hỗ trợ')
@section('contents')
    <!-- BREADCRUMB SECTION START -->
    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>hỗ trợ</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="index.html">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">hỗ trợ</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- BREADCRUMB SECTION END -->

    <!-- WISHLIST SECTION START -->
    <section class="wishlist-section section-b-space">
        <div class="container-fluid-lg">
            <div class="container">
                <h1>Tạo yêu cầu hỗ trợ</h1>
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <form method="POST" action="{{ route('client.support-ticket.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="title" class="form-label">Tiêu đề</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Nội dung</label>
                        <textarea name="content" class="form-control" rows="5" required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi yêu cầu</button>
                </form>
            </div>
        </div>
    </section>
    <!-- WISHLIST SECTION END -->
@endsection

@extends('layouts.frontend')
@section('title', 'Blog')
@section('contents')
    <style>
        .blog-image {
            width: 100%;
            aspect-ratio: 4 / 3;
            /* Tỷ lệ khung hình */
            overflow: hidden;
            border-radius: 10px;
            /* Tuỳ chọn bo góc */
        }

        .blog-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Cắt ảnh vừa khung */
            display: block;
        }
    </style>


    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>Tin tức</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="index.html">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Tin Tức</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Blog Section Start -->
    <section class="blog-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row g-4">
                <div class="col-xxl-9 col-xl-8 col-lg-7 order-lg-2">
                    <div class="row g-4 ratio_65">

                        @foreach ($blog as $item)
                            <div class="col-xxl-4 col-sm-6">
                                <div class="blog-box wow fadeInUp">
                                    <div class="blog-image">
                                        <a href="{{ route('client.blogs-detail', ['id' => $item->id]) }}">
                                            @if ($item->thumbnail)
                                                <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->title }}">
                                            @endif
                                        </a>
                                    </div>

                                    <div class="blog-contain">
                                        <div class="blog-label">
                                            <span class="time">
                                                <i data-feather="clock"></i>
                                                <span>{{ $item->created_at ? $item->created_at->format('F d, Y') : 'Chưa có ngày tạo' }}</span>
                                            </span>
                                        </div>
                                        <a href="{{ route('client.blogs-detail', ['id' => $item->id]) }}">
                                            <h3>{{ $item->title }}</h3>
                                        </a>
                                        <button
                                            onclick="location.href='{{ route('client.blogs-detail', ['id' => $item->id]) }}'"
                                            class="blog-button">
                                            Đọc thêm <i class="fa-solid fa-right-long"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>



                    <nav class="custome-pagination">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="javascript:void(0)" tabindex="-1">
                                    <i class="fa-solid fa-angles-left"></i>
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="javascript:void(0)">1</a>
                            </li>
                            <li class="page-item" aria-current="page">
                                <a class="page-link" href="javascript:void(0)">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="javascript:void(0)">
                                    <i class="fa-solid fa-angles-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>

                <div class="col-xxl-3 col-xl-4 col-lg-5 order-lg-1">
                    <div class="left-sidebar-box wow fadeInUp">
                        <div class="left-search-box">
                            <div class="search-box">
                                <input type="search" class="form-control" id="exampleFormControlInput1"
                                    placeholder="Tìm kiếm....">
                            </div>
                        </div>

                        <div class="accordion left-accordion-box" id="accordionPanelsStayOpenExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true"
                                        aria-controls="panelsStayOpen-collapseOne">
                                        Bài viết gần đây
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show"
                                    aria-labelledby="panelsStayOpen-headingOne">
                                    <div class="accordion-body pt-0">
                                        <div class="recent-post-box">

                                            @foreach ($recentBlogs as $item)
                                                <div class="recent-box">
                                                    <a href="{{ route('client.blogs-detail', ['id' => $item->id]) }}"
                                                        class="recent-image">
                                                        @if ($item->thumbnail)
                                                            <img src="{{ asset($item->thumbnail) }}"
                                                                alt="{{ $item->title }}"
                                                                class="img-fluid blur-up lazyload">
                                                        @endif
                                                    </a>

                                                    <div class="recent-detail">
                                                        <a href="{{ route('client.blogs-detail', ['id' => $item->id]) }}">
                                                            <h5 class="recent-name">{{ $item->title }}</h5>
                                                        </a>
                                                        <h6><span>{{ $item->created_at ? $item->created_at->format('F d, Y') : 'Chưa có ngày tạo' }}</span>

                                                            <i data-feather="thumbs-up"></i>
                                                        </h6>

                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Blog Section End -->



@endsection

@extends('layouts.frontend')
@section('title', 'Blog-detail')
@section('contents')

 <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>Chi tiết tin tức</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="index.html">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item" aria-current="page">Tin Tức</li>
                                <li class="breadcrumb-item active" aria-current="page">Chi tiết</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Blog Details Section Start -->
    <section class="blog-section section-b-space">
        <div class="container-fluid-lg">
            <div class="row g-sm-4 g-3">
                <div class="col-xxl-3 col-xl-4 col-lg-5 d-lg-block d-none">
                    <div class="left-sidebar-box">
                        {{-- <div class="left-search-box">
                            <div class="search-box">
                                <input type="search" class="form-control" id="exampleFormControlInput4"
                                    placeholder="Search....">
                            </div>
                        </div> --}}

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
                                            <div class="recent-post-box">
                                                @foreach($recentBlogs as $item)
                                                    <div class="recent-box">
                                                        <a href="{{ route('client.blogs-detail', ['id' => $item->id]) }}" class="recent-image">
                                                            @if($item->thumbnail)
                                                                <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->title }}" class="img-fluid blur-up lazyload">
                                                            @endif
                                                        </a>

                                                        <div class="recent-detail">
                                                            <a href="{{ route('client.blogs-detail', ['id' => $item->id]) }}">
                                                                <h5 class="recent-name">{{ $item->title }}</h5>
                                                            </a>
                                                            <h6><span>{{ $blog->created_at ? $blog->created_at->format('F d, Y') : 'Chưa có ngày tạo' }}</span>
                                                                <i data-feather="thumbs-up"></i></h6>
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

                <div class="col-xxl-9 col-xl-8 col-lg-7 ratio_50">
                    <div class="blog-detail-image rounded-3 mb-4">
                        @if($blog->thumbnail)
                            <img src="{{ asset($blog->thumbnail) }}" alt="{{ $blog->title }}" class="bg-img blur-up lazyload">
                        @endif
                        <div class="blog-image-contain">
                            <h2>{{ $blog->title }}</h2>
                            <ul class="contain-comment-list">
                                {{-- <li>
                                    <div class="user-list">
                                        <i data-feather="user"></i>
                                        <span>Caroline</span>
                                    </div>
                                </li> --}}

                                <li>
                                    <div class="user-list">
                                        <i data-feather="calendar"></i>
                                        <span>{{ $blog->created_at ? $blog->created_at->format('F d, Y') : 'Chưa có ngày tạo' }}</span>
                                    </div>
                                </li>

                                <li>
                                    <div class="user-list">
                                        <i data-feather="message-square"></i>
                                        <span>{{ $blog->comments->count()}} Bình luận</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="blog-detail-contain">
                        <div style="max-width: 100%; overflow-x: auto;">
                            {!! str_replace('<img', '<img style="max-width: 100%; height: auto; display: block;"', $blog->content) !!}
                        </div>
                    </div>

                    <div class="comment-box overflow-hidden">
                        <div class="leave-title">
                            <h3>Bình luận</h3>
                        </div>

                        <div class="user-comment-box" id="comments">
                            <ul>
                                @forelse ($blog->comments as $comment)
                                    <li>
                                        <div class="user-box border-color">
                                            <div class="user-iamge">
                                                @if ($comment->user->avatar)
                                                    <img src="{{ asset('storage/' . $comment->user->avatar) }}"
                                                        class="img-fluid blur-up lazyload" width="60" alt="{{ $comment->user->name }}">
                                                @else
                                                    <img src="{{ asset('assets/images/users/default.jpg') }}"
                                                        class="img-fluid blur-up lazyload" width="60" alt="Default Avatar">
                                                @endif

                                                <div class="user-name">
                                                    <h6>{{ $comment->created_at->format('d M, Y') }}</h6>
                                                    <h5 class="text-content">{{ $comment->user->name ?? 'Ẩn danh' }}</h5>
                                                </div>
                                            </div>

                                            <div class="user-contain">
                                                <p>{!! nl2br(e($comment->content)) !!}</p>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li>
                                        <div class="text-muted">Chưa có bình luận nào.</div>
                                    </li>
                                @endforelse
                            </ul>
                        </div>

                    </div>

                    <div class="leave-box">
                        <div class="leave-title mt-0">
                            <h3>Bình luận</h3>
                        </div>

                        <div class="leave-comment">
                            @if (!Auth::check())
                                <div class="alert alert-warning">
                                    Vui lòng <a href="{{ route('login') }}">đăng nhập</a> để bình luận.
                                </div>
                            @endif

                            <form action="{{ route('client.blog.comments.store') }}" method="POST">
                                @csrf

                                {{-- Hidden input để gửi kèm ID bài viết --}}
                                <input type="hidden" name="blog_id" value="{{ $blog->id }}">

                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="blog-input">
                                            <textarea
                                                class="form-control"
                                                name="content"
                                                id="commentTextarea"
                                                rows="4"
                                                placeholder="Viết bình luận của bạn tại đây..."
                                                {{ Auth::check() ? '' : 'disabled' }}
                                            ></textarea>
                                        </div>
                                    </div>
                                </div>

                                <button
                                    type="submit"
                                    class="btn btn-animation ms-xxl-auto mt-xxl-0 mt-3 btn-md fw-bold"
                                    {{ Auth::check() ? '' : 'disabled' }}
                                >
                                    Gửi bình luận
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- Blog Details Section End -->
@endsection

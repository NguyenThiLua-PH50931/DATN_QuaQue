@extends('layouts.backend')

@section('title', 'Xem tin tức')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <!-- Main Content -->
                <div class="row g-4">
                    <!-- Content Column -->
                    <div class="col-lg-8">
                        <div class="content-card">
                            <div class="content-header">
                                <h3><i class="fas fa-file-text me-3"></i>Nội dung bài viết</h3>
                            </div>
                            <div class="content-body">
                                {!! str_replace('<img', '<img style="max-width: 100%; height: auto; display: block; border-radius: 12px; margin: 2rem 0; box-shadow: 0 10px 30px rgba(0,0,0,0.1);"', $blog->content) !!}
                            </div>

                            <!-- Bình luận -->
                            <div class="content-header">
                                <h3><i class="fas fa-file-text me-3"></i>Bình luận</h3>
                            </div>
                            <div class="content-body">
                                @forelse ($blog->comments as $comment)
                                    <div class="comment-item border rounded p-3 mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong>{{ $comment->user->name ?? 'Ẩn danh' }}</strong>
                                            <span class="text-muted small">
                                                {{ $comment->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </div>
                                        <div class="comment-content">{!! nl2br(e($comment->content)) !!}</div>
                                    </div>
                                @empty
                                    <div class="text-muted">Chưa có bình luận nào.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Image Gallery Card -->
                        @if($blog->thumbnail)
                        <div class="info-card mb-4">
                            <div class="card-header-custom">
                                <h5><i class="fas fa-images me-2"></i>Ảnh đại diện</h5>
                            </div>
                            <div class="image-showcase">
                                <div class="main-image">
                                    <img src="{{ asset($blog->thumbnail) }}" alt="{{ $blog->title }}" class="featured-image" onclick="openImageModal('{{ asset($blog->thumbnail) }}')">
                                   
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Details Card -->
                        <div class="info-card">
                            <div class="card-header-custom">
                                <h5><i class="fas fa-info-circle me-2"></i>Thông tin chi tiết</h5>
                            </div>
                            <div class="details-list">
                                 <div class="detail-item">
                                    <div class="detail-icon">
                                        <i class="fas fa-name"></i>
                                    </div>
                                    <div class="detail-content">
                                        <label>Tiêu đề</label>
                                        <span>{{ $blog->title }}</span>
                                    </div>
                                </div>
                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <i class="fas fa-link"></i>
                                    </div>
                                    <div class="detail-content">
                                        <label>Đường dẫn</label>
                                        <span>{{ $blog->slug }}</span>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <i class="fas fa-play-circle"></i>
                                    </div>
                                    <div class="detail-content">
                                        <label>Ngày bắt đầu</label>
                                        <span>{{ $blog->start_date ? $blog->start_date->format('d/m/Y') : 'Chưa thiết lập' }}</span>
                                    </div>
                                </div>

                                <div class="detail-item">
                                    <div class="detail-icon">
                                        <i class="fas fa-stop-circle"></i>
                                    </div>
                                    <div class="detail-content">
                                        <label>Ngày kết thúc</label>
                                        <span>{{ $blog->end_date ? $blog->end_date->format('d/m/Y') : 'Chưa thiết lập' }}</span>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Actions Card -->
                        <div class="info-card mt-4">
                            <div class="action-buttons">
                                <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn btn-primary btn-action">
                                    <i class="fas fa-edit me-2"></i>Chỉnh sửa
                                </a>
                                <a href="{{ route('admin.blog.index') }}" class="btn btn-outline-secondary btn-action">
                                    <i class="fas fa-list me-2"></i>Danh sách
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Xem ảnh</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <img id="modalImage" src="" alt="Preview" class="w-100">
            </div>
        </div>
    </div>
</div>

@includeIf('backend.footer')

<style>
/* Hero Section */
.hero-section {
    position: relative;
    height: 400px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.1);
}

.hero-content {
    position: relative;
    height: 100%;
    display: flex;
    align-items: end;
}

.hero-overlay {
    position: relative;
    z-index: 2;
    background: linear-gradient(transparent 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0.7) 100%);
    padding: 2rem;
    width: 100%;
    color: white;
}

.hero-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.hero-bg {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
}

.hero-breadcrumb {
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border-radius: 50px;
    padding: 0.5rem 1rem;
}

.hero-breadcrumb .breadcrumb-item a {
    color: rgba(255,255,255,0.9);
    text-decoration: none;
}

.hero-breadcrumb .breadcrumb-item.active {
    color: white;
}

.btn-floating {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(10px);
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    color: white;
    transition: all 0.3s ease;
}

.btn-floating:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-2px);
    color: white;
}

.hero-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    line-height: 1.2;
    text-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.hero-meta {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    opacity: 0.9;
}

/* Cards */
.content-card, .info-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.06);
    border: 1px solid rgba(0,0,0,0.05);
    overflow: hidden;
    transition: all 0.3s ease;
}

.content-card:hover, .info-card:hover {
    box-shadow: 0 12px 40px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

.content-header, .card-header-custom {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.content-header h3, .card-header-custom h5 {
    margin: 0;
    color: #495057;
    font-weight: 600;
}

.content-body {
    padding: 2rem;
    line-height: 1.8;
    color: #495057;
}

.content-body h1, .content-body h2, .content-body h3, 
.content-body h4, .content-body h5, .content-body h6 {
    color: #2d3436;
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.content-body p {
    margin-bottom: 1.2rem;
    text-align: justify;
}

.content-body ul, .content-body ol {
    padding-left: 2rem;
    margin-bottom: 1.2rem;
}

/* Image Showcase */
.image-showcase {
    padding: 1.5rem;
}

.main-image {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
    group: hover;
}

.featured-image {
    width: 100%;
    height: 250px;
    object-fit: cover;
    transition: all 0.3s ease;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
    color: white;
    font-size: 1.5rem;
}

.main-image:hover .featured-image {
    transform: scale(1.05);
}

.main-image:hover .image-overlay {
    opacity: 1;
}

/* Details List */
.details-list {
    padding: 1.5rem;
}

.detail-item {
    display: flex;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    margin-right: 1rem;
    flex-shrink: 0;
}

.detail-content {
    flex: 1;
    min-width: 0;
}

.detail-content label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.detail-content span {
    font-weight: 500;
    color: #495057;
    word-break: break-all;
}

/* Action Buttons */
.action-buttons {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.btn-action {
    border-radius: 10px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
    text-align: center;
}

.btn-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* Modal Styles */
.modal-content {
    border-radius: 16px;
    border: none;
    overflow: hidden;
}

.modal-header {
    background: #f8f9fa;
    padding: 1rem 1.5rem;
}

#modalImage {
    border-radius: 0;
}

/* Responsive Design */
@media (max-width: 991px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .hero-meta {
        gap: 1rem;
    }
    
    .meta-item {
        font-size: 0.8rem;
    }
}

@media (max-width: 768px) {
    .hero-section {
        height: 300px;
    }
    
    .hero-title {
        font-size: 1.5rem;
    }
    
    .hero-overlay {
        padding: 1rem;
    }
    
    .content-body {
        padding: 1rem;
    }
    
    .details-list, .image-showcase, .action-buttons {
        padding: 1rem;
    }
}

/* Animations */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.content-card, .info-card {
    animation: slideInUp 0.6s ease-out;
}

.content-card {
    animation-delay: 0.1s;
}

.info-card:nth-child(1) { animation-delay: 0.2s; }
.info-card:nth-child(2) { animation-delay: 0.3s; }
.info-card:nth-child(3) { animation-delay: 0.4s; }
</style>

<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}
</script>
@endsection
@extends('layouts.backend')

@section('title', 'Sửa sản phẩm')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-sm-8 m-auto">

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="card">
                            <div class="card-body">
                                <div class="card-header-2"><h5>Sửa thông tin sản phẩm</h5></div>
                                <form action="{{ route('admin.products.update', $product->slug) }}" method="POST" enctype="multipart/form-data" class="theme-form theme-form-2 mega-form">
                                    @csrf

                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-sm-3 mb-0">Tên sản phẩm</label>
                                        <div class="col-sm-9">
                                            <input name="name" class="form-control" type="text" required value="{{ old('name', $product->name) }}">
                                        </div>
                                    </div>

                                    <div class="mb-4 row align-items-center">
                                        <label class="col-sm-3 col-form-label form-label-title">Danh mục</label>
                                        <div class="col-sm-7">
                                            <select name="category_id" class="form-select" required>
                                                <option value="">--Chọn danh mục--</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat->id }}" {{ (old('category_id', $product->category_id)==$cat->id)?'selected':'' }}>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-4 row align-items-center">
                                        <label class="col-sm-3 col-form-label form-label-title">Vùng miền</label>
                                        <div class="col-sm-7">
                                            <select name="region_id" class="form-select" required>
                                                <option value="">--Chọn vùng--</option>
                                                @foreach($regions as $region)
                                                    <option value="{{ $region->id }}" {{ (old('region_id', $product->region_id)==$region->id)?'selected':'' }}>{{ $region->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-sm-3 mb-0">Mô tả</label>
                                        <div class="col-sm-9">
                                            <textarea name="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
                                        </div>
                                    </div>

                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-sm-3 mb-0">Ảnh đại diện</label>
                                        <div class="col-sm-9">
                                            @if($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}" style="max-width:120px; margin-bottom:10px;">
                                            @endif
                                            <input name="image" class="form-control" type="file" accept="image/*">
                                            <small>Bỏ trống nếu không muốn đổi ảnh</small>
                                        </div>
                                    </div>

                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-sm-3 mb-0">Ảnh mô tả</label>
                                        <div class="col-sm-9">
                                            @if($images)
                                                @foreach($images as $img)
                                                    <img src="{{ asset('storage/' . $img->image_url) }}" style="max-width:80px; margin-right:5px;">
                                                @endforeach
                                            @endif
                                            <input name="images[]" class="form-control" type="file" accept="image/*" multiple>
                                            <small>Bỏ trống nếu không muốn đổi ảnh mô tả</small>
                                        </div>
                                    </div>

                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-sm-3 mb-0">Xuất xứ</label>
                                        <div class="col-sm-9">
                                            <input name="origin" class="form-control" type="text" value="{{ old('origin', $product->origin) }}">
                                        </div>
                                    </div>

                                    {{-- =============== Biến thể sản phẩm =============== --}}
                                    <div class="mb-4">
                                        <div class="card-header-2"><h5>Biến thể (Variations)</h5></div>
                                        <div id="variants-list">
                                            <div class="row mb-1 fw-bold">
                                                <div class="col-sm-3">Tên biến thể</div>
                                                <div class="col-sm-2">Giá bán</div>
                                                <div class="col-sm-2">Tồn kho</div>
                                                <div class="col-sm-3">Ghi chú (tùy chọn)</div>
                                                <div class="col-sm-1">SKU</div>
                                                <div class="col-sm-1"></div>
                                            </div>
                                            @foreach($variants as $i => $variant)
                                            <div class="row mb-2 variant-row">
                                                <div class="col-sm-3">
                                                    <input name="variants[{{$i}}][name]" class="form-control" type="text" placeholder="Tên biến thể" required value="{{ $variant->name }}">
                                                </div>
                                                <div class="col-sm-2">
                                                    <input name="variants[{{$i}}][price]" class="form-control" type="number" step="0.01" placeholder="Giá bán" required value="{{ $variant->price }}">
                                                </div>
                                                <div class="col-sm-2">
                                                    <input name="variants[{{$i}}][stock]" class="form-control" type="number" placeholder="Tồn kho" min="0" required value="{{ $variant->stock }}">
                                                </div>
                                                <div class="col-sm-3">
                                                    <input name="variants[{{$i}}][description]" class="form-control" type="text" placeholder="Ghi chú (tùy chọn)" value="{{ $variant->description }}">
                                                </div>
                                                <div class="col-sm-1">
                                                    <input name="variants[{{$i}}][sku]" class="form-control sku-auto" type="text" placeholder="SKU" readonly value="{{ $variant->sku }}">
                                                </div>
                                                <div class="col-sm-1 d-flex align-items-center justify-content-center">
                                                    <button type="button" class="btn btn-outline-danger btn-sm btn-remove-variant" style="{{ $variants->count()==1?'display:none;':'' }}">&times;</button>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <a href="javascript:void(0)" id="add-variant-btn" class="text-success mt-2 d-inline-block" style="font-weight:500;">+ Thêm biến thể</a>
                                    </div>
                                    {{-- ============= End Biến thể ================ --}}

                                    <div class="mb-4 row align-items-center">
                                        <label class="col-sm-3 col-form-label form-label-title">Kích hoạt</label>
                                        <div class="col-sm-9">
                                            <select name="active" class="form-select">
                                                <option value="1" {{ $product->active == 1 ? 'selected' : '' }}>Có</option>
                                                <option value="0" {{ $product->active == 0 ? 'selected' : '' }}>Không</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-sm-9 offset-sm-3">
                                            <button type="submit" class="btn btn-success">Cập nhật sản phẩm</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @includeIf('backend.footer')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let variantIndex = {{ count($variants) }};
    function generateSKU(name = '') {
        const base = 'PRD-' + (new Date()).getTime();
        const val = name ? '-' + name.replace(/\s/g, '').toUpperCase().substring(0,6) : '';
        return base + val;
    }
    document.getElementById('add-variant-btn').onclick = function () {
        let html = `<div class="row mb-2 variant-row">
            <div class="col-sm-3">
                <input name="variants[${variantIndex}][name]" class="form-control" type="text" placeholder="Tên biến thể" required>
            </div>
            <div class="col-sm-2">
                <input name="variants[${variantIndex}][price]" class="form-control" type="number" step="0.01" placeholder="Giá bán" required>
            </div>
            <div class="col-sm-2">
                <input name="variants[${variantIndex}][stock]" class="form-control" type="number" placeholder="Tồn kho" min="0" required>
            </div>
            <div class="col-sm-3">
                <input name="variants[${variantIndex}][description]" class="form-control" type="text" placeholder="Ghi chú (tùy chọn)">
            </div>
            <div class="col-sm-1">
                <input name="variants[${variantIndex}][sku]" class="form-control sku-auto" type="text" placeholder="SKU" readonly>
            </div>
            <div class="col-sm-1 d-flex align-items-center justify-content-center">
                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-variant">&times;</button>
            </div>
        </div>`;
        document.getElementById('variants-list').insertAdjacentHTML('beforeend', html);
        variantIndex++;
        showRemoveButtons();
    };

    document.addEventListener('input', function(e){
        if(e.target.name && e.target.name.includes('variants') && e.target.name.endsWith('[name]')) {
            let row = e.target.closest('.variant-row');
            let skuInput = row.querySelector('.sku-auto');
            skuInput.value = generateSKU(e.target.value);
        }
    });

    document.addEventListener('click', function(e){
        if(e.target.classList.contains('btn-remove-variant')) {
            let row = e.target.closest('.variant-row');
            row.remove();
            showRemoveButtons();
        }
    });

    function showRemoveButtons() {
        let rows = document.querySelectorAll('.variant-row');
        rows.forEach((row, idx) => {
            let btn = row.querySelector('.btn-remove-variant');
            if (btn) btn.style.display = (rows.length > 1) ? 'inline-block' : 'none';
        });
    }
    showRemoveButtons();
</script>
@endsection

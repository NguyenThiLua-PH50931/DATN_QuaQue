@extends('layouts.backend')

@section('title', 'Sửa sản phẩm')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-sm-10 m-auto">

                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">{{ session('error') }}</div>
                            @endif

                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="title-header option-title d-sm-flex d-block">
                                        <h5>Sửa Sản Phẩm</h5>
                                        <div class="right-options">
                                            <ul>
                                                <li>
                                                    <a class="btn btn-solid" href="{{ route('admin.products.index') }}">Quay
                                                        lại</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <form id="main-form" action="{{ route('admin.products.update', $product->slug) }}"
                                        method="POST" enctype="multipart/form-data"
                                        class="theme-form theme-form-2 mega-form">
                                        @csrf
                                        {{-- Nếu route dùng POST mà bạn muốn PUT, nhớ thêm: @method('PUT') nếu có --}}

                                        {{-- Nav tabs --}}
                                        <ul class="nav nav-tabs mb-3" id="editProductTab" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="info-tab" data-bs-toggle="tab"
                                                    data-bs-target="#info" type="button" role="tab"
                                                    aria-controls="info" aria-selected="true">Thông tin sản phẩm
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="category-tab" data-bs-toggle="tab"
                                                    data-bs-target="#category" type="button" role="tab"
                                                    aria-controls="category" aria-selected="false">Phân loại & Danh mục
                                                </button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="variant-tab" data-bs-toggle="tab"
                                                    data-bs-target="#variant" type="button" role="tab"
                                                    aria-controls="variant" aria-selected="false">Biến thể sản phẩm
                                                </button>
                                            </li>
                                        </ul>

                                        {{-- Tab contents --}}
                                        <div class="tab-content" id="editProductTabContent">

                                            {{-- TAB 1: Thông tin sản phẩm --}}
                                            <div class="tab-pane fade show active" id="info" role="tabpanel"
                                                aria-labelledby="info-tab">
                                                {{-- Tên sản phẩm --}}
                                                <div class="mb-3">
                                                    <label class="form-label">Tên sản phẩm</label>
                                                    <input type="text" class="form-control" name="name"
                                                        value="{{ old('name', $product->name) }}">
                                                    @error('name')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                {{-- Ảnh đại diện hiện tại --}}
                                                <div class="form-group">
                                                    <label for="image">Ảnh đại diện</label>
                                                    <input type="file" class="form-control" id="image"
                                                        name="image">
                                                    @if ($product->image)
                                                        <div class="mt-2">
                                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                                alt="Ảnh hiện tại" style="max-width: 200px;">
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- Ảnh mô tả hiện tại --}}
                                                <div class="form-group mt-4">
                                                    <label class="form-label">Ảnh mô tả sản phẩm</label>
                                                    <div class="description-images-container d-flex flex-wrap gap-2">
                                                        @foreach ($product->product_images as $image)
                                                            <div class="image-item position-relative border rounded"
                                                                style="width: 100px; height: 100px;">
                                                                <img src="{{ $image->image_url }}"
                                                                    class="w-100 h-100 object-fit-cover" alt="Ảnh mô tả">
                                                                <button type="button"
                                                                    class="btn btn-danger btn-sm product-image-delete-btn position-absolute rounded-circle p-1 delete-image-x-btn"
                                                                    data-id="{{ $image->id }}"
                                                                    data-url="{{ route('admin.products.image.delete', $image->id) }}">
                                                                    &times;
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                        <div class="w-100 mt-2">
                                                            <input type="file" class="form-control"
                                                                id="description_images" name="description_images[]"
                                                                multiple>
                                                            <small class="text-muted">Có thể chọn nhiều ảnh cùng lúc</small>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Xuất xứ --}}
                                                <div class="mb-3">
                                                    <label class="form-label">Xuất xứ</label>
                                                    <input type="text" name="origin" class="form-control"
                                                        value="{{ old('origin', $product->origin) }}">
                                                    @error('origin')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                {{-- Mô tả sản phẩm --}}
                                                <div class="mb-3">
                                                    <label class="form-label">Mô tả sản phẩm</label>
                                                    <textarea name="description" id="main-description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                                                    @error('description')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- TAB 2: Phân loại & Danh mục --}}
                                            <div class="tab-pane fade" id="category" role="tabpanel"
                                                aria-labelledby="category-tab">

                                                {{-- Danh mục nhiều checkbox 3 cột --}}
                                                <div class="mb-3">
                                                    <label
                                                        class="form-label fw-bold d-flex justify-content-between align-items-center">
                                                        <span>Danh mục</span>
                                                        <button type="button" class="btn btn-link p-0"
                                                            style="color: #0da487; font-size: 0.9rem;"
                                                            data-bs-toggle="modal" data-bs-target="#addCategoryModal">+
                                                            Thêm danh mục</button>
                                                    </label>
                                                    <div class="row">
                                                        @foreach ($categories as $cat)
                                                            <div class="col-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="category_ids[]" value="{{ $cat->id }}"
                                                                        id="category-{{ $cat->id }}"
                                                                        {{ collect(old('category_ids', $product->categories->pluck('id')->toArray() ?? []))->contains($cat->id) ? 'checked' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="category-{{ $cat->id }}">
                                                                        {{ $cat->name }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @error('category_ids')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                {{-- Vùng miền radio chọn 1 --}}
                                                <div class="mb-3">
                                                    <label
                                                        class="form-label fw-bold d-flex justify-content-between align-items-center">
                                                        <span>Vùng miền</span>
                                                        <button type="button" class="btn btn-link p-0"
                                                            style="color: #0da487; font-size: 0.9rem;"
                                                            data-bs-toggle="modal" data-bs-target="#addRegionModal">+ Thêm
                                                            vùng miền</button>
                                                    </label>
                                                    <div class="row regionRadio">
                                                        @foreach ($regions as $region)
                                                            <div class="col-4">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="region_id" value="{{ $region->id }}"
                                                                        id="region-{{ $region->id }}"
                                                                        {{ old('region_id', $product->region_id) == $region->id ? 'checked' : '' }}>
                                                                    <label class="form-check-label"
                                                                        for="region-{{ $region->id }}">
                                                                        {{ $region->name }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    @error('region_id')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                            </div>

                                            {{-- TAB 3: Biến thể sản phẩm --}}
                                            <div class="tab-pane fade" id="variant" role="tabpanel"
                                                aria-labelledby="variant-tab">

                                                {{-- Chọn loại sản phẩm: Có biến thể hay không --}}
                                                <div class="mb-3">
                                                    <label class="form-label">Loại sản phẩm</label>
                                                    <div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="has_variants" id="editHasVariantsYes"
                                                                value="1"
                                                                {{ old('has_variants', $product->has_variants) == 1 ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="editHasVariantsYes">Có
                                                                biến thể</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="radio"
                                                                name="has_variants" id="editHasVariantsNo" value="0"
                                                                {{ old('has_variants', $product->has_variants) == 0 ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="editHasVariantsNo">Không
                                                                có biến thể</label>
                                                        </div>
                                                    </div>
                                                    @error('has_variants')
                                                        <small class="text-danger">{{ $message }}</small>
                                                    @enderror
                                                </div>

                                                {{-- Thông tin sản phẩm đơn nếu không có biến thể --}}
                                                <div id="edit-single-product-fields"
                                                    style="display: {{ old('has_variants', $product->has_variants) == 0 ? 'block' : 'none' }};">
                                                    <div class="card mb-3">
                                                        <div class="card-body">
                                                            <h6 class="fw-bold mb-3">Thông tin sản phẩm đơn</h6>
                                                            <div class="row gx-2 gy-2">
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Giá bán</label>
                                                                    <input type="number" name="price" min="0"
                                                                        step="0.01" class="form-control"
                                                                        value="{{ old('price', $product->variants->first()->price ?? '') }}">
                                                                    @error('price')
                                                                        <small class="text-danger">{{ $message }}</small>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Khối lượng (hiển thị ở tên
                                                                        biến thể)</label>
                                                                    <input type="text" name="variant_name"
                                                                        class="form-control"
                                                                        value="{{ old('variant_name', $product->variants->first()->name ?? '') }}"
                                                                        placeholder="Nhập khối lượng, ví dụ: 500g, 1kg...">
                                                                    @error('variant_name')
                                                                        <small class="text-danger">{{ $message }}</small>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">Tồn kho</label>
                                                                    <input type="number" name="stock" min="0"
                                                                        class="form-control"
                                                                        value="{{ old('stock', $product->variants->first()->stock ?? '') }}">
                                                                    @error('stock')
                                                                        <small class="text-danger">{{ $message }}</small>
                                                                    @enderror
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label">SKU (Mã kho)</label>
                                                                    <input type="text" name="sku"
                                                                        class="form-control"
                                                                        value="{{ old('sku', $product->variants->first()->sku ?? '') }}"
                                                                        placeholder="Tự động nếu để trống">
                                                                    @error('sku')
                                                                        <small class="text-danger">{{ $message }}</small>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- Chọn thuộc tính và giá trị cho biến thể --}}
                                                <div id="edit-variant-attribute-selection"
                                                    style="display: {{ old('has_variants', $product->has_variants) == 1 ? 'block' : 'none' }};">
                                                    <div class="card mb-3">
                                                        <div class="card-body">
                                                            <h6 class="fw-bold mb-3">Chọn thuộc tính và giá trị cho biến
                                                                thể</h6>
                                                            <input type="text" class="form-control mb-3"
                                                                id="filter-attributes" placeholder="Tìm thuộc tính...">

                                                            <div class="attribute-filters"
                                                                style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 4px;">
                                                                @foreach ($attributes as $attr)
                                                                    <div class="attribute-group mb-3"
                                                                        data-attr-name="{{ strtolower($attr->name) }}">
                                                                        <button class="btn btn-link p-0 mb-1"
                                                                            type="button" data-bs-toggle="collapse"
                                                                            data-bs-target="#attr-{{ $attr->id }}"
                                                                            aria-expanded="true"
                                                                            aria-controls="attr-{{ $attr->id }}">
                                                                            {{ $attr->name }}
                                                                            ({{ count($attr->values) }})
                                                                        </button>
                                                                        <div class="collapse show"
                                                                            id="attr-{{ $attr->id }}">
                                                                            <div class="values-list"
                                                                                style="max-height: 150px; overflow-y:auto; border:1px solid #ddd; padding:8px; border-radius:4px;">
                                                                                @foreach ($attr->values as $val)
                                                                                    @php
                                                                                        $checked = false;
                                                                                        $oldValues = old(
                                                                                            'attribute_values_checkbox.' .
                                                                                                $attr->id,
                                                                                            [],
                                                                                        );
                                                                                        if (
                                                                                            is_array($oldValues) &&
                                                                                            in_array(
                                                                                                $val->id,
                                                                                                $oldValues,
                                                                                            )
                                                                                        ) {
                                                                                            $checked = true;
                                                                                        } elseif (
                                                                                            !old('has_variants') ||
                                                                                            old('has_variants') == 1
                                                                                        ) {
                                                                                            foreach (
                                                                                                $product->variants->where(
                                                                                                    'name',
                                                                                                    '!=',
                                                                                                    'Mặc định',
                                                                                                )
                                                                                                as $variant
                                                                                            ) {
                                                                                                $valIds = $variant->attributeValues
                                                                                                    ->pluck('id')
                                                                                                    ->toArray();
                                                                                                if (
                                                                                                    in_array(
                                                                                                        $val->id,
                                                                                                        $valIds,
                                                                                                    )
                                                                                                ) {
                                                                                                    $checked = true;
                                                                                                    break;
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    @endphp
                                                                                    <label
                                                                                        class="form-check form-check-inline d-block">
                                                                                        <input
                                                                                            class="form-check-input attribute-value-checkbox"
                                                                                            type="checkbox"
                                                                                            data-attrid="{{ $attr->id }}"
                                                                                            value="{{ $val->id }}"
                                                                                            name="attribute_values_checkbox[{{ $attr->id }}][]"
                                                                                            {{ $checked ? 'checked' : '' }}>
                                                                                        <span
                                                                                            class="form-check-label">{{ $val->value }}</span>
                                                                                    </label>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>

                                                            <div class="mt-2 d-flex gap-3">
                                                                <a href="#" class="btn btn-link p-0"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#addAttributeModal">+ Thêm thuộc
                                                                    tính</a>
                                                                <a href="#" class="btn btn-link p-0"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#addAttributeValueModal">+ Thêm giá trị
                                                                    thuộc tính</a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Biến thể sản phẩm --}}
                                                    <div class="card mb-4">
                                                        <div class="card-body">
                                                            <h5 class="mb-3">Biến thể sản phẩm</h5>
                                                            <p class="text-muted small">Biến thể được sinh tự động khi chọn
                                                                các giá trị thuộc tính.</p>
                                                            <div id="variants-list">
                                                                @php
                                                                    $currentVariants = old('variants', null);
                                                                    if (is_null($currentVariants)) {
                                                                        $currentVariants = [];
                                                                        foreach (
                                                                            $product->variants->where(
                                                                                'name',
                                                                                '!=',
                                                                                'Mặc định',
                                                                            )
                                                                            as $variant
                                                                        ) {
                                                                            $currentVariants[] = [
                                                                                'id' => $variant->id,
                                                                                'price' => $variant->price,
                                                                                'stock' => $variant->stock,
                                                                                'sku' => $variant->sku,
                                                                                'name' => $variant->name,
                                                                                'description' => $variant->description,
                                                                                'image' => $variant->image,
                                                                                'active' => $variant->active,
                                                                                'attribute_value_ids' => $variant->attributeValues
                                                                                    ->pluck('id')
                                                                                    ->toArray(),
                                                                            ];
                                                                        }
                                                                    }
                                                                @endphp

                                                                @foreach ($currentVariants as $i => $variant)
                                                                    <div
                                                                        class="variant-row border rounded p-3 mb-3 bg-white position-relative">
                                                                        @foreach ($variant['attribute_value_ids'] ?? [] as $valId)
                                                                            <input type="hidden"
                                                                                name="variants[{{ $i }}][attribute_value_ids][]"
                                                                                value="{{ $valId }}">
                                                                        @endforeach
                                                                        <input type="hidden"
                                                                            name="variants[{{ $i }}][id]"
                                                                            value="{{ $variant['id'] ?? '' }}">
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 btn-remove-variant"
                                                                            title="Xóa biến thể">&times;</button>

                                                                        <div class="mb-2 fw-semibold"
                                                                            style="color: black;">
                                                                            Giá trị thuộc tính:
                                                                            <span>
                                                                                @php
                                                                                    $names = [];
                                                                                    foreach (
                                                                                        $variant[
                                                                                            'attribute_value_ids'
                                                                                        ] ?? []
                                                                                        as $vid
                                                                                    ) {
                                                                                        $attrVal = \App\Models\admin\AttributeValue::find(
                                                                                            $vid,
                                                                                        );
                                                                                        if ($attrVal) {
                                                                                            $names[] = $attrVal->value;
                                                                                        }
                                                                                    }
                                                                                    echo implode(' - ', $names);
                                                                                @endphp
                                                                            </span>
                                                                            @if (empty($names))
                                                                                <em>(biến thể thủ công)</em>
                                                                            @endif
                                                                        </div>

                                                                        <div class="row gx-2 gy-2">
                                                                            <div class="col-6 col-md-3">
                                                                                <label class="form-label">Giá bán</label>
                                                                                <input type="number"
                                                                                    name="variants[{{ $i }}][price]"
                                                                                    min="0" step="0.01"
                                                                                    class="form-control"
                                                                                    value="{{ old("variants.$i.price", $variant['price'] ?? '') }}"
                                                                                    required>
                                                                                @error("variants.$i.price")
                                                                                    <small
                                                                                        class="text-danger">{{ $message }}</small>
                                                                                @enderror
                                                                            </div>
                                                                            <div class="col-6 col-md-3">
                                                                                <label class="form-label">Tồn kho</label>
                                                                                <input type="number"
                                                                                    name="variants[{{ $i }}][stock]"
                                                                                    min="0" class="form-control"
                                                                                    value="{{ old("variants.$i.stock", $variant['stock'] ?? '') }}"
                                                                                    required>
                                                                                @error("variants.$i.stock")
                                                                                    <small
                                                                                        class="text-danger">{{ $message }}</small>
                                                                                @enderror
                                                                            </div>
                                                                            <div class="col-6 col-md-3">
                                                                                <label class="form-label">SKU</label>
                                                                                <input type="text"
                                                                                    name="variants[{{ $i }}][sku]"
                                                                                    class="form-control sku-auto" readonly
                                                                                    value="{{ old("variants.$i.sku", $variant['sku'] ?? '') }}">
                                                                                @error("variants.$i.sku")
                                                                                    <small
                                                                                        class="text-danger">{{ $message }}</small>
                                                                                @enderror
                                                                            </div>
                                                                            <div class="col-6 col-md-3">
                                                                                <input type="hidden"
                                                                                    name="variants[{{ $i }}][old_image]"
                                                                                    value="{{ old("variants.$i.old_image", $variant['image'] ?? '') }}">
                                                                            </div>
                                                                        </div>

                                                                        {{-- Hiển thị ảnh hiện tại --}}
                                                                        <div class="row mt-3">
                                                                            <div class="col-12">
                                                                                <label
                                                                                    class="form-label fw-bold text-success">Ảnh
                                                                                    hiện tại của biến thể</label>
                                                                                @php $variantImage = $variant['image'] ?? ''; @endphp
                                                                                @if (!empty($variantImage))
                                                                                    <div
                                                                                        class="d-flex justify-content-center mb-3">
                                                                                        <div class="border rounded p-3 bg-light shadow-sm"
                                                                                            style="max-width: 200px;">
                                                                                            <img src="{{ asset('storage/' . $variantImage) }}"
                                                                                                alt="Ảnh biến thể hiện tại"
                                                                                                style="width: 100%; height: 150px; object-fit: cover; border-radius: 6px;"
                                                                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block'; this.nextElementSibling.nextElementSibling.style.display='block';">
                                                                                            <div
                                                                                                style="display: none; text-align: center; color: #dc3545; font-size: 12px; padding: 20px;">
                                                                                                <i
                                                                                                    class="fas fa-exclamation-triangle"></i><br>
                                                                                                Lỗi tải ảnh<br>
                                                                                                <small>{{ $variantImage }}</small>
                                                                                            </div>
                                                                                            <div
                                                                                                style="display: none; text-align: center; color: #6c757d; font-size: 12px; padding: 20px;">
                                                                                                <i
                                                                                                    class="fas fa-info-circle"></i><br>
                                                                                                File ảnh không tồn tại<br>
                                                                                                <small>Vui lòng upload lại
                                                                                                    ảnh</small>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                @else
                                                                                    <div
                                                                                        class="d-flex justify-content-center mb-3">
                                                                                        <div class="border rounded p-3 bg-light text-center text-muted shadow-sm"
                                                                                            style="max-width: 200px;">
                                                                                            <i class="fas fa-image"
                                                                                                style="font-size: 64px; opacity: 0.3;"></i>
                                                                                            <br><small class="fw-bold">Chưa
                                                                                                có ảnh</small>
                                                                                        </div>
                                                                                    </div>
                                                                                @endif

                                                                                {{-- Thay đổi ảnh biến thể --}}
                                                                                <div class="mb-3">
                                                                                    <label class="form-label">Thay đổi ảnh
                                                                                        biến thể</label>
                                                                                    <input type="file"
                                                                                        name="variants[{{ $i }}][image]"
                                                                                        class="form-control"
                                                                                        accept="image/*">
                                                                                    <small class="text-muted">Để trống nếu
                                                                                        không muốn thay đổi ảnh</small>
                                                                                    @error("variants.$i.image")
                                                                                        <small
                                                                                            class="text-danger">{{ $message }}</small>
                                                                                    @enderror
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        {{-- Mô tả biến thể --}}
                                                                        <div class="row mt-3">
                                                                            <div class="col-12">
                                                                                <label class="form-label">Mô tả biến
                                                                                    thể</label>
                                                                                <textarea name="variants[{{ $i }}][description]" rows="2"
                                                                                    class="form-control variant-description-editor">{{ old("variants.$i.description", $variant['description'] ?? '') }}</textarea>
                                                                                @error("variants.$i.description")
                                                                                    <small
                                                                                        class="text-danger">{{ $message }}</small>
                                                                                @enderror
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Trạng thái --}}
                                            <div class="mb-3 mt-3">
                                                <label class="form-label">Kích hoạt</label>
                                                <select name="active" class="form-select">
                                                    <option value="1"
                                                        {{ old('active', $product->active) == 1 ? 'selected' : '' }}>Có
                                                    </option>
                                                    <option value="0"
                                                        {{ old('active', $product->active) == 0 ? 'selected' : '' }}>Không
                                                    </option>
                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-primary mt-2">Cập nhật sản phẩm</button>
                                    </form>

                                </div>
                            </div>
                            @includeIf('backend.footer')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @includeIf('backend.footer')
    </div>

    {{-- Modal thêm danh mục --}}
    <style>
        /* custom invalid styles */
        .input-with-icon {
            position: relative;
        }

        .input-with-icon .form-control.is-invalid {
            padding-right: 2.75rem;
        }

        /* chừa chỗ cho icon */

        /* icon "!" hiển thị khi có lỗi */
        .input-with-icon .invalid-icon {
            display: none;
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 700;
            color: #dc3545;
            /* bootstrap danger */
            pointer-events: none;
        }

        .input-with-icon.invalid .invalid-icon {
            display: block;
        }

        /* show red border on hover as user yêu cầu */
        .form-control.is-invalid:hover,
        .form-control.is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 .15rem rgba(220, 53, 69, .15);
        }

        .preview-img {
            max-width: 100%;
            max-height: 120px;
            display: block;
            margin-top: .5rem;
        }

        /* nhỏ gọn message style (Bootstrap .invalid-feedback dùng sẵn) */
        .invalid-feedback {
            display: block;
        }

        /* luôn block vì chúng ta show/hide bằng JS */
    </style>

    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="quickCategoryForm" method="POST" action="{{ route('admin.categories.storeQuick') }}"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm danh mục mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Name field -->
                        <div class="mb-3 input-with-icon" id="wrap-name">
                            <label for="cat-name" class="form-label">Tên danh mục</label>
                            <input type="text" name="name" id="cat-name" class="form-control"
                                placeholder="Tên danh mục mới" autocomplete="off">
                            <span class="invalid-icon">!</span>
                            <div class="invalid-feedback" id="error-name" style="display:none"></div>
                        </div>

                        <!-- Image field -->
                        <div class="mb-3 input-with-icon" id="wrap-image">
                            <label for="cat-image" class="form-label">Ảnh (tùy chọn)</label>
                            <input type="file" name="image" id="cat-image" accept="image/*" class="form-control">
                            <span class="invalid-icon">!</span>
                            <div class="invalid-feedback" id="error-image" style="display:none"></div>

                            <img id="imagePreview" class="preview-img" src="#" alt="preview"
                                style="display:none" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal thêm vùng miền --}}
    <div class="modal fade" id="addRegionModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="quickRegionForm" method="POST" action="{{ route('admin.regions.storeQuick') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm vùng miền mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 input-with-icon" id="wrap-region-name">
                            <label for="region-name" class="form-label">Tên vùng miền</label>
                            <input type="text" name="name" id="region-name" class="form-control"
                                placeholder="Tên vùng miền mới" autocomplete="off">
                            <div class="invalid-feedback" id="error-region-name" style="display:none"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    {{-- Modal thêm thuộc tính --}}
    <div class="modal fade" id="addAttributeModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="quickAttributeForm" method="POST" action="{{ route('admin.attributes.storeQuick') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm thuộc tính mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 input-with-icon" id="wrap-attr-name">
                            <label for="attr-name" class="form-label">Tên thuộc tính</label>
                            <input type="text" name="name" id="attr-name" class="form-control"
                                placeholder="Tên thuộc tính">
                            <div class="invalid-feedback" id="error-attr-name" style="display:none"></div>
                        </div>
                        <div class="mb-3 input-with-icon" id="wrap-attr-values">
                            <label for="attr-values" class="form-label">Giá trị (phân tách dấu phẩy)</label>
                            <input type="text" name="values" id="attr-values" class="form-control"
                                placeholder="Giá trị (vd: Đỏ, Xanh)">
                            <div class="invalid-feedback" id="error-attr-values" style="display:none"></div>
                            <small class="text-muted">VD: 1kg, 2kg, 3kg</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Thêm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal thêm giá trị thuộc tính --}}
    <div class="modal fade" id="addAttributeValueModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="quickAttributeValueForm" method="POST" action="{{ route('admin.attribute_values.storeQuick') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm giá trị thuộc tính mới</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 input-with-icon" id="wrap-attr-select">
                            <label for="attr-select" class="form-label">Chọn thuộc tính</label>
                            <select name="attribute_id" id="attr-select" class="form-select mb-2">
                                <option value="">-- Chọn thuộc tính --</option>
                                @foreach ($attributes as $attr)
                                    <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-icon">!</span>
                            <div class="invalid-feedback" id="error-attr-select" style="display:none"></div>
                        </div>
                        <div class="mb-3 input-with-icon" id="wrap-attr-value">
                            <label for="attr-value" class="form-label">Giá trị mới</label>
                            <input type="text" name="value" id="attr-value" class="form-control"
                                placeholder="Nhập giá trị mới">
                            <span class="invalid-icon">!</span>
                            <div class="invalid-feedback" id="error-attr-value" style="display:none"></div>
                            <small class="text-muted">VD: Đỏ, Xanh, 1kg,...</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Thêm giá trị</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <style>
        /* CKEditor chữ đen */
        .ck-editor__editable {
            color: #222 !important;
            background: #fff;
        }

        /* Bố cục card biến thể */
        .variant-row {
            background: #fffbe7;
            border: 2px solid #ffd966;
            border-radius: 12px;
            margin-bottom: 36px;
            padding: 20px 18px;
            box-shadow: 0 4px 16px #ffd96633;
        }

        .variant-row:not(:last-child) {
            margin-bottom: 48px;
        }

        .variant-row label {
            color: #212529 !important;
        }

        .sku-auto {
            background-color: #f8f9fa;
        }

        .bulk-delete-btn[disabled] {
            background: #e1e8f3 !important;
            color: #66708a !important;
            border: none !important;
            cursor: not-allowed !important;
            opacity: 1 !important;
        }

        .bulk-delete-btn[disabled] .delete-bulk-icon {
            color: #66708a !important;
        }

        .bulk-delete-btn:not([disabled]) {
            background: #becde4 !important;
            color: #495057 !important;
            border: none !important;
            cursor: pointer !important;
            box-shadow: 0 2px 8px #becde480;
            transition: background .5s, color .5s;
        }

        .bulk-delete-btn:not([disabled]) .delete-bulk-icon {
            color: #495057 !important;
        }

        .bulk-delete-btn:not([disabled]):hover {
            background: #aac4e7 !important;
        }

        .delete-image-x-btn {
            width: 36px;
            height: 36px;
            font-size: 20px;
            line-height: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            top: -18px;
            right: -18px;
            z-index: 999999;
            cursor: pointer;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
            background-color: rgba(255, 0, 0, 0.9);
            pointer-events: auto;
        }
    </style>
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Biến lưu editor chính và editor biến thể
            let mainEditor = null;
            let variantEditors = [];

            // Đăng ký ajax form cho từng modal
            // Ajax form thêm nhanh danh mục
            document.getElementById('quickCategoryForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = this;
                const url = form.action;
                const formData = new FormData(form);

                // DOM elements
                const nameInput = document.getElementById('cat-name');
                const imageInput = document.getElementById('cat-image');
                const imagePreview = document.getElementById('imagePreview');

                // helper: clear previous UI errors
                function clearErrors() {
                    ['name', 'image'].forEach(field => {
                        const input = (field === 'name') ? document.getElementById('cat-name') :
                            document.getElementById('cat-image');
                        const errDiv = document.getElementById('error-' + field);
                        if (input) input.classList.remove('is-invalid');
                        if (errDiv) {
                            errDiv.style.display = 'none';
                            errDiv.innerText = '';
                        }
                    });
                }

                // helper: show field error (messages can be array or string)
                function showFieldError(field, messages) {
                    const input = (field === 'name') ? document.getElementById('cat-name') : document
                        .getElementById('cat-image');
                    const errDiv = document.getElementById('error-' + field);
                    if (input) input.classList.add('is-invalid');
                    if (errDiv) {
                        errDiv.style.display = 'block';
                        errDiv.innerText = Array.isArray(messages) ? messages.join(' ') : messages;
                    }
                }

                clearErrors();

                // Client-side quick validation (optional but improves UX)
                const nameVal = nameInput ? nameInput.value.trim() : '';
                if (!nameVal) {
                    showFieldError('name', 'Tên danh mục bắt buộc.');
                    nameInput.focus();
                    return;
                }

                // client-side image checks (optional)
                if (imageInput && imageInput.files && imageInput.files[0]) {
                    const file = imageInput.files[0];
                    const maxSize = 2 * 1024 * 1024; // 2MB
                    if (file.size > maxSize) {
                        showFieldError('image', 'Kích thước ảnh không được vượt quá 2MB.');
                        return;
                    }
                    // (optionally) check mime types
                    const allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml'];
                    if (!allowed.includes(file.type)) {
                        showFieldError('image', 'Ảnh phải có định dạng: jpeg,png,jpg,gif,svg.');
                        return;
                    }
                }

                // show preview immediately (optional)
                if (imageInput && imageInput.files && imageInput.files[0] && imagePreview) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        imagePreview.src = ev.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(imageInput.files[0]);
                }

                // send AJAX
                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': formData.get('_token'),
                        },
                        body: formData, // FormData includes file automatically
                    })
                    .then(async res => {
                        if (!res.ok) {
                            if (res.status === 422) {
                                const data = await res.json();
                                // data.errors expected { field: [messages...] }
                                return Promise.reject(data);
                            }
                            // other server error
                            const text = await res.text();
                            return Promise.reject({
                                message: 'Lỗi server',
                                detail: text
                            });
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (data.success && data.category) {
                            // hide modal (supports bootstrap 5)
                            const modalEl = document.getElementById('addCategoryModal');
                            const bsModal = bootstrap.Modal.getInstance(modalEl) || bootstrap.Modal
                                .getOrCreateInstance(modalEl);
                            if (bsModal) bsModal.hide();

                            // append new category (checkbox) like bạn muốn
                            const container = document.querySelector('#category .row');
                            if (container) {
                                const newCat = data.category;
                                const div = document.createElement('div');
                                div.className = 'col-4';
                                div.innerHTML = `
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="category_ids[]" value="${newCat.id}" id="category-${newCat.id}" checked>
            <label class="form-check-label" for="category-${newCat.id}">${newCat.name}</label>
          </div>
        `;
                                container.appendChild(div);
                            }

                            // reset form UI
                            form.reset();
                            if (imagePreview) {
                                imagePreview.style.display = 'none';
                                imagePreview.src = '#';
                            }
                        } else {
                            alert(data.message || 'Có lỗi xảy ra');
                        }
                    })
                    .catch(err => {
                        // err.errors từ server validation
                        if (err && err.errors) {
                            // map server fields to inputs
                            Object.keys(err.errors).forEach(field => {
                                // server field keys thường 'name' hoặc 'image'
                                showFieldError(field, err.errors[field]);
                            });
                        } else {
                            console.error(err);
                            alert(err.message || 'Lỗi server, thử lại sau.');
                        }
                    });
            });

            // Tương tự ajax form thêm nhanh vùng miền (radio)
            document.getElementById('quickRegionForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = this;
                const url = form.action;
                const formData = new FormData(form);

                // DOM
                const nameInput = document.getElementById('region-name');
                const errDiv = document.getElementById('error-region-name');
                const wrap = document.getElementById('wrap-region-name');

                // Clear lỗi cũ
                function clearError() {
                    nameInput.classList.remove('is-invalid');
                    wrap.classList.remove('invalid');
                    errDiv.style.display = 'none';
                    errDiv.innerText = '';
                }

                function showError(msgs) {
                    const txt = Array.isArray(msgs) ? msgs.join(' ') : (msgs || 'Dữ liệu không hợp lệ.');
                    nameInput.classList.add('is-invalid');
                    wrap.classList.add('invalid');
                    errDiv.innerText = txt;
                    errDiv.style.display = 'block';
                }

                clearError();

                // Kiểm tra client (có thể bỏ qua)
                const val = (nameInput.value || '').trim();
                if (!val) {
                    showError('Tên vùng miền bắt buộc.');
                    nameInput.focus();
                    return;
                }

                // Gửi AJAX
                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': formData.get('_token'),
                        },
                        body: formData,
                    })
                    .then(async res => {
                        if (!res.ok) {
                            if (res.status === 422) {
                                const data = await res.json();
                                // Dạng { errors: { name: [...] } }
                                return Promise.reject(data);
                            }
                            // Lỗi khác
                            const text = await res.text();
                            return Promise.reject({
                                message: 'Lỗi server',
                                detail: text
                            });
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (data.success && data.region) {
                            // Hide modal
                            const modalEl = document.getElementById('addRegionModal');
                            const bsModal = bootstrap.Modal.getInstance(modalEl) || bootstrap.Modal
                                .getOrCreateInstance(modalEl);
                            if (bsModal) bsModal.hide();

                            // Thêm radio mới
                            const container = document.querySelector('.regionRadio');
                            if (container) {
                                const newRegion = data.region;
                                const div = document.createElement('div');
                                div.className = 'col-4';
                                div.innerHTML = `
          <div class="form-check">
            <input class="form-check-input" type="radio" name="region_id" value="${newRegion.id}" id="region-${newRegion.id}" checked>
            <label class="form-check-label" for="region-${newRegion.id}">${newRegion.name}</label>
          </div>
        `;
                                container.appendChild(div);
                            }

                            // Reset form
                            form.reset();
                        } else {
                            alert(data.message || 'Có lỗi xảy ra');
                        }
                    })
                    .catch(err => {
                        // Lỗi validate server
                        if (err && err.errors && err.errors.name) {
                            showError(err.errors.name);
                        } else {
                            alert(err.message || 'Lỗi server, thử lại sau.');
                            console.error(err);
                        }
                    });
            });

            // Clear lỗi khi user gõ lại
            document.getElementById('region-name').addEventListener('input', function() {
                this.classList.remove('is-invalid');
                document.getElementById('wrap-region-name').classList.remove('invalid');
                document.getElementById('error-region-name').style.display = 'none';
                document.getElementById('error-region-name').innerText = '';
            });
            // them nhanh tt và gttt

            function showToast(msg, icon = 'success') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2200,
                    timerProgressBar: true,
                    icon,
                    title: msg,
                    customClass: {
                        popup: 'swal2-toast'
                    }
                });
            }

            // --- Thêm thuộc tính mới
            const attrForm = document.getElementById('quickAttributeForm');
            const attrName = document.getElementById('attr-name');
            const attrValues = document.getElementById('attr-values');
            const wrapAttrName = document.getElementById('wrap-attr-name');
            const wrapAttrValues = document.getElementById('wrap-attr-values');
            const errAttrName = document.getElementById('error-attr-name');
            const errAttrValues = document.getElementById('error-attr-values');

            function clearAttrErrors() {
                [attrName, attrValues].forEach(i => i.classList.remove('is-invalid'));
                [wrapAttrName, wrapAttrValues].forEach(w => w.classList.remove('invalid'));
                [errAttrName, errAttrValues].forEach(e => {
                    e.textContent = '';
                    e.style.display = 'none';
                });
            }
            [attrName, attrValues].forEach(i => i.addEventListener('input', clearAttrErrors));

            attrForm.addEventListener('submit', function(e) {
                e.preventDefault();
                clearAttrErrors();
                const data = {
                    name: attrName.value.trim(),
                    values: attrValues.value.trim(),
                    _token: this.querySelector('input[name="_token"]').value
                };
                fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json'
                        },
                        body: new URLSearchParams(data)
                    })
                    .then(res => res.json().then(json => ({
                        status: res.status,
                        json
                    })))
                    .then(({
                        status,
                        json
                    }) => {
                        if (status === 422) {
                            if (json.errors.name) {
                                attrName.classList.add('is-invalid');
                                wrapAttrName.classList.add('invalid');
                                errAttrName.textContent = json.errors.name.join(' ');
                                errAttrName.style.display = '';
                            }
                            if (json.errors.values) {
                                attrValues.classList.add('is-invalid');
                                wrapAttrValues.classList.add('invalid');
                                errAttrValues.textContent = json.errors.values.join(' ');
                                errAttrValues.style.display = '';
                            }
                        } else if (json.success) {
                            attrForm.reset();
                            clearAttrErrors();
                            // Đóng modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'addAttributeModal'));
                            modal.hide();
                            showToast(json.message || 'Đã thêm thuộc tính!');
                            // --- RENDER thuộc tính mới vào .attribute-filters nếu có
                            if (json.attribute && Array.isArray(json.attributeValues) && json
                                .attributeValues.length > 0) {
                                renderAttributeGroup(json.attribute, json.attributeValues);
                            }
                        } else {
                            showToast('Có lỗi xảy ra!', 'error');
                        }
                    })
                    .catch(() => showToast('Không thể gửi dữ liệu.', 'error'));
            });

            // --- Thêm giá trị thuộc tính
            const valueForm = document.getElementById('quickAttributeValueForm');
            const attrSelect = document.getElementById('attr-select');
            const attrValue = document.getElementById('attr-value');
            const wrapAttrSelect = document.getElementById('wrap-attr-select');
            const wrapAttrValue = document.getElementById('wrap-attr-value');
            const errAttrSelect = document.getElementById('error-attr-select');
            const errAttrValue = document.getElementById('error-attr-value');

            function clearValueErrors() {
                [attrSelect, attrValue].forEach(i => i.classList.remove('is-invalid'));
                [wrapAttrSelect, wrapAttrValue].forEach(w => w.classList.remove('invalid'));
                [errAttrSelect, errAttrValue].forEach(e => {
                    e.textContent = '';
                    e.style.display = 'none';
                });
            }
            [attrSelect, attrValue].forEach(i => i.addEventListener('input', clearValueErrors));

            valueForm.addEventListener('submit', function(e) {
                e.preventDefault();
                clearValueErrors();
                const data = {
                    attribute_id: attrSelect.value,
                    value: attrValue.value.trim(),
                    _token: this.querySelector('input[name="_token"]').value
                };
                fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json'
                        },
                        body: new URLSearchParams(data)
                    })
                    .then(res => res.json().then(json => ({
                        status: res.status,
                        json
                    })))
                    .then(({
                        status,
                        json
                    }) => {
                        if (status === 422) {
                            if (json.errors.attribute_id) {
                                attrSelect.classList.add('is-invalid');
                                wrapAttrSelect.classList.add('invalid');
                                errAttrSelect.textContent = json.errors.attribute_id.join(' ');
                                errAttrSelect.style.display = '';
                            }
                            if (json.errors.value) {
                                attrValue.classList.add('is-invalid');
                                wrapAttrValue.classList.add('invalid');
                                errAttrValue.textContent = json.errors.value.join(' ');
                                errAttrValue.style.display = '';
                            }
                        } else if (json.success) {
                            valueForm.reset();
                            clearValueErrors();
                            // Đóng modal
                            const modal = bootstrap.Modal.getInstance(document.getElementById(
                                'addAttributeValueModal'));
                            modal.hide();
                            showToast('Đã thêm giá trị thuộc tính!');
                            // --- RENDER giá trị mới vào .attribute-filters
                            if (json.attribute_id && Array.isArray(json.attributeValues) && json
                                .attributeValues.length > 0) {
                                renderAttributeValues(json.attribute_id, json.attribute_name, json
                                    .attributeValues);
                            }
                        } else {
                            showToast('Có lỗi xảy ra!', 'error');
                        }
                    })
                    .catch(() => showToast('Không thể gửi dữ liệu.', 'error'));
            });

            // ---- RENDER THUỘC TÍNH và GIÁ TRỊ mới ra .attribute-filters ----

            function renderAttributeGroup(attr, attrValues) {
                // Nếu thuộc tính đã tồn tại thì không render nữa (tuỳ logic, có thể update)
                if (document.querySelector('.attribute-group[data-attr-name="' + attr.name.toLowerCase() + '"]')) {
                    renderAttributeValues(attr.id, attr.name, attrValues);
                    return;
                }
                const group = document.createElement('div');
                group.className = 'attribute-group mb-3';
                group.setAttribute('data-attr-name', attr.name.toLowerCase());
                group.innerHTML = `
      <button class="btn btn-link p-0 mb-1" type="button" data-bs-toggle="collapse" data-bs-target="#attr-${attr.id}" aria-expanded="true" aria-controls="attr-${attr.id}">
        ${attr.name} (${attrValues.length})
      </button>
      <div class="collapse show" id="attr-${attr.id}">
        <div class="values-list" style="max-height: 150px; overflow-y:auto; border:1px solid #ddd; padding:8px; border-radius:4px;">
          ${attrValues.map(val => `
                        <label class="form-check form-check-inline d-block">
                          <input class="form-check-input attribute-value-checkbox" type="checkbox" data-attrid="${attr.id}" value="${val.id}" name="attribute_values_checkbox[${attr.id}][]">
                          <span class="form-check-label">${val.value}</span>
                        </label>
                      `).join('')}
        </div>
      </div>
    `;
                document.querySelector('.attribute-filters').appendChild(group);

                // Thêm vào select của modal thêm giá trị thuộc tính (nếu chưa có)
                if (!Array.from(attrSelect.options).some(o => o.value == attr.id)) {
                    const opt = document.createElement('option');
                    opt.value = attr.id;
                    opt.textContent = attr.name;
                    attrSelect.appendChild(opt);
                }
            }

            function renderAttributeValues(attribute_id, attribute_name, attributeValues) {
                // Tìm đúng .attribute-group để append value mới
                const group = document.querySelector(`.attribute-group [data-bs-target="#attr-${attribute_id}"]`);
                const list = document.querySelector(`#attr-${attribute_id} .values-list`);
                if (!group || !list) {
                    // Nếu chưa có thuộc tính này => tạo mới
                    renderAttributeGroup({
                        id: attribute_id,
                        name: attribute_name
                    }, attributeValues);
                    return;
                }
                // Thêm từng value (nếu chưa có)
                attributeValues.forEach(val => {
                    if (!list.querySelector('input[value="' + val.id + '"]')) {
                        const label = document.createElement('label');
                        label.className = 'form-check form-check-inline d-block';
                        label.innerHTML = `
          <input class="form-check-input attribute-value-checkbox" type="checkbox" data-attrid="${attribute_id}" value="${val.id}" name="attribute_values_checkbox[${attribute_id}][]" checked>
          <span class="form-check-label">${val.value}</span>
        `;
                        list.appendChild(label);
                    }
                });
                // Update text số lượng giá trị trên button
                const btn = document.querySelector(`.attribute-group [data-bs-target="#attr-${attribute_id}"]`);
                if (btn) {
                    const cnt = list.querySelectorAll('input').length;
                    btn.innerHTML = `${attribute_name} (${cnt})`;
                }
            }

            // Lấy dữ liệu CKEditor biến thể hiện tại trước khi destroy
            function collectCurrentVariantsData() {
                const variantsData = {};
                document.querySelectorAll('.variant-row').forEach(row => {
                    // Lấy attribute_value_ids rồi sort join thành key
                    const attrIdInputs = row.querySelectorAll('input[name*="[attribute_value_ids]"]');
                    const key = Array.from(attrIdInputs).map(i => i.value).sort().join('-');

                    // Lấy dữ liệu các trường
                    const price = row.querySelector('input[name$="[price]"]')?.value || '';
                    const stock = row.querySelector('input[name$="[stock]"]')?.value || '';
                    const sku = row.querySelector('input[name$="[sku]"]')?.value || '';
                    const descriptionEditor = variantEditors.find(ed => ed.sourceElement === row
                        .querySelector('textarea.variant-description-editor'));
                    const description = descriptionEditor ? descriptionEditor.getData() : (row
                        .querySelector('textarea.variant-description-editor')?.value || '');
                    const id = row.querySelector('input[name$="[id]"]')?.value || '';
                    const oldImage = row.querySelector('input[name$="[old_image]"]')?.value || '';

                    variantsData[key] = {
                        price,
                        stock,
                        sku,
                        description,
                        id,
                        oldImage
                    };
                });
                return variantsData;
            }

            // Xóa CKEditor biến thể cũ trước khi rebuild
            function destroyVariantEditors() {
                variantEditors.forEach(editor => editor.destroy());
                variantEditors = [];
                document.querySelectorAll('.variant-description-editor').forEach(textarea => {
                    textarea.classList.remove('ck-editor-initialized');
                });
            }

            // Khởi tạo CKEditor biến thể
            function initVariantEditors() {
                document.querySelectorAll('.variant-description-editor').forEach(textarea => {
                    if (!textarea.classList.contains('ck-editor-initialized')) {
                        ClassicEditor.create(textarea).then(editor => {
                            variantEditors.push(editor);
                            textarea.classList.add('ck-editor-initialized');
                            editor.ui.view.editable.element.style.color = '#222';
                            editor.ui.view.editable.element.style.background = '#fff';
                        });
                    }
                });
            }

            // Khởi tạo CKEditor chính
            function initMainEditor() {
                const mainDesc = document.getElementById('main-description');
                if (mainDesc && !mainDesc.classList.contains('ck-editor-initialized')) {
                    ClassicEditor.create(mainDesc).then(editor => {
                        mainEditor = editor;
                        editor.ui.view.editable.element.style.color = '#222';
                        editor.ui.view.editable.element.style.background = '#fff';
                        mainDesc.classList.add('ck-editor-initialized');
                    });
                }
            }

            // Hàm tính tích Descartes (Cartesian product)
            function cartesianProduct(arr) {
                return arr.reduce((a, b) =>
                    a.flatMap(d => b.map(e => [...d, e])), [
                        []
                    ]);
            }

            // Hiển thị/ẩn nút xóa biến thể nếu có nhiều hơn 1 biến thể
            function showRemoveButtons() {
                const rows = document.querySelectorAll('.variant-row');
                rows.forEach(row => {
                    const btn = row.querySelector('.btn-remove-variant');
                    if (btn) btn.style.display = rows.length > 1 ? 'inline-block' : 'none';
                });
            }

            // Tự động sinh SKU
            function updateSkuAll() {
                const rows = document.querySelectorAll('.variant-row');
                const timestamp = Date.now();
                rows.forEach((row, index) => {
                    const skuInput = row.querySelector('.sku-auto');
                    const attrSpan = row.querySelector('div.mb-2 span');
                    if (skuInput) {
                        let sku = '';
                        if (attrSpan) {
                            const suffix = attrSpan.textContent.trim().replace(/\s+/g, '').toUpperCase()
                                .substring(0, 6);
                            sku = `PRD-${timestamp}-${suffix}-${index}`;
                        } else {
                            sku = `PRD-${timestamp}-MANUAL-${Math.floor(Math.random() * 1000)}`;
                        }
                        skuInput.value = sku;
                    }
                });
            }

            // Đăng ký sự kiện thay đổi checkbox để sinh biến thể
            function registerCheckboxListeners() {
                document.querySelectorAll('.attribute-value-checkbox').forEach(cb => {
                    cb.removeEventListener('change', generateVariants);
                    cb.addEventListener('change', generateVariants);
                });
            }

            // Hàm sinh biến thể dựa trên checkbox được chọn, giữ dữ liệu biến thể cũ
            function generateVariants() {
                const checkboxes = document.querySelectorAll('.attribute-value-checkbox');
                let attrMap = {};

                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        const attrId = cb.dataset.attrid;
                        if (!attrMap[attrId]) attrMap[attrId] = [];
                        attrMap[attrId].push({
                            id: cb.value,
                            text: cb.nextElementSibling.textContent.trim()
                        });
                    }
                });

                const variantArea = document.getElementById('variants-list');
                if (!variantArea) return;

                // Lưu dữ liệu biến thể đang có trước khi xóa
                const oldVariantsData = collectCurrentVariantsData();

                destroyVariantEditors(); // Xóa editor cũ
                variantArea.innerHTML = ''; // Xóa biến thể cũ

                const attrArrays = Object.values(attrMap);
                if (attrArrays.length === 0) {
                    // Nếu không có thuộc tính nào được chọn, hiển thị lại biến thể mặc định nếu có hoặc tạo rỗng
                    const defaultVariant = {!! $product->variants->where('name', 'Mặc định')->first()
                        ? json_encode($product->variants->where('name', 'Mặc định')->first()->toArray())
                        : 'null' !!};
                    const oldHasVariants = '{{ old('has_variants', $product->has_variants) }}';

                    if (defaultVariant && oldHasVariants == 0) {
                        const html = `
                    <div class="variant-row border rounded p-3 mb-3 bg-white position-relative">
                      <input type="hidden" name="variants[0][id]" value="${defaultVariant.id}">
                      <div class="mb-3 p-2">
                          <strong>Giá trị thuộc tính:</strong>
                          <span class="fw-bold">Mặc định</span>
                      </div>
                      <div class="row gx-2 gy-2">
                        <div class="col-6 col-md-3">
                          <label class="form-label">Giá bán</label>
                          <input type="number" name="variants[0][price]" min="0" step="0.01" class="form-control" value="${defaultVariant.price}" required>
                        </div>
                        <div class="col-6 col-md-3">
                          <label class="form-label">Tồn kho</label>
                          <input type="number" name="variants[0][stock]" min="0" class="form-control" value="${defaultVariant.stock}" required>
                        </div>
                        <div class="col-6 col-md-3">
                          <label class="form-label">SKU</label>
                          <input type="text" name="variants[0][sku]" class="form-control sku-auto" readonly value="${defaultVariant.sku}">
                        </div>
                        <div class="col-6 col-md-3">
                          <label class="form-label">Ảnh biến thể</label>
                          ${defaultVariant.image ? `
                                            <div class="mb-2">
                                              <label class="form-label text-muted small">Ảnh hiện tại:</label>
                                              <div class="border rounded p-2 bg-light">
                                                <img src="{{ asset('storage/') }}/${defaultVariant.image}" alt="Ảnh biến thể hiện tại" style="max-width: 120px; max-height: 120px; object-fit: cover; border-radius: 4px;">
                                              </div>
                                            </div>
                                          ` : `
                                            <div class="mb-2">
                                              <label class="form-label text-muted small">Ảnh hiện tại:</label>
                                              <div class="border rounded p-2 bg-light text-center text-muted">
                                                <i class="fas fa-image" style="font-size: 48px; opacity: 0.3;"></i>
                                                <br><small>Chưa có ảnh</small>
                                              </div>
                                            </div>
                                          `}
                          <input type="hidden" name="variants[0][old_image]" value="${defaultVariant.image || ''}">
                          <label class="form-label">Thay đổi ảnh biến thể</label>
                          <input type="file" name="variants[0][image]" class="form-control" accept="image/*">
                          <small class="text-muted">Để trống nếu không muốn thay đổi ảnh</small>
                        </div>
                      </div>
                      <div class="col-12">
                        <label class="form-label">Mô tả biến thể</label>
                        <textarea name="variants[0][description]" rows="2" class="form-control variant-description-editor">${defaultVariant.description || ''}</textarea>
                      </div>
                    </div>
                `;
                        variantArea.insertAdjacentHTML('beforeend', html);
                    }

                    showRemoveButtons();
                    initVariantEditors();
                    updateSkuAll();
                    return;
                }

                const variantsComb = cartesianProduct(attrArrays);

                variantsComb.forEach((combo, idx) => {
                    const ids = combo.map(c => c.id);
                    const names = combo.map(c => c.text);
                    const key = ids.slice().sort().join('-');

                    // Lấy dữ liệu cũ từ object đã lưu nếu có
                    let oldPrice = '';
                    let oldStock = '';
                    let oldSku = '';
                    let oldImage = '';
                    let oldDescription = '';
                    let oldVariantId = '';

                    if (oldVariantsData[key]) {
                        oldPrice = oldVariantsData[key].price;
                        oldStock = oldVariantsData[key].stock;
                        oldSku = oldVariantsData[key].sku;
                        oldImage = oldVariantsData[key].oldImage;
                        oldDescription = oldVariantsData[key].description;
                        oldVariantId = oldVariantsData[key].id;
                    } else {
                        // fallback tìm biến thể cũ trong $product->variants
                        const existingVariant = {!! json_encode($product->variants->where('name', '!=', 'Mặc định')->values()->toArray()) !!}.find(v => {
                            const existingIds = (v.attribute_values || []).map(av => av.id
                                .toString());
                            return existingIds.sort().join('-') === key;
                        });
                        if (existingVariant) {
                            oldPrice = existingVariant.price || '';
                            oldStock = existingVariant.stock || '';
                            oldSku = existingVariant.sku || '';
                            oldImage = existingVariant.image || '';
                            oldDescription = existingVariant.description || '';
                            oldVariantId = existingVariant.id || '';
                        }

                        // ưu tiên dữ liệu old input nếu có lỗi validation
                        @php
                            $oldVariantsInput = old('variants', []);
                        @endphp
                        if (Object.keys({!! json_encode($oldVariantsInput) !!}).length > 0) {
                            const oldInputVariant = {!! json_encode($oldVariantsInput) !!}.find(v => {
                                const oldInputIds = (v.attribute_value_ids || []).map(id => id
                                    .toString());
                                return oldInputIds.sort().join('-') === key;
                            });
                            if (oldInputVariant) {
                                oldPrice = oldInputVariant.price || oldPrice;
                                oldStock = oldInputVariant.stock || oldStock;
                                oldSku = oldInputVariant.sku || oldSku;
                                oldImage = oldInputVariant.old_image || oldInputVariant.image || oldImage;
                                oldDescription = oldInputVariant.description || oldDescription;
                                oldVariantId = oldInputVariant.id || oldVariantId;
                            }
                        }
                    }

                    const html = `
            <div class="variant-row border rounded p-3 mb-3 bg-white position-relative">
                ${ids.map(id => `<input type="hidden" name="variants[${idx}][attribute_value_ids][]" value="${id}">`).join('')}
                <input type="hidden" name="variants[${idx}][id]" value="${oldVariantId}">
                <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 btn-remove-variant" title="Xóa biến thể">&times;</button>
                <div class="mb-3 p-2   text-dark rounded">
                    <strong>Giá trị thuộc tính:</strong>
                    <span class="fw-bold">${names.join(' - ')}</span>
                </div>
                <div class="row gx-2 gy-2">
                    <div class="col-6 col-md-3">
                        <label class="form-label">Giá bán</label>
                        <input type="number" name="variants[${idx}][price]" min="0" step="0.01" class="form-control" value="${oldPrice}" required>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Tồn kho</label>
                        <input type="number" name="variants[${idx}][stock]" min="0" class="form-control" value="${oldStock}" required>
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">SKU</label>
                        <input type="text" name="variants[${idx}][sku]" class="form-control sku-auto" readonly value="${oldSku}">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Ảnh biến thể</label>
                        ${oldImage ? `
                                        <div class="mb-2">
                                            <label class="form-label text-muted small">Ảnh hiện tại:</label>
                                            <div class="border rounded p-2 bg-light">
                                                <img src="{{ asset('storage/') }}/${oldImage}" alt="Ảnh biến thể hiện tại" style="max-width: 120px; max-height: 120px; object-fit: cover; border-radius: 4px;">
                                            </div>
                                        </div>
                                    ` : `
                                        <div class="mb-2">
                                            <label class="form-label text-muted small">Ảnh hiện tại:</label>
                                            <div class="border rounded p-2 bg-light text-center text-muted">
                                                <i class="fas fa-image" style="font-size: 48px; opacity: 0.3;"></i>
                                                <br><small>Chưa có ảnh</small>
                                            </div>
                                        </div>
                                    `}
                        <input type="hidden" name="variants[${idx}][old_image]" value="${oldImage}">
                        <label class="form-label">Thay đổi ảnh biến thể</label>
                        <input type="file" name="variants[${idx}][image]" class="form-control" accept="image/*">
                        <small class="text-muted">Để trống nếu không muốn thay đổi ảnh</small>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Mô tả biến thể</label>
                        <textarea name="variants[${idx}][description]" rows="2" class="form-control variant-description-editor">${oldDescription}</textarea>
                    </div>
                </div>
            </div>
            `;

                    variantArea.insertAdjacentHTML('beforeend', html);
                });

                showRemoveButtons();
                initVariantEditors();
                updateSkuAll();
            }

            // Toggle hiển thị phần sản phẩm đơn/biến thể
            function toggleEditProductTypeFields() {
                const hasVariantsYesRadio = document.getElementById('editHasVariantsYes');
                const hasVariantsNoRadio = document.getElementById('editHasVariantsNo');
                const hasVariants = hasVariantsYesRadio.checked;

                const singleProductFields = document.getElementById('edit-single-product-fields');
                const variantAttributeSelection = document.getElementById('edit-variant-attribute-selection');
                const variantsList = document.getElementById('variants-list');

                if (hasVariants) {
                    singleProductFields.style.display = 'none';
                    variantAttributeSelection.style.display = 'block';

                    // Disable inputs đơn sản phẩm
                    singleProductFields.querySelectorAll('input, select, textarea').forEach(el => {
                        el.setAttribute('disabled', 'true');
                        el.removeAttribute('required');
                    });

                    // Enable inputs biến thể
                    variantAttributeSelection.querySelectorAll('input, select, textarea').forEach(el => {
                        el.removeAttribute('disabled');
                    });

                    if (variantsList && variantsList.innerHTML.trim() === '') {
                        generateVariants();
                    }

                    initVariantEditors();
                    registerCheckboxListeners();
                } else {
                    singleProductFields.style.display = 'block';
                    variantAttributeSelection.style.display = 'none';

                    // Enable inputs đơn sản phẩm
                    singleProductFields.querySelectorAll('input, select, textarea').forEach(el => {
                        el.removeAttribute('disabled');
                        if (el.name === 'price' || el.name === 'stock') {
                            el.setAttribute('required', 'true');
                        }
                    });

                    // Disable inputs biến thể
                    variantAttributeSelection.querySelectorAll('input, select, textarea').forEach(el => {
                        el.setAttribute('disabled', 'true');
                        el.removeAttribute('required');
                    });

                    destroyVariantEditors();
                }
            }

            // Xử lý submit form chính
            const mainForm = document.getElementById('main-form');
            const mainDesc = document.getElementById('main-description');
            if (mainForm) {
                mainForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (mainEditor && mainDesc) {
                        mainDesc.value = mainEditor.getData();
                    }

                    const formData = new FormData(this);

                    fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => Promise.reject(err));
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                toastr.success('Cập nhật sản phẩm thành công!');
                                setTimeout(() => {
                                    window.location.href =
                                        '{{ route('admin.products.index') }}';
                                }, 1500);
                            } else {
                                toastr.error(data.message || 'Có lỗi xảy ra khi cập nhật sản phẩm!');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            if (error.errors) {
                                Object.keys(error.errors).forEach(key => {
                                    toastr.error(error.errors[key][0]);
                                });
                            } else {
                                toastr.error(error.message || 'Có lỗi xảy ra khi cập nhật sản phẩm!');
                            }
                        });
                });
            }

            // Xóa biến thể khi click nút xóa
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-remove-variant')) {
                    e.target.closest('.variant-row').remove();
                    showRemoveButtons();
                    updateSkuAll();
                }
            });

            // Đăng ký sự kiện checkbox thuộc tính
            registerCheckboxListeners();

            // Khởi tạo editor chính
            initMainEditor();

            // Toggle hiển thị ban đầu
            toggleEditProductTypeFields();

            // Hiển thị nút xóa biến thể và cập nhật SKU
            showRemoveButtons();
            updateSkuAll();

            // Đăng ký event cho radio biến thể
            document.getElementById('editHasVariantsYes').addEventListener('change', toggleEditProductTypeFields);
            document.getElementById('editHasVariantsNo').addEventListener('change', toggleEditProductTypeFields);

        });
    </script>


    <script>
        $(document).ready(function() {
            // DIAGNOSTIC SCRIPT: Attempt to re-bind click event directly after a small delay
            setTimeout(function() {
                $('.product-image-delete-btn').each(function() {
                    var button = $(this);
                    // First, ensure no previous handlers are interfering if possible (though .on() should handle this)
                    button.off('click').on('click', function(e) {
                        e.preventDefault();
                        console.log('DIAGNOSTIC: Direct button click detected!', button
                            .data('id')); // This MUST show if clickable

                        var imageId = button.data('id');
                        var url = button.data('url');

                        if (confirm('Bạn có chắc muốn xóa ảnh này?')) {
                            $.ajax({
                                url: url,
                                type: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    if (response.success) {
                                        button.closest('.image-item').remove();
                                        alert('Xóa ảnh thành công!');
                                    } else {
                                        alert('Có lỗi xảy ra khi xóa ảnh!');
                                    }
                                },
                                error: function(xhr) {
                                    console.error(
                                        "Error deleting image (DIAGNOSTIC): ",
                                        xhr);
                                    alert(
                                        'Có lỗi xảy ra khi xóa ảnh. Vui lòng thử lại!'
                                    );
                                }
                            });
                        }
                    });
                });
                console.log('DIAGNOSTIC: Direct click handlers re-attached.');
            }, 1000); // 1-second delay to allow other scripts to initialize
        });
    </script>

@endsection

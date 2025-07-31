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
                                        {{-- Route dùng POST nên không cần @method('PUT') --}}

                                        {{-- Tên sản phẩm --}}
                                        <div class="mb-3">
                                            <label class="form-label">Tên sản phẩm</label>
                                            <input type="text" class="form-control" name="name"
                                                value="{{ old('name', $product->name) }}">
                                            @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- Danh mục --}}
                                        <div class="mb-3 d-flex align-items-center gap-2">
                                            <label class="form-label mb-0 me-2">Danh mục</label>
                                            <select name="category_id" class="form-select" style="width: auto; flex:1;">
                                                <option value="">--Chọn danh mục--</option>
                                                @foreach ($categories as $cat)
                                                    <option value="{{ $cat->id }}"
                                                        {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                                        {{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-link px-2" style="color: #0da487"
                                                data-bs-toggle="modal" data-bs-target="#addCategoryModal">+ Thêm danh
                                                mục</button>
                                        </div>

                                        {{-- Vùng miền --}}
                                        <div class="mb-3 d-flex align-items-center gap-2">
                                            <label class="form-label mb-0 me-2">Vùng miền</label>
                                            <select name="region_id" class="form-select" style="width: auto; flex:1;">
                                                <option value="">--Chọn Vùng miền--</option>
                                                @foreach ($regions as $region)
                                                    <option value="{{ $region->id }}"
                                                        {{ old('region_id', $product->region_id) == $region->id ? 'selected' : '' }}>
                                                        {{ $region->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-link px-2" style="color: #0da487"
                                                data-bs-toggle="modal" data-bs-target="#addRegionModal">+ Thêm vùng
                                                miền</button>
                                        </div>

                                        {{-- Ảnh đại diện hiện tại --}}
                                        <div class="form-group">
                                            <label for="image">Ảnh đại diện</label>
                                            <input type="file" class="form-control" id="image" name="image">
                                            @if ($product->image)
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="Ảnh hiện tại"
                                                        style="max-width: 200px;">
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
                                                <div class="w-100 mt-2"> {{-- This div takes full width for the file input --}}
                                                    <input type="file" class="form-control" id="description_images"
                                                        name="description_images[]" multiple>
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

                                        {{-- Chọn loại sản phẩm: Có biến thể hay không --}}
                                        <div class="mb-3">
                                            <label class="form-label">Loại sản phẩm</label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="has_variants"
                                                        id="editHasVariantsYes" value="1"
                                                        {{ old('has_variants', $product->has_variants) == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="editHasVariantsYes">Có biến
                                                        thể</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="has_variants"
                                                        id="editHasVariantsNo" value="0"
                                                        {{ old('has_variants', $product->has_variants) == 0 ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="editHasVariantsNo">Không có biến
                                                        thể</label>
                                                </div>
                                            </div>
                                            @error('has_variants')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- BLOCK: THÔNG TIN CHO SẢN PHẨM KHÔNG CÓ BIẾN THỂ --}}
                                        {{-- <div id="edit-single-product-fields"
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
                                                            <label class="form-label">Khối lượng (hiển thị ở tên biến
                                                                thể)</label>
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
                                                            <input type="text" name="sku" class="form-control"
                                                                value="{{ old('sku', $product->variants->first()->sku ?? '') }}"
                                                                placeholder="Tự động nếu để trống">
                                                            @error('sku')
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}

                                        {{-- BLOCK: CHỌN THUỘC TÍNH VÀ GIÁ TRỊ DÙNG CHECKBOX --}}
                                        <div id="edit-variant-attribute-selection"
                                            style="display: {{ old('has_variants', $product->has_variants) == 1 ? 'block' : 'none' }};">
                                            {{-- <div class="card mb-3">
                                                <div class="card-body">
                                                    <h6 class="fw-bold mb-3">Chọn thuộc tính và giá trị cho biến thể</h6>
                                                    <input type="text" class="form-control mb-3"
                                                        id="filter-attributes" placeholder="Tìm thuộc tính...">

                                                    <div class="attribute-filters"
                                                        style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 4px;">
                                                        @foreach ($attributes as $attr)
                                                            <div class="attribute-group mb-3"
                                                                data-attr-name="{{ strtolower($attr->name) }}">
                                                                <button class="btn btn-link p-0 mb-1" type="button"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#attr-{{ $attr->id }}"
                                                                    aria-expanded="true"
                                                                    aria-controls="attr-{{ $attr->id }}">
                                                                    {{ $attr->name }} ({{ count($attr->values) }})
                                                                </button>
                                                                <div class="collapse show" id="attr-{{ $attr->id }}">
                                                                    <div class="values-list"
                                                                        style="max-height: 150px; overflow-y:auto; border:1px solid #ddd; padding:8px; border-radius:4px;">
                                                                        @foreach ($attr->values as $val)
                                                                            <label
                                                                                class="form-check form-check-inline d-block">
                                                                                @php
                                                                                    $checked = false;
                                                                                    if (
                                                                                        old(
                                                                                            'attribute_values_checkbox.' .
                                                                                                $attr->id,
                                                                                        ) &&
                                                                                        is_array(
                                                                                            old(
                                                                                                'attribute_values_checkbox.' .
                                                                                                    $attr->id,
                                                                                            ),
                                                                                        ) &&
                                                                                        in_array(
                                                                                            $val->id,
                                                                                            old(
                                                                                                'attribute_values_checkbox.' .
                                                                                                    $attr->id,
                                                                                            ),
                                                                                        )
                                                                                    ) {
                                                                                        $checked = true;
                                                                                    } elseif (
                                                                                        !old('has_variants') ||
                                                                                        old('has_variants') == 1
                                                                                    ) {
                                                                                        // Nếu có biến thể hoặc chưa chọn thì giữ checked cũ
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
                                                        <a href="#" class="btn btn-link p-0" data-bs-toggle="modal"
                                                            data-bs-target="#addAttributeModal">+ Thêm thuộc tính</a>
                                                        <a href="#" class="btn btn-link p-0" data-bs-toggle="modal"
                                                            data-bs-target="#addAttributeValueModal">+ Thêm giá trị thuộc
                                                            tính</a>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- BIẾN THỂ SẢN PHẨM --}}
                                            <div class="card mb-4">
                                                <div class="card-body">
                                                    <h5 class="mb-3">Biến thể sản phẩm</h5>
                                                    <p class="text-muted small">Biến thể được sinh tự động khi chọn các giá
                                                        trị thuộc tính.</p>

                                                    <div id="variants-list">
                                                        @php
                                                            $currentVariants = old('variants', null); // Lấy từ old() trước
                                                            if (is_null($currentVariants)) {
                                                                $currentVariants = [];
                                                                foreach (
                                                                    $product->variants->where('name', '!=', 'Mặc định')
                                                                    as $variant
                                                                ) {
                                                                    // Luôn hiển thị tất cả biến thể (trừ 'Mặc định')
                                                                    $variantData = [
                                                                        'id' => $variant->id,
                                                                        'price' => $variant->price,
                                                                        'stock' => $variant->stock,
                                                                        'sku' => $variant->sku,
                                                                        'name' => $variant->name,
                                                                        'description' => $variant->description,
                                                                        'image' => $variant->image, // Đảm bảo lấy image
                                                                        'active' => $variant->active,
                                                                        'attribute_value_ids' => $variant->attributeValues->pluck('id')->toArray(),
                                                                    ];
                                                                    $currentVariants[] = $variantData;
                                                                }
                                                            }

                                                            // Debug: Log thông tin variants
                                                            \Log::info('Current variants in view:', $currentVariants);
                                                        @endphp

                                                        @foreach ($currentVariants as $i => $variant)
                                                            <div
                                                                class="variant-row border rounded p-3 mb-3 bg-white position-relative">
                                                                {{-- Ẩn attribute_value_ids --}}
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

                                                                <div class="mb-2 fw-semibold" style="color: black;">
                                                                    Giá trị thuộc tính:
                                                                    <span>
                                                                        @php
                                                                            $names = [];
                                                                            foreach (
                                                                                $variant['attribute_value_ids'] ?? []
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

                                                                {{-- Phần hiển thị ảnh hiện tại và thay đổi ảnh --}}
                                                                <div class="row mt-3">
                                                                    <div class="col-12">
                                                                        <label class="form-label fw-bold text-success">Ảnh hiện tại của biến thể</label>
                                                                        @php
                                                                            $variantImage = $variant['image'] ?? '';
                                                                        @endphp

                                                                                                                                                @if (!empty($variantImage))
                                                                            <div class="d-flex justify-content-center mb-3">
                                                                                <div class="border rounded p-3 bg-light shadow-sm" style="max-width: 200px;">
                                                                                    <img src="{{ asset('storage/' . $variantImage) }}"
                                                                                        alt="Ảnh biến thể hiện tại"
                                                                                        style="width: 100%; height: 150px; object-fit: cover; border-radius: 6px;"
                                                                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='block'; this.nextElementSibling.nextElementSibling.style.display='block';">
                                                                                    <div style="display: none; text-align: center; color: #dc3545; font-size: 12px; padding: 20px;">
                                                                                        <i class="fas fa-exclamation-triangle"></i><br>
                                                                                        Lỗi tải ảnh<br>
                                                                                        <small>{{ $variantImage }}</small>
                                                                    </div>
                                                                                    <div style="display: none; text-align: center; color: #6c757d; font-size: 12px; padding: 20px;">
                                                                                        <i class="fas fa-info-circle"></i><br>
                                                                                        File ảnh không tồn tại<br>
                                                                                        <small>Vui lòng upload lại ảnh</small>
                                                                </div>
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            <div class="d-flex justify-content-center mb-3">
                                                                                <div class="border rounded p-3 bg-light text-center text-muted shadow-sm" style="max-width: 200px;">
                                                                                    <i class="fas fa-image" style="font-size: 64px; opacity: 0.3;"></i>
                                                                                    <br><small class="fw-bold">Chưa có ảnh</small>
                                                                                </div>
                                                                        </div>
                                                                    @endif

                                                                        {{-- Thay đổi ảnh biến thể --}}
                                                                        <div class="mb-3">
                                                                            <label class="form-label">Thay đổi ảnh biến thể</label>
                                                                    <input type="file"
                                                                        name="variants[{{ $i }}][image]"
                                                                                class="form-control" accept="image/*">
                                                                            <small class="text-muted">Để trống nếu không muốn thay đổi ảnh</small>
                                                                    @error("variants.$i.image")
                                                                        <small class="text-danger">{{ $message }}</small>
                                                                    @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                {{-- Mô tả biến thể --}}
                                                                <div class="row mt-3">
                                                                    <div class="col-12">
                                                                        <label class="form-label">Mô tả biến thể</label>
                                                                        <textarea name="variants[{{ $i }}][description]" rows="2"
                                                                            class="form-control variant-description-editor">{{ old("variants.$i.description", $variant['description'] ?? '') }}</textarea>
                                                                        @error("variants.$i.description")
                                                                            <small class="text-danger">{{ $message }}</small>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            {{-- <div class="col-12">
                                                                <label class="form-label">Mô tả biến thể</label>
                                                                <textarea name="variants[{{$i}}][description]" rows="2" class="form-control variant-description-editor">{{ old("variants.$i.description", $variant['description'] ?? '') }}</textarea>
                                                                @error("variants.$i.description")<small class="text-danger">{{ $message }}</small>@enderror
                                                            </div>
                                                        </div> --}}
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                </div>

                                {{-- Trạng thái --}}
                                <div class="mb-3">
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
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="quickCategoryForm" method="POST" action="{{ route('admin.categories.storeQuick') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryLabel">Thêm danh mục mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" class="form-control" placeholder="Tên danh mục mới"
                            required>
                        <div class="invalid-feedback" id="cat-error"></div>
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
    <div class="modal fade" id="addRegionModal" tabindex="-1" aria-labelledby="addRegionLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="quickRegionForm" method="POST" action="{{ route('admin.regions.storeQuick') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addRegionLabel">Thêm vùng miền mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" class="form-control" placeholder="Tên vùng miền mới"
                            required>
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
    <div class="modal fade" id="addAttributeModal" tabindex="-1" aria-labelledby="addAttributeLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="quickAttributeForm" method="POST" action="{{ route('admin.attributes.storeQuick') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAttributeLabel">Thêm thuộc tính mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" class="form-control mb-2" placeholder="Tên thuộc tính"
                            required>
                        <input type="text" name="values" class="form-control"
                            placeholder="Giá trị (cách nhau dấu phẩy)" required>
                        <small class="text-muted">VD: 1kg, 2kg, 3kg</small>
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
    <div class="modal fade" id="addAttributeValueModal" tabindex="-1" aria-labelledby="addAttributeValueLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="quickAttributeValueForm" method="POST" action="{{ route('admin.attribute_values.storeQuick') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAttributeValueLabel">Thêm giá trị thuộc tính mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <label>Chọn thuộc tính</label>
                        <select name="attribute_id" class="form-select mb-2" required>
                            <option value="">-- Chọn thuộc tính --</option>
                            @foreach ($attributes as $attr)
                                <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                            @endforeach
                        </select>
                        <label>Giá trị mới</label>
                        <input type="text" name="value" class="form-control" placeholder="Nhập giá trị mới"
                            required>
                        <div class="invalid-feedback" id="attr-value-error"></div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // ====== CKEditor ======
    let mainEditor = null;
    let variantEditors = [];
    let variantDescriptionsCache = {};

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

    function syncCKEditors() {
        if (mainEditor) {
            const mainDesc = document.getElementById('main-description');
            if (mainDesc) mainDesc.value = mainEditor.getData();
        }
        if (variantEditors.length > 0) {
            variantEditors.forEach(editor => {
                if (editor.sourceElement) {
                    editor.sourceElement.value = editor.getData();
                }
            });
        }
    }

    // ============ AJAX SUBMIT FORM SỬA SẢN PHẨM ============
    const mainForm = document.getElementById('main-form');
    if (mainForm) {
        mainForm.addEventListener('submit', function(e) {
            e.preventDefault();
            syncCKEditors();

            const formData = new FormData(this);
            formData.append('_method', 'POST');

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công',
                        text: data.message || 'Cập nhật sản phẩm thành công!',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = '{{ route('admin.products.index') }}';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: data.message || 'Có lỗi xảy ra khi cập nhật!',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi hệ thống',
                    text: 'Vui lòng thử lại hoặc liên hệ IT!',
                    confirmButtonText: 'OK'
                });
            });
        });
    }

    // ========== AJAX CHO MODAL (THÊM DANH MỤC, VÙNG MIỀN, THUỘC TÍNH, GIÁ TRỊ THUỘC TÍNH) ==========
    function ajaxModalForm(formId, modalId, selectName, optionKey, onSuccessCustom) {
        const form = document.getElementById(formId);
        if (!form) return;
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Ẩn modal
                    const modalEl = document.getElementById(modalId);
                    if (modalEl) {
                        const bsModal = bootstrap.Modal.getInstance(modalEl);
                        if (bsModal) bsModal.hide();
                    }
                    // Thêm vào select nếu cần
                    if (selectName && data[optionKey]) {
                        const select = document.querySelector(`select[name="${selectName}"]`);
                        if (select) {
                            const newOption = document.createElement('option');
                            newOption.value = data[optionKey].id;
                            newOption.text = data[optionKey].name || data[optionKey].value || '';
                            newOption.selected = true;
                            select.appendChild(newOption);
                            select.dispatchEvent(new Event('change'));
                        }
                    }
                    // Gọi callback tuỳ loại modal (thuộc tính, giá trị thuộc tính ...)
                    if (typeof onSuccessCustom === 'function') onSuccessCustom(data);

                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: data.message || 'Thêm thành công!',
                        timer: 1200,
                        showConfirmButton: false
                    });
                    this.reset();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: data.message || 'Thêm mới thất bại!',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi hệ thống',
                    text: 'Vui lòng thử lại!',
                    confirmButtonText: 'OK'
                });
            });
        });
    }

    // Đăng ký các modal AJAX
    ajaxModalForm('quickCategoryForm', 'addCategoryModal', 'category_id', 'category');
    ajaxModalForm('quickRegionForm', 'addRegionModal', 'region_id', 'region');
    ajaxModalForm('quickAttributeForm', 'addAttributeModal', null, 'attribute', function(data) {
        // Thêm thuộc tính mới vào UI filter
        if (data.attribute && data.attributeValues) {
            updateAttributeFilterUI(data.attribute, data.attributeValues);
        }
    });
    ajaxModalForm('quickAttributeValueForm', 'addAttributeValueModal', null, 'attributeValue', function(data) {
        // Thêm giá trị thuộc tính mới vào UI
        if (data.attributeValues && data.attribute_id && data.attribute_name) {
            updateAttributeValueFilterUI(data.attribute_id, data.attribute_name, data.attributeValues);
        }
    });

    // ========== HÀM HỖ TRỢ CẬP NHẬT UI FILTER THUỘC TÍNH & GIÁ TRỊ ==========
    function updateAttributeFilterUI(attribute, attributeValues) {
        const container = document.querySelector('.attribute-filters');
        if (!container) return;
        const valuesHtml = attributeValues.map(val => `
            <label class="form-check form-check-inline d-block">
                <input
                    class="form-check-input attribute-value-checkbox"
                    type="checkbox"
                    data-attrid="${attribute.id}"
                    value="${val.id}"
                    name="attribute_values_checkbox[${attribute.id}][]"
                    checked
                >
                <span class="form-check-label">${val.value}</span>
            </label>
        `).join('');
        const attrGroup = document.createElement('div');
        attrGroup.className = 'attribute-group mb-3';
        attrGroup.dataset.attrName = attribute.name.toLowerCase();
        attrGroup.innerHTML = `
            <button class="btn btn-link p-0 mb-1" type="button" data-bs-toggle="collapse" data-bs-target="#attr-${attribute.id}" aria-expanded="true" aria-controls="attr-${attribute.id}">
                ${attribute.name} (${attributeValues.length})
            </button>
            <div class="collapse show" id="attr-${attribute.id}">
                <div class="values-list" style="max-height: 150px; overflow-y:auto; border:1px solid #ddd; padding:8px; border-radius:4px;">
                    ${valuesHtml}
                </div>
            </div>
        `;
        container.appendChild(attrGroup);
        registerCheckboxListeners();
    }

    function updateAttributeValueFilterUI(attributeId, attributeName, attributeValues) {
        const container = document.querySelector('.attribute-filters');
        if (!container) return;
        const attrNameLower = attributeName.toLowerCase();
        let attrGroup = container.querySelector(`.attribute-group[data-attr-name="${attrNameLower}"]`);
        if (!attrGroup) {
            attrGroup = document.createElement('div');
            attrGroup.className = 'attribute-group mb-3';
            attrGroup.dataset.attrName = attrNameLower;
            attrGroup.innerHTML = `
                <button class="btn btn-link p-0 mb-1" type="button" data-bs-toggle="collapse" data-bs-target="#attr-${attributeId}" aria-expanded="true" aria-controls="attr-${attributeId}">
                    ${attributeName} (${attributeValues.length})
                </button>
                <div class="collapse show" id="attr-${attributeId}">
                    <div class="values-list" style="max-height: 150px; overflow-y:auto; border:1px solid #ddd; padding:8px; border-radius:4px;">
                        ${attributeValues.map(val => `
                            <label class="form-check form-check-inline d-block">
                                <input
                                    class="form-check-input attribute-value-checkbox"
                                    type="checkbox"
                                    data-attrid="${attributeId}"
                                    value="${val.id}"
                                    name="attribute_values_checkbox[${attributeId}][]"
                                    checked
                                >
                                <span class="form-check-label">${val.value}</span>
                            </label>
                        `).join('')}
                    </div>
                </div>
            `;
            container.appendChild(attrGroup);
        } else {
            const valuesList = attrGroup.querySelector('.values-list');
            if (valuesList) {
                const existingIds = new Set(Array.from(valuesList.querySelectorAll('input.attribute-value-checkbox')).map(input => input.value));
                attributeValues.forEach(val => {
                    if (!existingIds.has(val.id.toString())) {
                        const label = document.createElement('label');
                        label.className = 'form-check form-check-inline d-block';
                        label.innerHTML = `
                            <input
                                class="form-check-input attribute-value-checkbox"
                                type="checkbox"
                                data-attrid="${attributeId}"
                                value="${val.id}"
                                name="attribute_values_checkbox[${attributeId}][]"
                                checked
                            >
                            <span class="form-check-label">${val.value}</span>
                        `;
                        valuesList.appendChild(label);
                    }
                });
                const btn = attrGroup.querySelector('button');
                if (btn) {
                    const totalValues = valuesList.querySelectorAll('input.attribute-value-checkbox').length;
                    btn.textContent = `${attributeName} (${totalValues})`;
                }
            }
        }
        registerCheckboxListeners();
    }

    // ========== HÀM ĐĂNG KÝ LẠI SỰ KIỆN CHECKBOX GIÁ TRỊ THUỘC TÍNH ==========
    function registerCheckboxListeners() {
        document.querySelectorAll('.attribute-value-checkbox').forEach(cb => {
            cb.removeEventListener('change', generateVariants);
            cb.addEventListener('change', generateVariants);
        });
    }

    // ========== CÁC HÀM KHỞI TẠO KHÁC (sinh biến thể, toggle loại sản phẩm, v.v...) ==========
    // ...giữ nguyên như bạn đang dùng...

    // ====== Khởi tạo ban đầu ======
    initMainEditor();
    initVariantEditors();
    // ... các hàm khởi tạo khác nếu cần ...
});
</script>



@endsection

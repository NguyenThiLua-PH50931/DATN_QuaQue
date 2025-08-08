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
            <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info"
                    type="button" role="tab" aria-controls="info" aria-selected="true">Thông tin sản phẩm
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="category-tab" data-bs-toggle="tab" data-bs-target="#category"
                    type="button" role="tab" aria-controls="category" aria-selected="false">Phân loại & Danh mục
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="variant-tab" data-bs-toggle="tab" data-bs-target="#variant"
                    type="button" role="tab" aria-controls="variant" aria-selected="false">Biến thể sản phẩm
            </button>
        </li>
    </ul>

    {{-- Tab contents --}}
    <div class="tab-content" id="editProductTabContent">

        {{-- TAB 1: Thông tin sản phẩm --}}
        <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
            {{-- Tên sản phẩm --}}
            <div class="mb-3">
                <label class="form-label">Tên sản phẩm</label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $product->name) }}">
                @error('name')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            {{-- Ảnh đại diện hiện tại --}}
            <div class="form-group">
                <label for="image">Ảnh đại diện</label>
                <input type="file" class="form-control" id="image" name="image">
                @if ($product->image)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="Ảnh hiện tại" style="max-width: 200px;">
                    </div>
                @endif
            </div>

            {{-- Ảnh mô tả hiện tại --}}
            <div class="form-group mt-4">
                <label class="form-label">Ảnh mô tả sản phẩm</label>
                <div class="description-images-container d-flex flex-wrap gap-2">
                    @foreach ($product->product_images as $image)
                        <div class="image-item position-relative border rounded" style="width: 100px; height: 100px;">
                            <img src="{{ $image->image_url }}" class="w-100 h-100 object-fit-cover" alt="Ảnh mô tả">
                            <button type="button"
                                    class="btn btn-danger btn-sm product-image-delete-btn position-absolute rounded-circle p-1 delete-image-x-btn"
                                    data-id="{{ $image->id }}"
                                    data-url="{{ route('admin.products.image.delete', $image->id) }}">
                                &times;
                            </button>
                        </div>
                    @endforeach
                    <div class="w-100 mt-2">
                        <input type="file" class="form-control" id="description_images" name="description_images[]" multiple>
                        <small class="text-muted">Có thể chọn nhiều ảnh cùng lúc</small>
                    </div>
                </div>
            </div>

            {{-- Xuất xứ --}}
            <div class="mb-3">
                <label class="form-label">Xuất xứ</label>
                <input type="text" name="origin" class="form-control" value="{{ old('origin', $product->origin) }}">
                @error('origin')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            {{-- Mô tả sản phẩm --}}
            <div class="mb-3">
                <label class="form-label">Mô tả sản phẩm</label>
                <textarea name="description" id="main-description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                @error('description')<small class="text-danger">{{ $message }}</small>@enderror
            </div>
        </div>

        {{-- TAB 2: Phân loại & Danh mục --}}
        <div class="tab-pane fade" id="category" role="tabpanel" aria-labelledby="category-tab">

  {{-- Danh mục nhiều checkbox 3 cột --}}
  <div class="mb-3">
    <label class="form-label fw-bold d-flex justify-content-between align-items-center">
      <span>Danh mục</span>
      <button type="button" class="btn btn-link p-0" style="color: #0da487; font-size: 0.9rem;"
              data-bs-toggle="modal" data-bs-target="#addCategoryModal">+ Thêm danh mục</button>
    </label>
    <div class="row">
      @foreach ($categories as $cat)
        <div class="col-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox"
                   name="category_ids[]"
                   value="{{ $cat->id }}"
                   id="category-{{ $cat->id }}"
                   {{ collect(old('category_ids', $product->categories->pluck('id')->toArray() ?? []))->contains($cat->id) ? 'checked' : '' }}>
            <label class="form-check-label" for="category-{{ $cat->id }}">
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
    <label class="form-label fw-bold d-flex justify-content-between align-items-center">
      <span>Vùng miền</span>
      <button type="button" class="btn btn-link p-0" style="color: #0da487; font-size: 0.9rem;"
              data-bs-toggle="modal" data-bs-target="#addRegionModal">+ Thêm vùng miền</button>
    </label>
    <div class="row regionRadio">
      @foreach ($regions as $region)
        <div class="col-4">
          <div class="form-check">
            <input class="form-check-input" type="radio"
                   name="region_id"
                   value="{{ $region->id }}"
                   id="region-{{ $region->id }}"
                   {{ old('region_id', $product->region_id) == $region->id ? 'checked' : '' }}>
            <label class="form-check-label" for="region-{{ $region->id }}">
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
        <div class="tab-pane fade" id="variant" role="tabpanel" aria-labelledby="variant-tab">

            {{-- Chọn loại sản phẩm: Có biến thể hay không --}}
            <div class="mb-3">
                <label class="form-label">Loại sản phẩm</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="has_variants" id="editHasVariantsYes" value="1"
                               {{ old('has_variants', $product->has_variants) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="editHasVariantsYes">Có biến thể</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="has_variants" id="editHasVariantsNo" value="0"
                               {{ old('has_variants', $product->has_variants) == 0 ? 'checked' : '' }}>
                        <label class="form-check-label" for="editHasVariantsNo">Không có biến thể</label>
                    </div>
                </div>
                @error('has_variants')<small class="text-danger">{{ $message }}</small>@enderror
            </div>

            {{-- Thông tin sản phẩm đơn nếu không có biến thể --}}
            <div id="edit-single-product-fields" style="display: {{ old('has_variants', $product->has_variants) == 0 ? 'block' : 'none' }};">
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Thông tin sản phẩm đơn</h6>
                        <div class="row gx-2 gy-2">
                            <div class="col-md-6">
                                <label class="form-label">Giá bán</label>
                                <input type="number" name="price" min="0" step="0.01" class="form-control"
                                       value="{{ old('price', $product->variants->first()->price ?? '') }}">
                                @error('price')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Khối lượng (hiển thị ở tên biến thể)</label>
                                <input type="text" name="variant_name" class="form-control"
                                       value="{{ old('variant_name', $product->variants->first()->name ?? '') }}"
                                       placeholder="Nhập khối lượng, ví dụ: 500g, 1kg...">
                                @error('variant_name')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tồn kho</label>
                                <input type="number" name="stock" min="0" class="form-control"
                                       value="{{ old('stock', $product->variants->first()->stock ?? '') }}">
                                @error('stock')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">SKU (Mã kho)</label>
                                <input type="text" name="sku" class="form-control"
                                       value="{{ old('sku', $product->variants->first()->sku ?? '') }}"
                                       placeholder="Tự động nếu để trống">
                                @error('sku')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Chọn thuộc tính và giá trị cho biến thể --}}
            <div id="edit-variant-attribute-selection" style="display: {{ old('has_variants', $product->has_variants) == 1 ? 'block' : 'none' }};">
                <div class="card mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Chọn thuộc tính và giá trị cho biến thể</h6>
                        <input type="text" class="form-control mb-3" id="filter-attributes" placeholder="Tìm thuộc tính...">

                        <div class="attribute-filters" style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 4px;">
                            @foreach ($attributes as $attr)
                                <div class="attribute-group mb-3" data-attr-name="{{ strtolower($attr->name) }}">
                                    <button class="btn btn-link p-0 mb-1" type="button" data-bs-toggle="collapse" data-bs-target="#attr-{{ $attr->id }}" aria-expanded="true" aria-controls="attr-{{ $attr->id }}">
                                        {{ $attr->name }} ({{ count($attr->values) }})
                                    </button>
                                    <div class="collapse show" id="attr-{{ $attr->id }}">
                                        <div class="values-list" style="max-height: 150px; overflow-y:auto; border:1px solid #ddd; padding:8px; border-radius:4px;">
                                            @foreach ($attr->values as $val)
                                                @php
                                                    $checked = false;
                                                    $oldValues = old('attribute_values_checkbox.' . $attr->id, []);
                                                    if (is_array($oldValues) && in_array($val->id, $oldValues)) {
                                                        $checked = true;
                                                    } elseif (!old('has_variants') || old('has_variants') == 1) {
                                                        foreach ($product->variants->where('name', '!=', 'Mặc định') as $variant) {
                                                            $valIds = $variant->attributeValues->pluck('id')->toArray();
                                                            if (in_array($val->id, $valIds)) {
                                                                $checked = true;
                                                                break;
                                                            }
                                                        }
                                                    }
                                                @endphp
                                                <label class="form-check form-check-inline d-block">
                                                    <input class="form-check-input attribute-value-checkbox" type="checkbox" data-attrid="{{ $attr->id }}" value="{{ $val->id }}" name="attribute_values_checkbox[{{ $attr->id }}][]" {{ $checked ? 'checked' : '' }}>
                                                    <span class="form-check-label">{{ $val->value }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-2 d-flex gap-3">
                            <a href="#" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#addAttributeModal">+ Thêm thuộc tính</a>
                            <a href="#" class="btn btn-link p-0" data-bs-toggle="modal" data-bs-target="#addAttributeValueModal">+ Thêm giá trị thuộc tính</a>
                        </div>
                    </div>
                </div>

                {{-- Biến thể sản phẩm --}}
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="mb-3">Biến thể sản phẩm</h5>
                        <p class="text-muted small">Biến thể được sinh tự động khi chọn các giá trị thuộc tính.</p>
                        <div id="variants-list">
                            @php
                                $currentVariants = old('variants', null);
                                if (is_null($currentVariants)) {
                                    $currentVariants = [];
                                    foreach ($product->variants->where('name', '!=', 'Mặc định') as $variant) {
                                        $currentVariants[] = [
                                            'id' => $variant->id,
                                            'price' => $variant->price,
                                            'stock' => $variant->stock,
                                            'sku' => $variant->sku,
                                            'name' => $variant->name,
                                            'description' => $variant->description,
                                            'image' => $variant->image,
                                            'active' => $variant->active,
                                            'attribute_value_ids' => $variant->attributeValues->pluck('id')->toArray(),
                                        ];
                                    }
                                }
                            @endphp

                            @foreach ($currentVariants as $i => $variant)
                                <div class="variant-row border rounded p-3 mb-3 bg-white position-relative">
                                    @foreach ($variant['attribute_value_ids'] ?? [] as $valId)
                                        <input type="hidden" name="variants[{{ $i }}][attribute_value_ids][]" value="{{ $valId }}">
                                    @endforeach
                                    <input type="hidden" name="variants[{{ $i }}][id]" value="{{ $variant['id'] ?? '' }}">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 btn-remove-variant" title="Xóa biến thể">&times;</button>

                                    <div class="mb-2 fw-semibold" style="color: black;">
                                        Giá trị thuộc tính:
                                        <span>
                                            @php
                                                $names = [];
                                                foreach ($variant['attribute_value_ids'] ?? [] as $vid) {
                                                    $attrVal = \App\Models\admin\AttributeValue::find($vid);
                                                    if ($attrVal) $names[] = $attrVal->value;
                                                }
                                                echo implode(' - ', $names);
                                            @endphp
                                        </span>
                                        @if(empty($names))
                                            <em>(biến thể thủ công)</em>
                                        @endif
                                    </div>

                                    <div class="row gx-2 gy-2">
                                        <div class="col-6 col-md-3">
                                            <label class="form-label">Giá bán</label>
                                            <input type="number" name="variants[{{ $i }}][price]" min="0" step="0.01" class="form-control" value="{{ old("variants.$i.price", $variant['price'] ?? '') }}" required>
                                            @error("variants.$i.price")<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <label class="form-label">Tồn kho</label>
                                            <input type="number" name="variants[{{ $i }}][stock]" min="0" class="form-control" value="{{ old("variants.$i.stock", $variant['stock'] ?? '') }}" required>
                                            @error("variants.$i.stock")<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <label class="form-label">SKU</label>
                                            <input type="text" name="variants[{{ $i }}][sku]" class="form-control sku-auto" readonly value="{{ old("variants.$i.sku", $variant['sku'] ?? '') }}">
                                            @error("variants.$i.sku")<small class="text-danger">{{ $message }}</small>@enderror
                                        </div>
                                        <div class="col-6 col-md-3">
                                            <input type="hidden" name="variants[{{ $i }}][old_image]" value="{{ old("variants.$i.old_image", $variant['image'] ?? '') }}">
                                        </div>
                                    </div>

                                    {{-- Hiển thị ảnh hiện tại --}}
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <label class="form-label fw-bold text-success">Ảnh hiện tại của biến thể</label>
                                            @php $variantImage = $variant['image'] ?? ''; @endphp
                                            @if (!empty($variantImage))
                                                <div class="d-flex justify-content-center mb-3">
                                                    <div class="border rounded p-3 bg-light shadow-sm" style="max-width: 200px;">
                                                        <img src="{{ asset('storage/' . $variantImage) }}" alt="Ảnh biến thể hiện tại" style="width: 100%; height: 150px; object-fit: cover; border-radius: 6px;" onerror="this.style.display='none'; this.nextElementSibling.style.display='block'; this.nextElementSibling.nextElementSibling.style.display='block';">
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
                                                <input type="file" name="variants[{{ $i }}][image]" class="form-control" accept="image/*">
                                                <small class="text-muted">Để trống nếu không muốn thay đổi ảnh</small>
                                                @error("variants.$i.image")<small class="text-danger">{{ $message }}</small>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Mô tả biến thể --}}
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <label class="form-label">Mô tả biến thể</label>
                                            <textarea name="variants[{{ $i }}][description]" rows="2" class="form-control variant-description-editor">{{ old("variants.$i.description", $variant['description'] ?? '') }}</textarea>
                                            @error("variants.$i.description")<small class="text-danger">{{ $message }}</small>@enderror
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
            <option value="1" {{ old('active', $product->active) == 1 ? 'selected' : '' }}>Có</option>
            <option value="0" {{ old('active', $product->active) == 0 ? 'selected' : '' }}>Không</option>
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
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Biến lưu editor chính và editor biến thể
    let mainEditor = null;
    let variantEditors = [];

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
            const descriptionEditor = variantEditors.find(ed => ed.sourceElement === row.querySelector('textarea.variant-description-editor'));
            const description = descriptionEditor ? descriptionEditor.getData() : (row.querySelector('textarea.variant-description-editor')?.value || '');
            const id = row.querySelector('input[name$="[id]"]')?.value || '';
            const oldImage = row.querySelector('input[name$="[old_image]"]')?.value || '';

            variantsData[key] = { price, stock, sku, description, id, oldImage };
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
            a.flatMap(d => b.map(e => [...d, e])), [[]]);
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
                    const suffix = attrSpan.textContent.trim().replace(/\s+/g, '').toUpperCase().substring(0, 6);
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
            const defaultVariant = {!! $product->variants->where('name', 'Mặc định')->first() ? json_encode($product->variants->where('name', 'Mặc định')->first()->toArray()) : 'null' !!};
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
                    const existingIds = (v.attribute_values || []).map(av => av.id.toString());
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
                        const oldInputIds = (v.attribute_value_ids || []).map(id => id.toString());
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
                        window.location.href = '{{ route('admin.products.index') }}';
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
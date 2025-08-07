@extends('layouts.backend')

@section('title', 'Thêm sản phẩm')

@section('content')
<div class="page-body">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="row">
          <div class="col-sm-10 m-auto">

            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              <i class="ri-check-double-line me-1 align-middle"></i>
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="Close"></button>
            </div>
            @endif
            @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- BLOCK 1: THÔNG TIN CƠ BẢN --}}
            <div class="card mb-3">
              <div class="card-body">
                <div class="title-header option-title d-sm-flex d-block">
                  <h5>Thêm Sản Phẩm</h5>
                  <div class="right-options">
                    <ul>
                      <li>
                        <a class="btn btn-solid" href="{{ route('admin.products.index') }}">Quay
                          lại trang danh sách</a>
                      </li>
                    </ul>
                  </div>
                </div>
                <form id="main-form" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="theme-form theme-form-2 mega-form">
                  @csrf

                  {{-- Nav tabs --}}
                  <ul class="nav nav-tabs mb-3" id="editProductTab" role="tablist">
                    <li class="nav-item" role="presentation">
                      <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info"
                        type="button" role="tab" aria-controls="info" aria-selected="true">Thông tin sản phẩm</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" id="category-tab" data-bs-toggle="tab" data-bs-target="#category"
                        type="button" role="tab" aria-controls="category" aria-selected="false">Phân loại & Danh mục</button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link" id="variant-tab" data-bs-toggle="tab" data-bs-target="#variant"
                        type="button" role="tab" aria-controls="variant" aria-selected="false">Biến thể sản phẩm</button>
                    </li>
                  </ul>

                  {{-- Tab contents --}}
                  <div class="tab-content" id="editProductTabContent">
                    {{-- TAB 1: Thông tin sản phẩm --}}
                    <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                      <div class="mb-3">
                        <label class="form-label">Tên sản phẩm</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                        @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                      </div>

                      <div class="form-group">
                        <label for="image">Ảnh đại diện</label>
                        <input type="file" class="form-control" id="image" name="image" required>
                        @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                      </div>

                      <div class="form-group mt-4">
                        <label class="form-label">Ảnh mô tả sản phẩm</label>
                        <input type="file" class="form-control" name="images[]" multiple>
                        @error('images.*')<small class="text-danger">{{ $message }}</small>@enderror
                      </div>

                      <div class="mb-3">
                        <label class="form-label">Xuất xứ</label>
                        <input type="text" name="origin" class="form-control" value="{{ old('origin') }}">
                        @error('origin')<small class="text-danger">{{ $message }}</small>@enderror
                      </div>

                      <div class="mb-3">
                        <label class="form-label">Mô tả sản phẩm</label>
                        <textarea name="description" id="main-description" class="form-control" rows="4">{{ old('description') }}</textarea>
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
                                {{ collect(old('category_ids'))->contains($cat->id) ? 'checked' : '' }}>
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
                                {{ old('region_id') == $region->id ? 'checked' : '' }}>
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
                      <div class="mb-3">
                        <label class="form-label">Loại sản phẩm</label>
                        <div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="has_variants" id="hasVariantsYes" value="1" {{ old('has_variants', 1) == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="hasVariantsYes">Có biến thể</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="has_variants" id="hasVariantsNo" value="0" {{ old('has_variants', 1) == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="hasVariantsNo">Không có biến thể</label>
                          </div>
                        </div>
                        @error('has_variants')<small class="text-danger">{{ $message }}</small>@enderror
                      </div>

                      {{-- Thông tin sản phẩm đơn nếu không có biến thể --}}
                      <div id="single-product-fields" style="display: {{ old('has_variants', 1) == 0 ? 'block' : 'none' }};">
                        <div class="card mb-3">
                          <div class="card-body">
                            <h6 class="fw-bold mb-3">Thông tin sản phẩm đơn</h6>
                            <div class="row gx-2 gy-2">
                              <div class="col-md-6">
                                <label class="form-label">Giá bán</label>
                                <input type="number" name="price" min="0" step="0.01" class="form-control" value="{{ old('price') }}">
                                @error('price')<small class="text-danger">{{ $message }}</small>@enderror
                              </div>
                              <div class="col-md-6">
                                <label class="form-label">Khối lượng (hiển thị ở tên biến thể)</label>
                                <input type="text" name="variant_name" class="form-control" value="{{ old('variant_name') }}" placeholder="Ví dụ: 500g, 1kg...">
                                @error('variant_name')<small class="text-danger">{{ $message }}</small>@enderror
                              </div>
                              <div class="col-md-6">
                                <label class="form-label">Tồn kho</label>
                                <input type="number" name="stock" min="0" class="form-control" value="{{ old('stock') }}">
                                @error('stock')<small class="text-danger">{{ $message }}</small>@enderror
                              </div>
                              <div class="col-md-6">
                                <label class="form-label">SKU (Mã kho)</label>
                                <input type="text" name="sku" class="form-control" value="{{ old('sku') }}" placeholder="Tự động nếu để trống">
                                @error('sku')<small class="text-danger">{{ $message }}</small>@enderror
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      {{-- Chọn thuộc tính và giá trị cho biến thể nếu có --}}
                      <div id="variant-attribute-selection" style="display: {{ old('has_variants', 1) == 1 ? 'block' : 'none' }};">
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
                                    <label class="form-check form-check-inline d-block">
                                      <input class="form-check-input attribute-value-checkbox" type="checkbox" data-attrid="{{ $attr->id }}" value="{{ $val->id }}" name="attribute_values_checkbox[{{ $attr->id }}][]" {{ (old('attribute_values_checkbox.' . $attr->id) && in_array($val->id, old('attribute_values_checkbox.' . $attr->id))) ? 'checked' : '' }}>
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

                        {{-- Biến thể tự động sinh --}}
                        <div class="card mb-4">
                          <div class="card-body">
                            <h5 class="mb-3">Biến thể sản phẩm</h5>
                            <p class="text-muted small">Biến thể sẽ được sinh tự động khi chọn các giá trị thuộc tính.</p>
                            <div id="variants-list">
                              {{-- JS sẽ sinh biến thể ở đây --}}
                              @if(old('variants'))
                              @foreach(old('variants') as $i => $variant)
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
                                    <div class="mb-3">
                                      <label class="form-label">Thay đổi ảnh biến thể</label>
                                      <input type="file" name="variants[{{ $i }}][image]" class="form-control" accept="image/*">
                                      <small class="text-muted">Để trống nếu không muốn thay đổi ảnh</small>
                                      @error("variants.$i.image")<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                  </div>
                                </div>

                                <div class="row mt-3">
                                  <div class="col-12">
                                    <label class="form-label">Mô tả biến thể</label>
                                    <textarea name="variants[{{ $i }}][description]" rows="2" class="form-control variant-description-editor">{{ old("variants.$i.description", $variant['description'] ?? '') }}</textarea>
                                    @error("variants.$i.description")<small class="text-danger">{{ $message }}</small>@enderror
                                  </div>
                                </div>
                              </div>
                              @endforeach
                              @endif
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  {{-- Trạng thái --}}
                  <div class="mb-3 mt-3">
                    <label class="form-label">Kích hoạt</label>
                    <select name="active" class="form-select">
                      <option value="1" {{ old('active', 1) == 1 ? 'selected' : '' }}>Có</option>
                      <option value="0" {{ old('active', 1) == 0 ? 'selected' : '' }}>Không</option>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-primary mt-2">Thêm sản phẩm</button>
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
<div class="modal fade" id="addCategoryModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="quickCategoryForm" method="POST" action="{{ route('admin.categories.storeQuick') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Thêm danh mục mới</h5>
        </div>
        <div class="modal-body">
          <input type="text" name="name" class="form-control" placeholder="Tên danh mục mới">
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
<div class="modal fade" id="addRegionModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="quickRegionForm" method="POST" action="{{ route('admin.regions.storeQuick') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Thêm vùng miền mới</h5>
        </div>
        <div class="modal-body">
          <input type="text" name="name" class="form-control" placeholder="Tên vùng miền mới">
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
        </div>
        <div class="modal-body">
          <input type="text" name="name" class="form-control mb-2" placeholder="Tên thuộc tính">
          <input type="text" name="values" class="form-control"
            placeholder="Giá trị (cách nhau dấu phẩy)">
          <small>VD: 1kg, 2kg, 3kg</small>
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
    background: #f9f9fc;
    border: 1px solid #e0e7ef;
    border-radius: 8px;
    margin-bottom: 18px;
    /* position: relative; */
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
</style>

{{-- CKEditor 5 CDN --}}
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
    // =================== Biến toàn cục ===================
    let variantEditors = [];

    // =================== Khởi tạo CKEditor cho textarea mô tả biến thể ===================
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

    // =================== Khởi tạo CKEditor cho mô tả chính ===================
    function initMainEditor() {
        const mainDesc = document.getElementById('main-description');
        if (mainDesc) {
            ClassicEditor.create(mainDesc).then(editor => {
                editor.ui.view.editable.element.style.color = '#222';
                editor.ui.view.editable.element.style.background = '#fff';
            });
        }
    }

    // =================== Hàm tính tích Descartes (Cartesian product) ===================
    function cartesianProduct(arr) {
        return arr.reduce((a, b) =>
            a.flatMap(d => b.map(e => [...d, e])), [
                []
            ]
        );
    }

    // =================== Filter tìm thuộc tính ===================
    function removeVietnameseTones(str) {
        let from = "áàảãạăắằẳẵặâấầẩẫậđéèẻẽẹêếềểễệíìỉĩịóòỏõọôốồổỗộơớờởỡợúùủũụưứừửữựýỳỷỹỵ";
        let to = "aaaaaaaaaaaaaaaaadeeeeeeeeeeeiiiiiooooooooooooooooouuuuuuuuuuuyyyyy";
        str = str.toLowerCase();
        for (let i = 0; i < from.length; i++) {
            str = str.replace(new RegExp(from[i], 'g'), to[i]);
        }
        return str;
    }

    // Filter tìm thuộc tính không dấu, không phân biệt hoa thường
    document.getElementById('filter-attributes').addEventListener('input', function() {
        const filterRaw = this.value.trim().toLowerCase();
        const filter = removeVietnameseTones(filterRaw);

        document.querySelectorAll('.attribute-group').forEach(group => {
            const attrNameRaw = group.dataset.attrName;
            const attrName = removeVietnameseTones(attrNameRaw);
            if (attrName.includes(filter)) {
                group.style.display = '';
                // Mở collapse khi có filter trùng
                const collapseEl = group.querySelector('.collapse');
                if (collapseEl && !collapseEl.classList.contains('show')) {
                    const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseEl);
                    bsCollapse.show();
                }
            } else {
                group.style.display = 'none';
            }
        });
    });

    // Đăng ký event checkbox và gán lại sau khi DOM thay đổi
    function registerCheckboxListeners() {
        document.querySelectorAll('.attribute-value-checkbox').forEach(cb => {
            cb.removeEventListener('change', generateVariants); // tránh trùng
            cb.addEventListener('change', generateVariants);
        });
    }

    // =================== Sinh biến thể tự động từ checkbox thuộc tính đã chọn ===================
    function generateVariants() {
        const checkboxes = document.querySelectorAll('.attribute-value-checkbox');
        let attrMap = {};

        // Thu thập các giá trị checkbox đã chọn theo từng thuộc tính
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

        // Xóa hết các biến thể hiện tại (vì không giữ biến thể thủ công nữa)
        variantArea.innerHTML = '';

        const attrArrays = Object.values(attrMap);

        // Nếu không có thuộc tính nào được chọn thì dừng
        if (attrArrays.length === 0) {
            showRemoveButtons();
            initVariantEditors();
            updateSkuAll();
            return;
        }

        // Sinh tổ hợp các biến thể (tích Descartes)
        const variantsComb = cartesianProduct(attrArrays);

        // Tạo HTML cho từng biến thể và thêm vào vùng hiển thị
        variantsComb.forEach((combo, idx) => {
            const ids = combo.map(c => c.id);
            const names = combo.map(c => c.text);

            const html = `
    <div class="variant-row border rounded p-3 mb-3 bg-white position-relative">
      ${ids.map(id => `<input type="hidden" name="variants[${idx}][attribute_value_ids][]" value="${id}">`).join('')}
        <button type="button"
            class="btn-remove-variant btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
            title="Xóa biến thể">
              &times;
        </button>

      <div class="mb-2 fw-semibold">Giá trị thuộc tính: <span>${names.join(' - ')}</span></div>
      <div class="row gx-2 gy-2">
        <div class="col-6 col-md-3">
          <label class="form-label">Giá bán</label>
          <input type="number" name="variants[${idx}][price]" min="0" step="0.01" class="form-control" required>
        </div>
        <div class="col-6 col-md-3">
          <label class="form-label">Tồn kho</label>
          <input type="number" name="variants[${idx}][stock]" min="0" class="form-control" required>
        </div>
        <div class="col-6 col-md-3">
          <label class="form-label">SKU</label>
          <input type="text" name="variants[${idx}][sku]" class="form-control sku-auto" readonly>
        </div>
        <div class="col-6 col-md-3">
          <label class="form-label">Ảnh biến thể</label>
          <input type="file" name="variants[${idx}][image]" class="form-control" accept="image/*">
        </div>
        <div class="col-12">
          <label class="form-label">Mô tả biến thể</label>
          <textarea name="variants[${idx}][description]" rows="2" class="form-control variant-description-editor"></textarea>
        </div>
      </div>
    </div> `;
            variantArea.insertAdjacentHTML('beforeend', html);
        });

        updateSkuAll();
        showRemoveButtons();
        initVariantEditors();
    }

    // =================== Hiển thị / Ẩn nút Xóa biến thể ===================
    function showRemoveButtons() {
        const rows = document.querySelectorAll('.variant-row');
        rows.forEach(row => {
            const btn = row.querySelector('.btn-remove-variant');
            if (btn) btn.style.display = rows.length > 1 ? 'inline-block' : 'none';
        });
    }

    // =================== Xóa biến thể khi click nút xóa ===================
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-variant')) {
            e.target.closest('.variant-row').remove();
            showRemoveButtons();
            updateSkuAll();
        }
    });

    // =================== Tự động sinh SKU cho tất cả biến thể ===================
    function updateSkuAll() {
        const rows = document.querySelectorAll('.variant-row');
        const timestamp = Date.now();
        rows.forEach((row, index) => {
            const skuInput = row.querySelector('.sku-auto');
            const attrSpan = row.querySelector('div.mb-2 span');
            if (skuInput) {
                let sku = '';
                if (attrSpan) {
                    // Lấy 6 ký tự đầu của tên thuộc tính, không dấu, viết hoa, không khoảng trắng
                    const suffix = attrSpan.textContent.trim().replace(/\s+/g, '').toUpperCase().substring(0,
                        6);
                    sku = `PRD-${timestamp}-${suffix}-${index}`;
                } else {
                    sku = `PRD-${timestamp}-MANUAL-${Math.floor(Math.random() * 1000)}`;
                }
                skuInput.value = sku;
            }
        });
    }

    // =================== Bắt sự kiện checkbox thuộc tính thay đổi để sinh biến thể ===================
    document.querySelector('.attribute-filters').addEventListener('change', function(e) {
        if (e.target.classList.contains('attribute-value-checkbox')) {
            generateVariants();
        }
    });

    // =================== Toggle hiển thị các phần dựa trên lựa chọn biến thể ===================
    function toggleProductTypeFields() {
        const hasVariants = document.getElementById('hasVariantsYes').checked;
        const singleProductFields = document.getElementById('single-product-fields');
        const variantAttributeSelection = document.getElementById('variant-attribute-selection');

        if (hasVariants) {
            singleProductFields.style.display = 'none';
            variantAttributeSelection.style.display = 'block';
            // Đảm bảo các trường của biến thể không bị disabled
            singleProductFields.querySelectorAll('input, select, textarea').forEach(el => el.setAttribute('disabled',
                'true'));
            variantAttributeSelection.querySelectorAll('input, select, textarea').forEach(el => el.removeAttribute(
                'disabled'));

            // Khởi tạo lại CKEditor cho biến thể khi hiển thị
            initVariantEditors();

        } else {
            singleProductFields.style.display = 'block';
            variantAttributeSelection.style.display = 'none';
            // Đảm bảo các trường của sản phẩm đơn không bị disabled
            singleProductFields.querySelectorAll('input, select, textarea').forEach(el => el.removeAttribute(
                'disabled'));
            variantAttributeSelection.querySelectorAll('input, select, textarea').forEach(el => el.setAttribute(
                'disabled', 'true'));

            // Hủy CKEditor cho biến thể khi ẩn
            variantEditors.forEach(editor => editor.destroy());
            variantEditors = [];
            document.querySelectorAll('.variant-description-editor').forEach(textarea => {
                textarea.classList.remove('ck-editor-initialized');
            });
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Bắt sự kiện thay đổi radio button
        document.querySelectorAll('input[name="has_variants"]').forEach(radio => {
            radio.addEventListener('change', toggleProductTypeFields);
        });

        // Khởi tạo ban đầu
        initMainEditor();
        toggleProductTypeFields(); // Gọi lần đầu để thiết lập trạng thái ban đầu
        showRemoveButtons();
        updateSkuAll();
    });

    document.addEventListener('DOMContentLoaded', function() {

        function showValidationErrors(form, errors) {
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(div => {
                div.innerText = '';
                div.style.display = 'none';
            });

            for (const field in errors) {
                const messages = errors[field];
                const input = form.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    let errorDiv = form.querySelector(`#${field.replace(/\./g, '-')}-error`);
                    if (!errorDiv) {
                        errorDiv = input.nextElementSibling && input.nextElementSibling.classList.contains(
                                'invalid-feedback') ?
                            input.nextElementSibling :
                            null;
                    }
                    if (errorDiv) {
                        errorDiv.innerText = messages.join(', ');
                        errorDiv.style.display = 'block';
                    }
                }
            }
        }

        function ajaxFormSubmit(formId, modalId, selectName, optionKeyName, errorElementId, onSuccessCallback) {
            const form = document.getElementById(formId);
            if (!form) return;

            form.addEventListener('submit', function(e) {
                console.log('Submit event caught for', formId);

                e.preventDefault();

                const formData = new FormData(form);
                const url = form.action;

                if (errorElementId) {
                    const errorDiv = document.getElementById(errorElementId);
                    if (errorDiv) {
                        errorDiv.innerText = '';
                        errorDiv.style.display = 'none';
                        if (errorDiv.previousElementSibling) {
                            errorDiv.previousElementSibling.classList.remove('is-invalid');
                        }
                    }
                }

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': formData.get('_token'),
                        },
                        body: formData,
                    })
                    .then(res => {
                        if (!res.ok) {
                            if (res.status === 422) return res.json().then(data => Promise.reject(
                                data));
                            throw new Error('Lỗi server');
                        }
                        return res.json();
                    })
                    .then(data => {
                        if (data.success) {
                            const modalEl = document.getElementById(modalId);
                            if (modalEl) {
                                const bsModal = bootstrap.Modal.getInstance(modalEl);
                                if (bsModal) bsModal.hide();
                            }

                            if (selectName && data[optionKeyName]) {
                                const select = document.querySelector(
                                    `select[name="${selectName}"]`);
                                if (select) {
                                    const newOption = document.createElement('option');
                                    newOption.value = data[optionKeyName].id;
                                    newOption.text = data[optionKeyName].name || data[optionKeyName]
                                        .value || '';
                                    newOption.selected = true;
                                    select.appendChild(newOption);
                                    select.dispatchEvent(new Event('change'));
                                }
                            }

                            // Thay đổi quan trọng: không reset form nếu là quickAttributeValueForm
                            if (formId === 'quickAttributeValueForm') {
                                // Giữ lại giá trị input cũ và nối thêm giá trị mới
                                const valuesInput = form.querySelector(
                                    'input[name="value"], input[name="values"]');
                                if (valuesInput) {
                                    const oldVal = valuesInput.value.trim();

                                    // Lấy giá trị mới từ data.attributeValues thành mảng
                                    const newVals = (data.attributeValues || []).map(v => v.value
                                        .trim()).filter(v => v);

                                    // Tạo Set để loại trùng
                                    const allValsSet = new Set();

                                    // Thêm giá trị cũ đã có
                                    oldVal.split(',').forEach(v => {
                                        if (v.trim()) allValsSet.add(v.trim());
                                    });

                                    // Thêm giá trị mới
                                    newVals.forEach(v => allValsSet.add(v));

                                    // Gán lại input nối bằng dấu phẩy
                                    valuesInput.value = Array.from(allValsSet).join(', ');
                                }
                            } else {
                                // Các form khác reset bình thường
                                form.reset();
                            }

                            if (typeof onSuccessCallback === 'function') {
                                onSuccessCallback(data);
                            }
                        } else {
                            if (errorElementId) {
                                const errorDiv = document.getElementById(errorElementId);
                                if (errorDiv) {
                                    errorDiv.innerText = data.message || 'Có lỗi xảy ra';
                                    errorDiv.style.display = 'block';
                                }
                            } else {
                                alert(data.message || 'Có lỗi xảy ra');
                            }
                        }
                    })

                    .catch(err => {
                        if (err.errors) {
                            showValidationErrors(form, err.errors);
                        } else {
                            if (errorElementId) {
                                const errorDiv = document.getElementById(errorElementId);
                                if (errorDiv) {
                                    errorDiv.innerText = 'Lỗi server, thử lại sau.';
                                    errorDiv.style.display = 'block';
                                }
                            } else {
                                alert('Lỗi server, thử lại sau.');
                            }
                            console.error(err);
                        }
                    });
            });
        }

        // Đăng ký ajax form cho từng modal
        // Ajax form thêm nhanh danh mục
        document.getElementById('quickCategoryForm').addEventListener('submit', function(e) {
          e.preventDefault();

          const form = this;
          const formData = new FormData(form);
          const url = form.action;

          fetch(url, {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': formData.get('_token'),
            },
            body: formData,
          })
          .then(res => {
            if (!res.ok) {
              if (res.status === 422) return res.json().then(data => Promise.reject(data));
              throw new Error('Lỗi server');
            }
            return res.json();
          })
          .then(data => {
            if (data.success && data.category) {
              // Ẩn modal
              const modalEl = document.getElementById('addCategoryModal');
              const bsModal = bootstrap.Modal.getInstance(modalEl);
              if (bsModal) bsModal.hide();

              // Thêm checkbox mới vào danh sách danh mục
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

              form.reset();
            } else {
              alert(data.message || 'Có lỗi xảy ra');
            }
          })
          .catch(err => {
            if (err.errors) {
              // Xử lý validation lỗi nếu cần
              console.error(err.errors);
            } else {
              alert('Lỗi server, thử lại sau.');
              console.error(err);
            }
          });
        });

        // Tương tự ajax form thêm nhanh vùng miền (radio)
        document.getElementById('quickRegionForm').addEventListener('submit', function(e) {
          e.preventDefault();

          const form = this;
          const formData = new FormData(form);
          const url = form.action;

          fetch(url, {
            method: 'POST',
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': formData.get('_token'),
            },
            body: formData,
          })
          .then(res => {
            if (!res.ok) {
              if (res.status === 422) return res.json().then(data => Promise.reject(data));
              throw new Error('Lỗi server');
            }
            return res.json();
          })
          .then(data => {
            if (data.success && data.region) {
              // Ẩn modal
              const modalEl = document.getElementById('addRegionModal');
              const bsModal = bootstrap.Modal.getInstance(modalEl);
              if (bsModal) bsModal.hide();

              // Thêm radio mới vào vùng miền
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

              form.reset();
            } else {
              alert(data.message || 'Có lỗi xảy ra');
            }
          })
          .catch(err => {
            if (err.errors) {
              console.error(err.errors);
            } else {
              alert('Lỗi server, thử lại sau.');
              console.error(err);
            }
          });
        });

        ajaxFormSubmit('quickAttributeForm', 'addAttributeModal', null, null, null, (data) => {
            if (data.attribute) {
                const container = document.querySelector('.attribute-filters');
                if (!container) return;

                const valuesHtml = (data.attributeValues || []).map(val => `
            <label class="form-check form-check-inline d-block">
                <input
                    class="form-check-input attribute-value-checkbox"
                    type="checkbox"
                    data-attrid="${data.attribute.id}"
                    value="${val.id}"
                    name="attribute_values_checkbox[${data.attribute.id}][]"
                    checked
                >
                <span class="form-check-label">${val.value}</span>
            </label>
        `).join('');

                const attrGroup = document.createElement('div');
                attrGroup.className = 'attribute-group mb-3';
                attrGroup.dataset.attrName = data.attribute.name.toLowerCase();

                attrGroup.innerHTML = `
            <button class="btn btn-link p-0 mb-1" type="button" data-bs-toggle="collapse" data-bs-target="#attr-${data.attribute.id}" aria-expanded="true" aria-controls="attr-${data.attribute.id}">
                ${data.attribute.name} (${data.attributeValues ? data.attributeValues.length : 0})
            </button>
            <div class="collapse show" id="attr-${data.attribute.id}">
                <div class="values-list" style="max-height: 150px; overflow-y:auto; border:1px solid #ddd; padding:8px; border-radius:4px;">
                    ${valuesHtml}
                </div>
            </div>
        `;

                container.appendChild(attrGroup);

                const attrValueSelect = document.querySelector('select[name="attribute_id"]');
                if (attrValueSelect) {
                    const optionExists = [...attrValueSelect.options].some(opt => opt.value == data
                        .attribute.id);
                    if (!optionExists) {
                        const option = document.createElement('option');
                        option.value = data.attribute.id;
                        option.text = data.attribute.name;
                        option.selected = true;
                        attrValueSelect.appendChild(option);
                    }
                }

                registerCheckboxListeners();
                // generateVariants(); // Chỉ gọi khi has_variants là true
            }
        });
        ajaxFormSubmit(
            'quickAttributeValueForm',
            'addAttributeValueModal',
            null,
            null,
            'attr-value-error',
            (data) => {
                if (data.attributeValues && data.attribute_id && data.attribute_name) {
                    const container = document.querySelector('.attribute-filters');
                    if (!container) return;

                    const valuesHtml = data.attributeValues
                        .map(
                            (val) => `
      <label class="form-check form-check-inline d-block">
        <input
          class="form-check-input attribute-value-checkbox"
          type="checkbox"
          data-attrid="${data.attribute_id}"
          value="${val.id}"
          name="attribute_values_checkbox[${data.attribute_id}][]"
          checked
        >
        <span class="form-check-label">${val.value}</span>
      </label>
    `
                        )
                        .join('');

                    const attrNameLower = data.attribute_name.toLowerCase();

                    let attrGroup = container.querySelector(
                        `.attribute-group[data-attr-name="${attrNameLower}"]`);

                    if (!attrGroup) {
                        // Nếu chưa có nhóm, tạo mới và append
                        attrGroup = document.createElement('div');
                        attrGroup.className = 'attribute-group mb-3';
                        attrGroup.dataset.attrName = attrNameLower;

                        // Tạo HTML nhóm với tất cả giá trị mới
                        attrGroup.innerHTML = `
    <button class="btn btn-link p-0 mb-1" type="button" data-bs-toggle="collapse" data-bs-target="#attr-${data.attribute_id}" aria-expanded="true" aria-controls="attr-${data.attribute_id}">
        ${data.attribute_name} (${data.attributeValues.length})
    </button>
    <div class="collapse show" id="attr-${data.attribute_id}">
        <div class="values-list" style="max-height: 150px; overflow-y:auto; border:1px solid #ddd; padding:8px; border-radius:4px;">
            ${data.attributeValues.map(val => `
                        <label class="form-check form-check-inline d-block">
                            <input
                                class="form-check-input attribute-value-checkbox"
                                type="checkbox"
                                data-attrid="${data.attribute_id}"
                                value="${val.id}"
                                name="attribute_values_checkbox[${data.attribute_id}][]"
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
                        // Nếu nhóm đã có, chỉ thêm giá trị mới vào container con 'values-list'
                        const valuesList = attrGroup.querySelector('.values-list');
                        if (valuesList) {
                            // Lấy các giá trị id đang có để tránh trùng
                            const existingIds = new Set(
                                Array.from(valuesList.querySelectorAll(
                                    'input.attribute-value-checkbox')).map(input => input.value)
                            );

                            // Thêm giá trị mới (chỉ những giá trị chưa có)
                            data.attributeValues.forEach(val => {
                                if (!existingIds.has(val.id.toString())) {
                                    const label = document.createElement('label');
                                    label.className = 'form-check form-check-inline d-block';
                                    label.innerHTML = `
                <input
                    class="form-check-input attribute-value-checkbox"
                    type="checkbox"
                    data-attrid="${data.attribute_id}"
                    value="${val.id}"
                    name="attribute_values_checkbox[${data.attribute_id}][]"
                    checked
                >
                <span class="form-check-label">${val.value}</span>
            `;
                                    valuesList.appendChild(label);
                                }
                            });

                            // Cập nhật số lượng giá trị hiển thị trên nút
                            const btn = attrGroup.querySelector('button');
                            if (btn) {
                                const totalValues = valuesList.querySelectorAll(
                                    'input.attribute-value-checkbox').length;
                                btn.textContent = `${data.attribute_name} (${totalValues})`;
                            }
                        }
                    }

                    const attrValueSelect = document.querySelector('select[name="attribute_id"]');
                    if (attrValueSelect) {
                        const optionExists = [...attrValueSelect.options].some((opt) => opt.value == data
                            .attribute_id);
                        if (!optionExists) {
                            const option = document.createElement('option');
                            option.value = data.attribute_id;
                            option.text = data.attribute_name;
                            option.selected = true;
                            attrValueSelect.appendChild(option);
                        }
                    }

                    registerCheckboxListeners();
                    // generateVariants(); // Chỉ gọi khi has_variants là true
                }
            }
        );
        // Đăng ký sự kiện checkbox để gọi generateVariants khi checkbox thay đổi
        // đã chuyển vào toggleProductTypeFields

        // Hàm sinh biến thể (bạn cần viết hoặc dùng logic riêng)
        function generateVariants() {
            console.log('Biến thể được cập nhật');
            // TODO: Thực hiện sinh biến thể theo checkbox đã chọn
        }

        // Khởi tạo sự kiện checkbox khi load trang
        registerCheckboxListeners(); // Giữ lại để đăng ký listener cho các checkbox hiện có
    });
</script>

@endsection
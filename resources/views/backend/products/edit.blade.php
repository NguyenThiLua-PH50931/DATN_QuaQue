@extends('layouts.backend')

@section('title', 'Sửa sản phẩm')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-sm-10 m-auto">

                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="title-header option-title d-sm-flex d-block">
                                    <h5>Sửa Sản Phẩm</h5>
                                    <div class="right-options">
                                        <ul>
                                            <li>
                                                <a class="btn btn-solid" href="{{ route('admin.products.index') }}">Quay lại</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <form id="main-form" action="{{ route('admin.products.update', $product->slug) }}" method="POST" enctype="multipart/form-data" class="theme-form theme-form-2 mega-form">
                                    @csrf
                                    {{-- Route dùng POST nên không cần @method('PUT') --}}

                                    {{-- Tên sản phẩm --}}
                                    <div class="mb-3">
                                        <label class="form-label">Tên sản phẩm</label>
                                        <input type="text" class="form-control" name="name" value="{{ old('name', $product->name) }}">
                                        @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>

                                    {{-- Danh mục --}}
                                    <div class="mb-3 d-flex align-items-center gap-2">
                                        <label class="form-label mb-0 me-2">Danh mục</label>
                                        <select name="category_id" class="form-select" style="width: auto; flex:1;">
                                            <option value="">--Chọn danh mục--</option>
                                            @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-link px-2" style="color: #0da487" data-bs-toggle="modal" data-bs-target="#addCategoryModal">+ Thêm danh mục</button>
                                    </div>

                                    {{-- Vùng miền --}}
                                    <div class="mb-3 d-flex align-items-center gap-2">
                                        <label class="form-label mb-0 me-2">Vùng miền</label>
                                        <select name="region_id" class="form-select" style="width: auto; flex:1;">
                                            <option value="">--Chọn Vùng miền--</option>
                                            @foreach($regions as $region)
                                            <option value="{{ $region->id }}" {{ old('region_id', $product->region_id) == $region->id ? 'selected' : '' }}>{{ $region->name }}</option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="btn btn-link px-2" style="color: #0da487" data-bs-toggle="modal" data-bs-target="#addRegionModal">+ Thêm vùng miền</button>
                                    </div>

                                    {{-- Ảnh đại diện hiện tại --}}
                                    <div class="mb-3">
                                        <label class="form-label">Ảnh đại diện hiện tại</label>
                                        @if($product->image)
                                        <div class="mb-2">
                                            <img src="{{ asset('storage/'.$product->image) }}" alt="Ảnh đại diện" style="max-width: 150px;">
                                        </div>
                                        @endif
                                        <label class="form-label">Thay đổi ảnh đại diện</label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                        @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>

                                    {{-- Ảnh mô tả hiện tại --}}
                                    <div class="mb-3">
                                        <label class="form-label">Ảnh mô tả hiện tại</label>
                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                            @foreach($product->product_images as $img)
                                            <div style="position: relative;">
                                                <img src="{{ asset('storage/'.$img->image_url) }}" alt="Ảnh mô tả" style="max-width: 100px; max-height: 100px; object-fit: cover;">
                                                {{-- Có thể thêm nút xóa ajax sau --}}
                                            </div>
                                            @endforeach
                                        </div>
                                        <label class="form-label">Thêm ảnh mô tả (nhiều ảnh)</label>
                                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
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

                                    {{-- Chọn thuộc tính và giá trị --}}
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h6 class="fw-bold mb-3">Chọn thuộc tính và giá trị cho biến thể</h6>
                                            <input type="text" class="form-control mb-3" id="filter-attributes" placeholder="Tìm thuộc tính...">

                                            <div class="attribute-filters" style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 4px;">
                                                @foreach($attributes as $attr)
                                                <div class="attribute-group mb-3" data-attr-name="{{ strtolower($attr->name) }}">
                                                    <button class="btn btn-link p-0 mb-1" type="button" data-bs-toggle="collapse" data-bs-target="#attr-{{ $attr->id }}" aria-expanded="true" aria-controls="attr-{{ $attr->id }}">
                                                        {{ $attr->name }} ({{ count($attr->values) }})
                                                    </button>
                                                    <div class="collapse show" id="attr-{{ $attr->id }}">
                                                        <div class="values-list" style="max-height: 150px; overflow-y:auto; border:1px solid #ddd; padding:8px; border-radius:4px;">
                                                            @foreach($attr->values as $val)
                                                            <label class="form-check form-check-inline d-block">
                                                                @php
                                                                $checked = false;
                                                                foreach ($product->variants as $variant) {
                                                                $valIds = $variant->attributeValues->pluck('id')->toArray();
                                                                if (in_array($val->id, $valIds)) {
                                                                $checked = true;
                                                                break;
                                                                }
                                                                }
                                                                @endphp
                                                                <input
                                                                    class="form-check-input attribute-value-checkbox"
                                                                    type="checkbox"
                                                                    data-attrid="{{ $attr->id }}"
                                                                    value="{{ $val->id }}"
                                                                    name="attribute_values_checkbox[{{ $attr->id }}][]"
                                                                    {{ old("attribute_values_checkbox.$attr->id") && in_array($val->id, old("attribute_values_checkbox.$attr->id")) ? 'checked' : ($checked ? 'checked' : '') }}>
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
                                                $oldVariants = old('variants', $product->variants->toArray());
                                                @endphp
                                                @foreach($oldVariants as $i => $variant)
                                                <div class="variant-row border rounded p-3 mb-3 bg-white position-relative">
                                                    @php
                                                    $valIds = [];
                                                    if (isset($variant['attribute_value_ids'])) {
                                                    $valIds = $variant['attribute_value_ids'];
                                                    } elseif (isset($variant['attribute_value_id'])) {
                                                    $valIds = [$variant['attribute_value_id']];
                                                    } elseif (is_object($variant) && $variant->relationLoaded('attributeValues')) {
                                                    $valIds = $variant->attributeValues->pluck('id')->toArray();
                                                    } elseif (isset($variant['attributeValues']) && is_array($variant['attributeValues'])) {
                                                    $valIds = array_column($variant['attributeValues'], 'id');
                                                    }
                                                    @endphp

                                                    @foreach($valIds as $valId)
                                                    <input type="hidden" name="variants[{{$i}}][attribute_value_ids][]" value="{{ $valId }}">
                                                    @endforeach

                                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 btn-remove-variant" title="Xóa biến thể">&times;</button>

                                                    <div class="mb-2 fw-semibold">
                                                        Giá trị thuộc tính:
                                                        <span>
                                                            @php
                                                            $names = [];
                                                            foreach ($valIds as $vid) {
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
                                                            <input type="number" name="variants[{{$i}}][price]" min="0" step="0.01" class="form-control" value="{{ old("variants.$i.price", $variant['price'] ?? '') }}" required>
                                                            @error("variants.$i.price")<small class="text-danger">{{ $message }}</small>@enderror
                                                        </div>
                                                        <div class="col-6 col-md-3">
                                                            <label class="form-label">Tồn kho</label>
                                                            <input type="number" name="variants[{{$i}}][stock]" min="0" class="form-control" value="{{ old("variants.$i.stock", $variant['stock'] ?? '') }}" required>
                                                            @error("variants.$i.stock")<small class="text-danger">{{ $message }}</small>@enderror
                                                        </div>
                                                        <div class="col-6 col-md-3">
                                                            <label class="form-label">SKU</label>
                                                            <input type="text" name="variants[{{$i}}][sku]" class="form-control sku-auto" readonly value="{{ old("variants.$i.sku", $variant['sku'] ?? '') }}">
                                                            @error("variants.$i.sku")<small class="text-danger">{{ $message }}</small>@enderror
                                                        </div>
                                                        <div class="col-6 col-md-3">
                                                            <label class="form-label">Ảnh biến thể hiện tại</label>
                                                            @if(!empty($variant['image'] ?? $variant['image_url'] ?? ''))
                                                            <div class="mb-2">
                                                                <img src="{{ asset('storage/' . ($variant['image'] ?? $variant['image_url'])) }}" alt="Ảnh biến thể" style="max-width: 100px;">
                                                            </div>
                                                            @endif
                                                            <input type="hidden" name="variants[{{$i}}][old_image]" value="{{ old("variants.$i.old_image", $variant['image'] ?? '') }}">
                                                            <label class="form-label">Thay đổi ảnh biến thể</label>
                                                            <input type="file" name="variants[{{$i}}][image]" class="form-control" accept="image/*">
                                                            @error("variants.$i.image")<small class="text-danger">{{ $message }}</small>@enderror
                                                        </div>
                                                        <div class="col-12">
                                                            <label class="form-label">Mô tả biến thể</label>
                                                            <textarea name="variants[{{$i}}][description]" rows="2" class="form-control variant-description-editor">{{ old("variants.$i.description", $variant['description'] ?? '') }}</textarea>
                                                            @error("variants.$i.description")<small class="text-danger">{{ $message }}</small>@enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Trạng thái --}}
                                    <div class="mb-3">
                                        <label class="form-label">Kích hoạt</label>
                                        <select name="active" class="form-select">
                                            <option value="1" {{ old('active', $product->active) == 1 ? 'selected' : '' }}>Có</option>
                                            <option value="0" {{ old('active', $product->active) == 0 ? 'selected' : '' }}>Không</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="variants[{{$i}}][old_image]" value="{{ old("variants.$i.old_image", $variant['image'] ?? '') }}">

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
                    <input type="text" name="name" class="form-control" placeholder="Tên danh mục mới" required>
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
                    <input type="text" name="name" class="form-control" placeholder="Tên vùng miền mới" required>
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
<div class="modal fade" id="addAttributeModal" tabindex="-1" aria-labelledby="addAttributeLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="quickAttributeForm" method="POST" action="{{ route('admin.attributes.storeQuick') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAttributeLabel">Thêm thuộc tính mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control mb-2" placeholder="Tên thuộc tính" required>
                    <input type="text" name="values" class="form-control" placeholder="Giá trị (cách nhau dấu phẩy)" required>
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
<div class="modal fade" id="addAttributeValueModal" tabindex="-1" aria-labelledby="addAttributeValueLabel" aria-hidden="true">
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
                        @foreach($attributes as $attr)
                        <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                        @endforeach
                    </select>
                    <label>Giá trị mới</label>
                    <input type="text" name="value" class="form-control" placeholder="Nhập giá trị mới" required>
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
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {

        // ================== Khởi tạo CKEditor cho mô tả chính ==================
        function initMainEditor() {
            const mainDesc = document.getElementById('main-description');
            if (mainDesc && !mainDesc.classList.contains('ck-editor-initialized')) {
                ClassicEditor.create(mainDesc).then(editor => {
                    editor.ui.view.editable.element.style.color = '#222';
                    editor.ui.view.editable.element.style.background = '#fff';
                    mainDesc.classList.add('ck-editor-initialized');
                });
            }
        }

        // ================== Khởi tạo CKEditor cho mô tả biến thể ==================
        let variantEditors = [];

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

        // ================== Xóa CKEditor cho biến thể (trước khi rebuild) ==================
        function destroyVariantEditors() {
            variantEditors.forEach(editor => editor.destroy());
            variantEditors = [];
            document.querySelectorAll('.variant-description-editor').forEach(textarea => {
                textarea.classList.remove('ck-editor-initialized');
            });
        }

        // ================== Hàm tính tích Descartes (Cartesian product) ==================
        function cartesianProduct(arr) {
            return arr.reduce((a, b) =>
                a.flatMap(d => b.map(e => [...d, e])), [
                    []
                ]
            );
        }

        // ================== Hàm lọc bỏ dấu tiếng Việt ==================
        function removeVietnameseTones(str) {
            let from = "áàảãạăắằẳẵặâấầẩẫậđéèẻẽẹêếềểễệíìỉĩịóòỏõọôốồổỗộơớờởỡợúùủũụưứừửữựýỳỷỹỵ";
            let to = "aaaaaaaaaaaaaaaaadeeeeeeeeeeeiiiiiooooooooooooooooouuuuuuuuuuuyyyyy";
            str = str.toLowerCase();
            for (let i = 0; i < from.length; i++) {
                str = str.replace(new RegExp(from[i], 'g'), to[i]);
            }
            return str;
        }

        // ================== Filter tìm thuộc tính (checkbox group) ==================
        const filterInput = document.getElementById('filter-attributes');
        filterInput.addEventListener('input', () => {
            const filterRaw = filterInput.value.trim().toLowerCase();
            const filter = removeVietnameseTones(filterRaw);

            document.querySelectorAll('.attribute-group').forEach(group => {
                const attrNameRaw = group.dataset.attrName;
                const attrName = removeVietnameseTones(attrNameRaw);
                if (attrName.includes(filter)) {
                    group.style.display = '';
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

        // ================== Cập nhật sự kiện change checkbox để sinh biến thể ==================
        function registerCheckboxListeners() {
            document.querySelectorAll('.attribute-value-checkbox').forEach(cb => {
                cb.removeEventListener('change', generateVariants);
                cb.addEventListener('change', generateVariants);
            });
        }

        // ================== Hiển thị / ẩn nút xóa biến thể ==================
        function showRemoveButtons() {
            const rows = document.querySelectorAll('.variant-row');
            rows.forEach(row => {
                const btn = row.querySelector('.btn-remove-variant');
                if (btn) btn.style.display = rows.length > 1 ? 'inline-block' : 'none';
            });
        }

        // ================== Xóa biến thể khi click nút xóa ==================
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-remove-variant')) {
                e.target.closest('.variant-row').remove();
                showRemoveButtons();
                updateSkuAll();
            }
        });

        // ================== Tự động sinh SKU cho biến thể ==================
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

        // ================== Sinh biến thể từ checkbox đã chọn ==================
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

            // Xóa biến thể thủ công (nếu muốn giữ thì nâng cấp sau)
            variantArea.innerHTML = '';

            const attrArrays = Object.values(attrMap);
            if (attrArrays.length === 0) {
                showRemoveButtons();
                initVariantEditors();
                updateSkuAll();
                return;
            }

            const variantsComb = cartesianProduct(attrArrays);

            variantsComb.forEach((combo, idx) => {
                const ids = combo.map(c => c.id);
                const names = combo.map(c => c.text);

                const html = `
        <div class="variant-row border rounded p-3 mb-3 bg-white position-relative">
          ${ids.map(id => `<input type="hidden" name="variants[${idx}][attribute_value_ids][]" value="${id}">`).join('')}
          <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2 btn-remove-variant" title="Xóa biến thể">&times;</button>
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
        </div>
        `;
                variantArea.insertAdjacentHTML('beforeend', html);
            });

            showRemoveButtons();
            initVariantEditors();
            updateSkuAll();
        }

        // ================== Ajax submit form modal chung ==================
        function ajaxFormSubmit(formId, modalId, selectName, optionKeyName, errorElementId, onSuccessCallback) {
            const form = document.getElementById(formId);
            if (!form) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                const url = form.action;

                if (errorElementId) {
                    const errorDiv = document.getElementById(errorElementId);
                    if (errorDiv) {
                        errorDiv.innerText = '';
                        errorDiv.style.display = 'none';
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
                            if (res.status === 422) return res.json().then(data => Promise.reject(data));
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
                                const select = document.querySelector(`select[name="${selectName}"]`);
                                if (select) {
                                    const newOption = document.createElement('option');
                                    newOption.value = data[optionKeyName].id;
                                    newOption.text = data[optionKeyName].name || data[optionKeyName].value || '';
                                    newOption.selected = true;
                                    select.appendChild(newOption);
                                    select.dispatchEvent(new Event('change'));
                                }
                            }

                            if (formId === 'quickAttributeForm' && data.attribute) {
                                updateAttributeFilterUI(data.attribute, data.attributeValues || []);
                            }

                            if (formId === 'quickAttributeValueForm' && data.attributeValues && data.attribute_id && data.attribute_name) {
                                updateAttributeValueFilterUI(data.attribute_id, data.attribute_name, data.attributeValues);
                            }

                            form.reset();
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

        // ================== Hiển thị lỗi validate ajax ==================
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
                        errorDiv = input.nextElementSibling && input.nextElementSibling.classList.contains('invalid-feedback') ?
                            input.nextElementSibling : null;
                    }
                    if (errorDiv) {
                        errorDiv.innerText = messages.join(', ');
                        errorDiv.style.display = 'block';
                    }
                }
            }
        }

        // ================== Cập nhật UI thêm thuộc tính mới ==================
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
            generateVariants();
        }

        // ================== Cập nhật UI thêm giá trị thuộc tính mới ==================
        function updateAttributeValueFilterUI(attributeId, attributeName, attributeValues) {
            const container = document.querySelector('.attribute-filters');
            if (!container) return;

            const valuesHtml = attributeValues.map(val => `
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
        `).join('');

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
                        ${valuesHtml}
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
            generateVariants();
        }

        // ================== Đăng ký ajax submit cho các form modal ==================
        ajaxFormSubmit('quickCategoryForm', 'addCategoryModal', 'category_id', 'category', 'cat-error');
        ajaxFormSubmit('quickRegionForm', 'addRegionModal', 'region_id', 'region');
        ajaxFormSubmit('quickAttributeForm', 'addAttributeModal', null, null, null);
        ajaxFormSubmit('quickAttributeValueForm', 'addAttributeValueModal', null, null, 'attr-value-error');

        // ================== Khởi tạo ban đầu ==================
        initMainEditor();
        initVariantEditors();
        showRemoveButtons();
        updateSkuAll();
        registerCheckboxListeners();

    });
</script>

@endsection
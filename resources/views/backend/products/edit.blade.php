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
                        @if(session('info'))
                        <div class="alert alert-info">{{ session('info') }}</div>
                        @endif
                        @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        {{-- BLOCK 1: THÔNG TIN CƠ BẢN --}}
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="title-header option-title d-sm-flex d-block">
                                    <h5>Sửa Sản Phẩm: {{ $product->name }}</h5>
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
                                        <button type="button" class="btn btn-link px-2" data-bs-toggle="modal" data-bs-target="#addCategoryModal">+ Thêm</button>
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
                                        <button type="button" class="btn btn-link px-2" data-bs-toggle="modal" data-bs-target="#addRegionModal">+ Thêm</button>
                                    </div>

                                    {{-- Ảnh đại diện --}}
                                    <div class="mb-3">
                                        <label class="form-label">Ảnh đại diện hiện tại</label><br>
                                        @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="" style="max-width:120px;">
                                        @else
                                        <span class="text-muted">Chưa có ảnh</span>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Đổi ảnh đại diện</label>
                                        <input type="file" name="image" class="form-control" accept="image/*">
                                        @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                    {{-- Ảnh mô tả hiện tại (gallery) --}}
                                    <div class="mb-3">
                                        <label class="form-label">Ảnh mô tả hiện tại</label>
                                        <div class="row">
                                            @if($product->images && $product->images->count())
                                            @foreach($product->images as $img)
                                            <div class="col-auto mb-2" id="desc-img-{{ $img->id }}">
                                                <div style="position:relative; display:inline-block;">
                                                    <img src="{{ asset('storage/' . $img->image_url) }}" style="width:80px; border:1px solid #eee; border-radius:5px;">
                                                    <button type="button"
                                                        class="btn btn-xs btn-danger btn-remove-desc-img"
                                                        data-id="{{ $img->id }}"
                                                        style="position:absolute; top:3px; right:3px; padding:2px 7px; border-radius:100%; line-height:1;">
                                                        &times;
                                                    </button>
                                                </div>
                                            </div>
                                            @endforeach
                                            @else
                                            <span class="text-muted ms-2">Chưa có ảnh mô tả</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Upload ảnh mô tả mới --}}
                                    <div class="mb-3">
                                        <label class="form-label">Thêm ảnh mô tả mới (có thể chọn nhiều ảnh)</label>
                                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                                    </div>

                                    {{-- Xuất xứ --}}
                                    <div class="mb-3">
                                        <label class="form-label">Xuất xứ</label>
                                        <input type="text" name="origin" class="form-control" value="{{ old('origin', $product->origin) }}">
                                        @error('origin')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>

                                    {{-- Mô tả --}}
                                    <div class="mb-3">
                                        <label class="form-label">Mô tả sản phẩm</label>
                                        <textarea name="description" id="main-description" class="form-control" rows="4">{{ old('description', $product->description) }}</textarea>
                                        @error('description')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>

                                    {{-- CHỌN THUỘC TÍNH và GIÁ TRỊ --}}
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h6 class="fw-bold mb-2">Chọn thuộc tính và giá trị cho biến thể</h6>
                                            <div class="row gy-2 gx-3">
                                                @foreach($attributes as $attr)
                                                <div class="col-6 col-md-3">
                                                    <label class="form-label mb-1">{{ $attr->name }}</label>
                                                    <select class="form-select form-select-sm attribute-value-select" data-attrid="{{ $attr->id }}" multiple style="min-height: 70px;">
                                                        @foreach($attr->values as $val)
                                                        <option value="{{ $val->id }}">{{ $val->value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        <a href="#" class="btn btn-link p-0 mt-3" data-bs-toggle="modal" data-bs-target="#addAttributeModal">+ Thêm thuộc tính</a>
                                    </div>

                                    {{-- BIẾN THỂ --}}
                                    <div class="card mb-4 mt-3">
                                        <div class="card-body bg-light">
                                            <h5 class="mb-3 mt-3 ms-3" style="color: #4a5568;">Biến thể sản phẩm</h5>
                                            @error('variants')<small class="text-danger d-block mb-2">{{ $message }}</small>@enderror

                                            <button type="button" class="btn btn-primary mb-3 ms-3" id="generate-variants-btn">
                                                Sinh biến thể từ thuộc tính đã chọn
                                            </button>

                                            <div id="variants-list">
                                                {{-- Nếu submit lỗi thì hiển thị lại --}}
                                                @php
                                                $oldVariants = old('variants', []);
                                                @endphp
                                                @if($oldVariants)
                                                @foreach($oldVariants as $i => $variant)
                                                <div class="card mb-4 mt-3">
                                                    <div class="card-body bg-light">
                                                        <h5 class="mb-3 mt-3 ms-3" style="color: #4a5568;">Biến thể sản phẩm</h5>
                                                        @error('variants')<small class="text-danger d-block mb-2">{{ $message }}</small>@enderror

                                                        <button type="button" class="btn btn-primary mb-3 ms-3" id="generate-variants-btn">
                                                            Sinh biến thể từ thuộc tính đã chọn
                                                        </button>

                                                        <div id="variants-list">
                                                            {{-- Nếu submit lỗi thì hiển thị lại --}}
                                                            @php
                                                            $oldVariants = old('variants', []);
                                                            @endphp
                                                            @if($oldVariants)
                                                            @foreach($oldVariants as $i => $variant)
                                                            <div class="variant-row border p-3 mb-3 bg-white rounded">
                                                                <input type="hidden" name="variants[{{$i}}][id]" value="{{ $variant['id'] ?? '' }}">
                                                                <input type="hidden" name="variants[{{$i}}][attribute_value_id]" value="{{ $variant['attribute_value_id'] ?? '' }}">
                                                                <input type="hidden" name="variants[{{$i}}][attribute_value_name]" value="{{ $variant['attribute_value_name'] ?? '' }}">
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Giá trị thuộc tính</label>
                                                                    <input type="text" class="form-control" readonly value="{{ $variant['attribute_value_name'] ?? '' }}">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Giá bán</label>
                                                                    <input type="number" class="form-control" name="variants[{{$i}}][price]" min="0" step="0.01" value="{{ $variant['price'] ?? '' }}">
                                                                    @error("variants.$i.price")<small class="text-danger">{{ $message }}</small>@enderror
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Tồn kho</label>
                                                                    <input type="number" class="form-control" name="variants[{{$i}}][stock]" min="0" value="{{ $variant['stock'] ?? '' }}">
                                                                    @error("variants.$i.stock")<small class="text-danger">{{ $message }}</small>@enderror
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">SKU</label>
                                                                    <input type="text" class="form-control sku-auto" name="variants[{{$i}}][sku]" readonly value="{{ $variant['sku'] ?? '' }}">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Ảnh biến thể</label>
                                                                    @if(!empty($variant['image']))
                                                                    <img src="{{ asset('storage/' . $variant['image']) }}" style="max-width:60px;">
                                                                    @endif
                                                                    <input type="file" name="variants[{{$i}}][image]" class="form-control" accept="image/*">
                                                                    @error("variants.$i.image")<small class="text-danger">{{ $message }}</small>@enderror
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Mô tả biến thể</label>
                                                                    <textarea class="form-control variant-description-editor" name="variants[{{$i}}][description]" rows="2">{{ $variant['description'] ?? '' }}</textarea>
                                                                    @error("variants.$i.description")<small class="text-danger">{{ $message }}</small>@enderror
                                                                </div>
                                                                <div class="mb-2">
                                                                    <button type="button" class="btn btn-danger btn-remove-variant">Xóa biến thể</button>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            @else
                                                            @foreach($product->variants as $i => $variant)
                                                            <div class="variant-row border p-3 mb-3 bg-white rounded">
                                                                <input type="hidden" name="variants[{{$i}}][id]" value="{{ $variant->id }}">
                                                                <input type="hidden" name="variants[{{$i}}][attribute_value_id]" value="{{ $variant->attribute_value_id }}">
                                                                <input type="hidden" name="variants[{{$i}}][attribute_value_name]" value="{{ $variant->name }}">
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Giá trị thuộc tính</label>
                                                                    <input type="text" class="form-control" readonly value="{{ $variant->name }}">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Giá bán</label>
                                                                    <input type="number" class="form-control" name="variants[{{$i}}][price]" min="0" step="0.01" value="{{ $variant->price }}">
                                                                    @error("variants.$i.price")<small class="text-danger">{{ $message }}</small>@enderror
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Tồn kho</label>
                                                                    <input type="number" class="form-control" name="variants[{{$i}}][stock]" min="0" value="{{ $variant->stock }}">
                                                                    @error("variants.$i.stock")<small class="text-danger">{{ $message }}</small>@enderror
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">SKU</label>
                                                                    <input type="text" class="form-control sku-auto" name="variants[{{$i}}][sku]" readonly value="{{ $variant->sku }}">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Ảnh biến thể</label>
                                                                    @if($variant->image)
                                                                    <img src="{{ asset('storage/' . $variant->image) }}" style="max-width:60px;">
                                                                    @endif
                                                                    <input type="file" name="variants[{{$i}}][image]" class="form-control" accept="image/*">
                                                                    @error("variants.$i.image")<small class="text-danger">{{ $message }}</small>@enderror
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Mô tả biến thể</label>
                                                                    <textarea class="form-control variant-description-editor" name="variants[{{$i}}][description]" rows="2">{{ $variant->description }}</textarea>
                                                                    @error("variants.$i.description")<small class="text-danger">{{ $message }}</small>@enderror
                                                                </div>
                                                                <div class="mb-2">
                                                                    <button type="button" class="btn btn-danger btn-remove-variant">Xóa biến thể</button>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                @endforeach
                                                @else
                                                @foreach($product->variants as $i => $variant)
                                                <div class="card mb-4 mt-3">
                                                    <div class="card-body bg-light">
                                                        <h5 class="mb-3 mt-3 ms-3" style="color: #4a5568;">Biến thể sản phẩm</h5>
                                                        @error('variants')<small class="text-danger d-block mb-2">{{ $message }}</small>@enderror

                                                        <button type="button" class="btn btn-primary mb-3 ms-3" id="generate-variants-btn">
                                                            Sinh biến thể từ thuộc tính đã chọn
                                                        </button>

                                                        <div id="variants-list">
                                                            {{-- Nếu submit lỗi thì hiển thị lại --}}
                                                            @php
                                                            $oldVariants = old('variants', []);
                                                            @endphp
                                                            @if($oldVariants)
                                                            @foreach($oldVariants as $i => $variant)
                                                            <div class="variant-row border p-3 mb-3 bg-white rounded">
                                                                <input type="hidden" name="variants[{{$i}}][id]" value="{{ $variant['id'] ?? '' }}">
                                                                <input type="hidden" name="variants[{{$i}}][attribute_value_id]" value="{{ $variant['attribute_value_id'] ?? '' }}">
                                                                <input type="hidden" name="variants[{{$i}}][attribute_value_name]" value="{{ $variant['attribute_value_name'] ?? '' }}">
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Giá trị thuộc tính</label>
                                                                    <input type="text" class="form-control" readonly value="{{ $variant['attribute_value_name'] ?? '' }}">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Giá bán</label>
                                                                    <input type="number" class="form-control" name="variants[{{$i}}][price]" min="0" step="0.01" value="{{ $variant['price'] ?? '' }}">
                                                                    @error("variants.$i.price")<small class="text-danger">{{ $message }}</small>@enderror
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Tồn kho</label>
                                                                    <input type="number" class="form-control" name="variants[{{$i}}][stock]" min="0" value="{{ $variant['stock'] ?? '' }}">
                                                                    @error("variants.$i.stock")<small class="text-danger">{{ $message }}</small>@enderror
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">SKU</label>
                                                                    <input type="text" class="form-control sku-auto" name="variants[{{$i}}][sku]" readonly value="{{ $variant['sku'] ?? '' }}">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Ảnh biến thể</label>
                                                                    @if(!empty($variant['image']))
                                                                    <img src="{{ asset('storage/' . $variant['image']) }}" style="max-width:60px;">
                                                                    @endif
                                                                    <input type="file" name="variants[{{$i}}][image]" class="form-control" accept="image/*">
                                                                    @error("variants.$i.image")<small class="text-danger">{{ $message }}</small>@enderror
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Mô tả biến thể</label>
                                                                    <textarea class="form-control variant-description-editor" name="variants[{{$i}}][description]" rows="2">{{ $variant['description'] ?? '' }}</textarea>
                                                                    @error("variants.$i.description")<small class="text-danger">{{ $message }}</small>@enderror
                                                                </div>
                                                                <div class="mb-2">
                                                                    <button type="button" class="btn btn-danger btn-remove-variant">Xóa biến thể</button>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            @else
                                                            @foreach($product->variants as $i => $variant)
                                                            <div class="variant-row border p-3 mb-3 bg-white rounded">
                                                                <input type="hidden" name="variants[{{$i}}][id]" value="{{ $variant->id }}">
                                                                <input type="hidden" name="variants[{{$i}}][attribute_value_id]" value="{{ $variant->attribute_value_id }}">
                                                                <input type="hidden" name="variants[{{$i}}][attribute_value_name]" value="{{ $variant->name }}">
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Giá trị thuộc tính</label>
                                                                    <input type="text" class="form-control" readonly value="{{ $variant->name }}">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Giá bán</label>
                                                                    <input type="number" class="form-control" name="variants[{{$i}}][price]" min="0" step="0.01" value="{{ $variant->price }}">
                                                                    @error("variants.$i.price")<small class="text-danger">{{ $message }}</small>@enderror
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Tồn kho</label>
                                                                    <input type="number" class="form-control" name="variants[{{$i}}][stock]" min="0" value="{{ $variant->stock }}">
                                                                    @error("variants.$i.stock")<small class="text-danger">{{ $message }}</small>@enderror
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">SKU</label>
                                                                    <input type="text" class="form-control sku-auto" name="variants[{{$i}}][sku]" readonly value="{{ $variant->sku }}">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Ảnh biến thể</label>
                                                                    @if($variant->image)
                                                                    <img src="{{ asset('storage/' . $variant->image) }}" style="max-width:60px;">
                                                                    @endif
                                                                    <input type="file" name="variants[{{$i}}][image]" class="form-control" accept="image/*">
                                                                    @error("variants.$i.image")<small class="text-danger">{{ $message }}</small>@enderror
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label fw-bold">Mô tả biến thể</label>
                                                                    <textarea class="form-control variant-description-editor" name="variants[{{$i}}][description]" rows="2">{{ $variant->description }}</textarea>
                                                                    @error("variants.$i.description")<small class="text-danger">{{ $message }}</small>@enderror
                                                                </div>
                                                                <div class="mb-2">
                                                                    <button type="button" class="btn btn-danger btn-remove-variant">Xóa biến thể</button>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Trạng thái --}}
                                    <div class="mb-3">
                                        <label class="form-label">Kích hoạt</label>
                                        <select name="active" class="form-select">
                                            <option value="1" {{ old('active', $product->active)==1 ? 'selected' : '' }}>Có</option>
                                            <option value="0" {{ old('active', $product->active)==0 ? 'selected' : '' }}>Không</option>
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
<div class="modal fade" id="addRegionModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="quickRegionForm" method="POST" action="{{ route('admin.regions.storeQuick') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm vùng miền mới</h5>
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
<div class="modal fade" id="addAttributeModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="quickAttributeForm" method="POST" action="{{ route('admin.attributes.storeQuick') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm thuộc tính mới</h5>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control mb-2" placeholder="Tên thuộc tính" required>
                    <input type="text" name="values" class="form-control" placeholder="Giá trị (cách nhau dấu phẩy)" required>
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
        position: relative;
    }

    .variant-row label {
        color: #212529 !important;
    }

    .btn-remove-variant {
        min-width: 110px;
    }

    .card-body .row .col-6 {
        margin-bottom: 8px;
    }

    .form-label {
        font-size: 14px;
        font-weight: 500;
    }

    .variant-row img {
        margin-bottom: 8px;
        border: 1px solid #e0e7ef;
        background: #fff;
        border-radius: 5px;
    }
</style>
{{-- CKEditor 5 CDN --}}
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    // CKEditor cho mô tả sản phẩm
    let mainCkEditor = null;
    ClassicEditor.create(document.getElementById('main-description')).then(editor => {
        mainCkEditor = editor;
    });

    // CKEditor cho mô tả biến thể
    let variantEditors = [];

    function initVariantEditors() {
        document.querySelectorAll('.variant-description-editor').forEach((textarea) => {
            if (!textarea.classList.contains('ck-editor-initialized')) {
                ClassicEditor.create(textarea).then(editor => {
                    variantEditors.push(editor);
                    textarea.classList.add('ck-editor-initialized');
                });
            }
        });
    }
    initVariantEditors();

    // Hiện/ẩn nút xóa biến thể
    function showRemoveButtons() {
        let rows = document.querySelectorAll('.variant-row');
        rows.forEach((row, idx) => {
            let btn = row.querySelector('.btn-remove-variant');
            if (btn) btn.style.display = (rows.length > 1) ? 'inline-block' : 'none';
        });
    }
    showRemoveButtons();

    // Xóa biến thể
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-variant')) {
            let row = e.target.closest('.variant-row');
            if (row) row.remove();
            showRemoveButtons();
        }
    });

    // Sinh SKU tự động khi nhập giá bán
    document.addEventListener('input', function(e) {
        if (e.target.name && e.target.name.includes('variants') && e.target.name.endsWith('[price]')) {
            let row = e.target.closest('.variant-row');
            let skuInput = row.querySelector('.sku-auto');
            let attrNameInput = row.querySelector('input[readonly]');
            if (skuInput && attrNameInput) {
                let base = 'PRD-' + (new Date()).getTime();
                let val = attrNameInput.value ? '-' + attrNameInput.value.replace(/\s/g, '').toUpperCase().substring(0, 6) : '';
                skuInput.value = base + val;
            }
        }
    });

    // Nếu có chức năng sinh biến thể từ thuộc tính
    let variantIndex = document.querySelectorAll('.variant-row').length;
    let genBtn = document.getElementById('generate-variants-btn');
    if (genBtn) {
        genBtn.onclick = function() {
            let variantsList = document.getElementById('variants-list');
            variantsList.innerHTML = '';
            variantIndex = 0;

            let selects = document.querySelectorAll('.attribute-value-select');
            selects.forEach(function(select) {
                [...select.selectedOptions].forEach(function(option) {
                    let attribute_value_id = option.value;
                    let attribute_value_name = option.text;
                    let html = `
                        <div class="variant-row border p-3 mb-3 bg-white rounded">
                            <input type="hidden" name="variants[${variantIndex}][attribute_value_id]" value="${attribute_value_id}">
                            <input type="hidden" name="variants[${variantIndex}][attribute_value_name]" value="${attribute_value_name}">
                            <div class="mb-2">
                                <label class="form-label fw-bold">Giá trị thuộc tính</label>
                                <input type="text" class="form-control" readonly value="${attribute_value_name}">
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-bold">Giá bán</label>
                                <input type="number" class="form-control" name="variants[${variantIndex}][price]" min="0" step="0.01" value="">
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-bold">Tồn kho</label>
                                <input type="number" class="form-control" name="variants[${variantIndex}][stock]" min="0" value="">
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-bold">SKU</label>
                                <input type="text" class="form-control sku-auto" name="variants[${variantIndex}][sku]" readonly value="">
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-bold">Ảnh biến thể</label>
                                <input type="file" name="variants[${variantIndex}][image]" class="form-control" accept="image/*">
                            </div>
                            <div class="mb-2">
                                <label class="form-label fw-bold">Mô tả biến thể</label>
                                <textarea class="form-control variant-description-editor" name="variants[${variantIndex}][description]" rows="2"></textarea>
                            </div>
                            <div class="mb-2">
                                <button type="button" class="btn btn-danger btn-remove-variant">Xóa biến thể</button>
                            </div>
                        </div>
                    `;
                    variantsList.insertAdjacentHTML('beforeend', html);
                    variantIndex++;
                });
            });
            setTimeout(initVariantEditors, 100);
            showRemoveButtons();
        };
    }
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-desc-img')) {
            if (confirm('Bạn có chắc chắn muốn xóa ảnh này không?')) {
                let imgId = e.target.getAttribute('data-id');
                fetch("{{ route('admin.products.deleteImage') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: imgId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById('desc-img-' + imgId).remove();
                        } else {
                            alert(data.message || 'Xóa thất bại!');
                        }
                    });
            }
        }
    });
</script>

@endsection
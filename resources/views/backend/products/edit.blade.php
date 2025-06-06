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
                                                                           <div class="attribue-space" style="display: flex; ">
                                                <a href="#" class="btn btn-link p-0 mt-3" style="color: #0da487" data-bs-toggle="modal" data-bs-target="#addAttributeModal">+ Thêm thuộc tính</a>
                                                <a href="#" class="btn btn-link p-0 mt-3 ms-3" style="color: #0da487" data-bs-toggle="modal" data-bs-target="#addAttributeValueModal">+ Thêm giá trị thuộc tính</a>
                                            </div>
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
<<<<<<< HEAD

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
=======

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

>>>>>>> main
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
    // ajax them danh muc
    document.addEventListener('DOMContentLoaded', function() {
        const quickCategoryForm = document.getElementById('quickCategoryForm');
        quickCategoryForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(quickCategoryForm);
            const url = quickCategoryForm.action;

            // Xóa lỗi cũ
            document.getElementById('cat-error').innerText = '';

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': formData.get('_token')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Đóng modal
                        const addCategoryModal = bootstrap.Modal.getInstance(document.getElementById('addCategoryModal'));
                        addCategoryModal.hide();

                        // Thêm option mới vào select danh mục
                        const categorySelect = document.querySelector('select[name="category_id"]');
                        const newOption = document.createElement('option');
                        newOption.value = data.category.id; // phải trả về id category mới
                        newOption.text = data.category.name; // phải trả về name category mới
                        newOption.selected = true;
                        categorySelect.appendChild(newOption);

                        // Reset lại form
                        quickCategoryForm.reset();
                    } else {
                        // Hiển thị lỗi (nếu có)
                        document.getElementById('cat-error').innerText = data.message || 'Có lỗi xảy ra';
                    }
                })
                .catch(err => {
                    document.getElementById('cat-error').innerText = 'Lỗi server, thử lại sau.';
                    console.error(err);
                });
        });
    });
    // ajax them vung miền
    document.addEventListener('DOMContentLoaded', function() {
        const quickRegionForm = document.getElementById('quickRegionForm');
        quickRegionForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(quickRegionForm);
            const url = quickRegionForm.action;

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': formData.get('_token')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Đóng modal
                        const addRegionModal = bootstrap.Modal.getInstance(document.getElementById('addRegionModal'));
                        addRegionModal.hide();

                        // Thêm option mới vào select vùng miền
                        const regionSelect = document.querySelector('select[name="region_id"]');
                        const newOption = document.createElement('option');
                        newOption.value = data.region.id;
                        newOption.text = data.region.name;
                        newOption.selected = true;
                        regionSelect.appendChild(newOption);

                        // Reset form
                        quickRegionForm.reset();
                    } else {
                        alert(data.message || 'Có lỗi xảy ra khi thêm vùng miền');
                    }
                })
                .catch(err => {
                    alert('Lỗi server, thử lại sau.');
                    console.error(err);
                });
        });
    });
    // ajax them thuoc tinh
    document.addEventListener('DOMContentLoaded', function() {
        const quickAttributeForm = document.getElementById('quickAttributeForm');
        quickAttributeForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(quickAttributeForm);
            const url = quickAttributeForm.action;

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': formData.get('_token')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Đóng modal
                        const addAttributeModal = bootstrap.Modal.getInstance(document.getElementById('addAttributeModal'));
                        addAttributeModal.hide();

                        // Tạm thời chỉ append vào phần chọn thuộc tính:
                        const container = document.querySelector('.card-body .row.gy-2.gx-3');
                        if (container) {
                            // Tạo 1 select mới
                            const select = document.createElement('select');
                            select.classList.add('form-select', 'form-select-sm', 'attribute-value-select');
                            select.setAttribute('data-attrid', data.attribute.id);
                            select.setAttribute('multiple', '');
                            select.style.minHeight = '70px';

                            data.attribute.values.forEach(val => {
                                const option = document.createElement('option');
                                option.value = val.id;
                                option.text = val.value;
                                select.appendChild(option);
                            });

                            const divCol = document.createElement('div');
                            divCol.classList.add('col-6', 'col-md-3');
                            const label = document.createElement('label');
                            label.classList.add('form-label', 'mb-1');
                            label.innerText = data.attribute.name;

                            divCol.appendChild(label);
                            divCol.appendChild(select);

                            container.appendChild(divCol);
                        }

                        // Reset form
                        quickAttributeForm.reset();
                    } else {
                        alert(data.message || 'Có lỗi xảy ra khi thêm thuộc tính');
                    }
                })
                .catch(err => {
                    alert('Lỗi server, thử lại sau.');
                    console.error(err);
                });
        });
    });
    // ajax them gia tri thuoc tinh
    document.addEventListener('DOMContentLoaded', function() {
        const quickAttributeValueForm = document.getElementById('quickAttributeValueForm');
        quickAttributeValueForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(quickAttributeValueForm);
            const url = quickAttributeValueForm.action;

            // Xóa lỗi cũ
            const errorDiv = document.getElementById('attr-value-error');
            errorDiv.innerText = '';
            errorDiv.style.display = 'none';

            fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': formData.get('_token')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Đóng modal
                        const addAttributeValueModal = bootstrap.Modal.getInstance(document.getElementById('addAttributeValueModal'));
                        addAttributeValueModal.hide();

                        // Thêm giá trị mới vào select thuộc tính tương ứng
                        const attrId = data.attribute_id;
                        const value = data.attributeValue.value;
                        const valueId = data.attributeValue.id;

                        // Tìm select thuộc tính tương ứng (data-attrid)
                        const select = document.querySelector(`select.attribute-value-select[data-attrid="${attrId}"]`);
                        if (select) {
                            const option = document.createElement('option');
                            option.value = valueId;
                            option.text = value;
                            option.selected = true;
                            select.appendChild(option);
                        }

                        // Reset form
                        quickAttributeValueForm.reset();
                    } else {
                        errorDiv.innerText = data.message || 'Có lỗi xảy ra';
                        errorDiv.style.display = 'block';
                    }
                })
                .catch(err => {
                    errorDiv.innerText = 'Lỗi server, thử lại sau.';
                    errorDiv.style.display = 'block';
                    console.error(err);
                });
        });
    });
</script>

@endsection
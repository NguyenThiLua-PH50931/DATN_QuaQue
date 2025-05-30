@extends('layouts.backend')

@section('title', 'Thêm vùng miền mới')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Thêm vùng miền mới</h5>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.regions.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Name Input --}}
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên vùng miền <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Buttons --}}
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary me-2">Lưu</button>
                                <a href="{{ route('admin.regions.index') }}" class="btn btn-secondary">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@includeIf('backend.footer')
@endsection

@push('scripts')
    {{-- Add any necessary scripts for this page here --}}
@endpush
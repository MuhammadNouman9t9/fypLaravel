@extends('admin.layout')

@section('title', 'Create Product')

@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Back to Products</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4 p-md-5">
            <h2 class="h4 mb-4">Create New Product</h2>

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Product Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="form-control">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Brand *</label>
                        <input type="text" name="brand" value="{{ old('brand') }}" required class="form-control">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku') }}" class="form-control">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Price *</label>
                        <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0" required class="form-control">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Compare At Price</label>
                        <input type="number" name="compare_at_price" value="{{ old('compare_at_price') }}" step="0.01" min="0" class="form-control">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Availability Status</label>
                        <select name="availability_status" class="form-select">
                            <option value="in_stock" {{ old('availability_status') === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                            <option value="out_of_stock" {{ old('availability_status') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            <option value="pre_order" {{ old('availability_status') === 'pre_order' ? 'selected' : '' }}>Pre Order</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Main Product Image</label>
                        <input type="file" name="cover_image" accept="image/*" class="form-control">
                        <div class="form-text">Upload a clear image for the product card (CCTV camera, alarm, smart lock, motion sensor, etc.).</div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Summary</label>
                        <textarea name="summary" rows="2" class="form-control">{{ old('summary') }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label mb-2">Categories</label>
                        <div class="row g-2">
                            @foreach ($categories as $category)
                                <div class="col-12 col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="form-check-input" id="create-cat-{{ $category->id }}">
                                        <label class="form-check-label" for="create-cat-{{ $category->id }}">{{ $category->name }}</label>
                                    </div>
                                </div>
                                @foreach ($category->children as $child)
                                    <div class="col-12 col-md-6 ps-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" name="categories[]" value="{{ $child->id }}" class="form-check-input" id="create-cat-{{ $child->id }}">
                                            <label class="form-check-label" for="create-cat-{{ $child->id }}">{{ $child->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12 d-flex flex-wrap gap-4">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="form-check-input" id="create-is-active">
                            <label class="form-check-label" for="create-is-active">Active</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="form-check-input" id="create-is-featured">
                            <label class="form-check-label" for="create-is-featured">Featured</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Create Product</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

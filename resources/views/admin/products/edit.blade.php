@extends('admin.layout')

@section('title', 'Edit Product')

@section('content')
    <div class="mb-3">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Back to Products</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4 p-md-5">
            <h2 class="h4 mb-4">Edit Product</h2>

            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Product Name *</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="form-control">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Brand *</label>
                        <input type="text" name="brand" value="{{ old('brand', $product->brand) }}" required class="form-control">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="form-control">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Price *</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" step="0.01" min="0" required class="form-control">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Compare At Price</label>
                        <input type="number" name="compare_at_price" value="{{ old('compare_at_price', $product->compare_at_price) }}" step="0.01" min="0" class="form-control">
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Availability Status</label>
                        <select name="availability_status" class="form-select">
                            <option value="in_stock" {{ old('availability_status', $product->availability_status) === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                            <option value="out_of_stock" {{ old('availability_status', $product->availability_status) === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                            <option value="pre_order" {{ old('availability_status', $product->availability_status) === 'pre_order' ? 'selected' : '' }}>Pre Order</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Main Product Image</label>
                        @if ($product->cover_image_url)
                            <div class="mb-2">
                                <img src="{{ $product->cover_image_url }}" alt="{{ $product->name }}" class="rounded border" style="width: 96px; height: 96px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" name="cover_image" accept="image/*" class="form-control">
                        <div class="form-text">Upload a new image to replace the current one.</div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Summary</label>
                        <textarea name="summary" rows="2" class="form-control">{{ old('summary', $product->summary) }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="col-12">
                        <label class="form-label mb-2">Categories</label>
                        <div class="row g-2">
                            @foreach ($categories as $category)
                                <div class="col-12 col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="categories[]" value="{{ $category->id }}" {{ $product->categories->contains($category->id) ? 'checked' : '' }} class="form-check-input" id="cat-{{ $category->id }}">
                                        <label class="form-check-label" for="cat-{{ $category->id }}">{{ $category->name }}</label>
                                    </div>
                                </div>
                                @foreach ($category->children as $child)
                                    <div class="col-12 col-md-6 ps-md-4">
                                        <div class="form-check">
                                            <input type="checkbox" name="categories[]" value="{{ $child->id }}" {{ $product->categories->contains($child->id) ? 'checked' : '' }} class="form-check-input" id="cat-{{ $child->id }}">
                                            <label class="form-check-label" for="cat-{{ $child->id }}">{{ $child->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12 d-flex flex-wrap gap-4">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="form-check-input" id="is-active">
                            <label class="form-check-label" for="is-active">Active</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="form-check-input" id="is-featured">
                            <label class="form-check-label" for="is-featured">Featured</label>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Product</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

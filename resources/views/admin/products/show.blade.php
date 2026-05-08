@extends('admin.layout')

@section('title', 'Product Details')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Back to Products</a>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary btn-sm">Edit Product</a>
            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    @if ($product->cover_image_url)
                        <img src="{{ $product->cover_image_url }}" alt="{{ $product->name }}" class="img-fluid rounded border" style="max-height: 280px; object-fit: cover;">
                    @else
                        <div class="border rounded bg-light d-flex align-items-center justify-content-center text-muted" style="height: 280px;">
                            No image available
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="mb-2">{{ $product->name }}</h3>
                    <p class="text-muted mb-3">{{ $product->description ?: 'No description provided.' }}</p>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="small text-muted">Brand</div>
                            <div class="fw-semibold">{{ $product->brand }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="small text-muted">SKU</div>
                            <div class="fw-semibold">{{ $product->sku ?: 'N/A' }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="small text-muted">Price</div>
                            <div class="fw-semibold">{{ $product->currency ?? 'USD' }} {{ number_format((float) $product->price, 2) }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="small text-muted">Compare At Price</div>
                            <div class="fw-semibold">
                                @if ($product->compare_at_price)
                                    {{ $product->currency ?? 'USD' }} {{ number_format((float) $product->compare_at_price, 2) }}
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="small text-muted">Availability</div>
                            <div class="fw-semibold">{{ ucwords(str_replace('_', ' ', $product->availability_status ?? 'in_stock')) }}</div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="small text-muted">Status</div>
                            <div class="d-flex gap-2">
                                <span class="badge {{ $product->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if ($product->is_featured)
                                    <span class="badge text-bg-warning">Featured</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white"><strong>Description</strong></div>
                <div class="card-body">
                    <p class="mb-0">{{ $product->description ?: 'No description provided.' }}</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><strong>Categories</strong></div>
                <div class="card-body">
                    @if ($product->categories->isNotEmpty())
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($product->categories as $category)
                                <span class="badge text-bg-light border text-dark">{{ $category->name }}</span>
                            @endforeach
                        </div>
                    @else
                        <span class="text-muted">No categories assigned.</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

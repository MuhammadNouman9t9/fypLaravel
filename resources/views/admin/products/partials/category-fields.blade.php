@php
    $selectedIds = collect($selectedIds ?? old('categories', []))
        ->map(fn ($v) => (int) $v)
        ->filter(fn ($v) => $v > 0)
        ->unique()
        ->values()
        ->all();
@endphp

<div class="col-12">
    <label class="form-label mb-0">Categories</label>
    <p class="form-text mb-3">
        Select any combination of parent categories and subcategories. The <strong>first</strong> selected value in the form (top to bottom, left to right) is saved as the primary category.
    </p>
    @error('categories')
        <div class="alert alert-danger py-2 small mb-3">{{ $message }}</div>
    @enderror

    <div class="row g-3">
        @foreach ($categories as $category)
            <div class="col-12 col-xl-6">
                <div class="border rounded-3 p-3 h-100 bg-body-secondary bg-opacity-25">
                    <div class="form-check mb-2">
                        <input
                            type="checkbox"
                            name="categories[]"
                            value="{{ $category->id }}"
                            class="form-check-input"
                            id="cat-root-{{ $category->id }}"
                            {{ in_array($category->id, $selectedIds, true) ? 'checked' : '' }}
                        >
                        <label class="form-check-label fw-semibold" for="cat-root-{{ $category->id }}">{{ $category->name }}</label>
                    </div>
                    @if ($category->children->isNotEmpty())
                        <div class="border-top pt-2 mt-2">
                            <div class="small text-body-secondary mb-2">Subcategories</div>
                            <div class="row row-cols-1 g-1">
                                @foreach ($category->children as $child)
                                    <div class="col">
                                        <div class="form-check">
                                            <input
                                                type="checkbox"
                                                name="categories[]"
                                                value="{{ $child->id }}"
                                                class="form-check-input"
                                                id="cat-{{ $child->id }}"
                                                {{ in_array($child->id, $selectedIds, true) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label small" for="cat-{{ $child->id }}">{{ $child->name }}</label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

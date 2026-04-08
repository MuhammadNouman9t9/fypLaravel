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
        Select main categories only.
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
                </div>
            </div>
        @endforeach
    </div>
</div>

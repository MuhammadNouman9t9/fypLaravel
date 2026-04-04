<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        // Eager load all necessary relationships to avoid N+1 queries
        $query = Product::with(['categories', 'media', 'inventory']);

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm): void {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('brand', 'like', "%{$searchTerm}%")
                    ->orWhere('sku', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $products = $query->latest()->paginate(15);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $categories = Category::active()->roots()->with('children')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:products,sku'],
            'summary' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'availability_status' => ['nullable', 'string', 'in:in_stock,out_of_stock,pre_order'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['created_by'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');
        $validated['currency'] = $validated['currency'] ?? 'USD';

        $product = Product::create($validated);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('products', 'public');

            ProductMedia::create([
                'product_id' => $product->id,
                'type' => 'image',
                'file_path' => asset('storage/'.$path),
                'alt_text' => $product->name,
                'position' => 0,
                'is_primary' => true,
            ]);
        }

        if ($request->filled('categories')) {
            $product->categories()->attach($request->categories, [
                'is_primary' => $request->categories[0] ?? false,
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('status', __('Product created successfully.'));
    }

    public function show(Product $product): View
    {
        $product->load('categories');

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $categories = Category::active()->roots()->with('children')->get();
        $product->load('categories');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:255', 'unique:products,sku,'.$product->id],
            'summary' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'availability_status' => ['nullable', 'string', 'in:in_stock,out_of_stock,pre_order'],
            'is_active' => ['boolean'],
            'is_featured' => ['boolean'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['exists:categories,id'],
            'cover_image' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['updated_by'] = auth()->id();
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        $product->update($validated);

        if ($request->hasFile('cover_image')) {
            $path = $request->file('cover_image')->store('products', 'public');

            // Mark existing primary as non-primary
            $product->media()->update(['is_primary' => false]);

            ProductMedia::create([
                'product_id' => $product->id,
                'type' => 'image',
                'file_path' => asset('storage/'.$path),
                'alt_text' => $product->name,
                'position' => 0,
                'is_primary' => true,
            ]);
        }

        if ($request->filled('categories')) {
            $product->categories()->sync($request->categories);
        } else {
            $product->categories()->detach();
        }

        return redirect()->route('admin.products.index')
            ->with('status', __('Product updated successfully.'));
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('status', __('Product deleted successfully.'));
    }
}

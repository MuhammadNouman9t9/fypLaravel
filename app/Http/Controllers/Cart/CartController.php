<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;

class CartController extends Controller
{
    public function index(): View
    {
        $sessionItems = collect(session('cart.items', []));

        if ($sessionItems->isEmpty()) {
            return view('cart.index', [
                'items' => collect(),
                'summary' => [
                    'subtotal' => 0,
                    'tax' => 0,
                    'total' => 0,
                ],
            ]);
        }

        $products = Product::query()
            ->with(['media', 'inventory'])
            ->whereIn('id', $sessionItems->pluck('product_id'))
            ->get()
            ->keyBy('id');

        $items = $sessionItems
            ->map(function (array $item) use ($products): ?array {
                $product = $products->get($item['product_id']);

                if (! $product) {
                    return null;
                }

                $quantity = (int) $item['quantity'];
                $lineTotal = $product->price * $quantity;

                $inventory = $product->inventory;
                $availableUnits = $inventory ? max(0, (int) $inventory->quantity_on_hand - (int) $inventory->quantity_reserved) : null;

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'line_total' => $lineTotal,
                    'in_stock' => $availableUnits === null ? true : $availableUnits >= $quantity,
                    'available_units' => $availableUnits,
                ];
            })
            ->filter()
            ->values();

        $subtotal = $items->sum(fn (array $item) => $item['line_total']);
        $estimatedTax = round($subtotal * 0.08, 2);

        return view('cart.index', [
            'items' => $items,
            'summary' => [
                'subtotal' => $subtotal,
                'tax' => $estimatedTax,
                'total' => $subtotal + $estimatedTax,
            ],
        ]);
    }

    public function store(AddToCartRequest $request): RedirectResponse
    {
        $product = Product::query()
            ->active()
            ->with(['inventory'])
            ->findOrFail($request->validated('product_id'));

        $inventory = $product->inventory;
        $availableUnits = $inventory ? max(0, (int) $inventory->quantity_on_hand - (int) $inventory->quantity_reserved) : null;

        if ($availableUnits !== null && $availableUnits <= 0) {
            return back()
                ->withErrors([
                    'quantity' => __(':product is currently unavailable.', ['product' => $product->name]),
                ])
                ->withInput();
        }

        $items = $this->cartItems();
        $currentQuantity = $items->firstWhere('product_id', $product->getKey())['quantity'] ?? 0;
        $requestedQuantity = $request->validated('quantity');
        $newQuantity = min(10, $currentQuantity + $requestedQuantity);

        if ($availableUnits !== null) {
            $newQuantity = min($newQuantity, $availableUnits);

            if ($newQuantity <= $currentQuantity) {
                return back()
                    ->withErrors([
                        'quantity' => __('Only :count units are available right now.', ['count' => $availableUnits]),
                    ])
                    ->withInput();
            }
        }

        $items = $items
            ->reject(fn (array $item) => $item['product_id'] === $product->getKey())
            ->push([
                'product_id' => $product->getKey(),
                'quantity' => $newQuantity,
            ]);

        session()->put('cart.items', $items->toArray());

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __(':product added to your cart.', ['product' => $product->name]),
                'cart_count' => $items->sum('quantity'),
                'redirect' => route('cart.index'),
            ]);
        }

        return redirect()
            ->route('cart.index')
            ->with('status', __(':product added to your cart.', ['product' => $product->name]));
    }

    public function update(UpdateCartItemRequest $request, Product $product)
    {
        $items = $this->cartItems();

        $inventory = $product->inventory()->first();
        $availableUnits = $inventory ? max(0, (int) $inventory->quantity_on_hand - (int) $inventory->quantity_reserved) : null;

        $requestedQuantity = min(10, $request->validated('quantity'));

        if ($availableUnits !== null) {
            $requestedQuantity = min($requestedQuantity, $availableUnits);
        }

        $statusMessage = __('Cart updated.');

        if ($availableUnits !== null && $request->validated('quantity') > $availableUnits) {
            $statusMessage = __('Updated to the :count available units.', ['count' => $availableUnits]);
        }

        if ($requestedQuantity <= 0) {
            $items = $items->reject(fn (array $item) => $item['product_id'] === $product->getKey());
            $statusMessage = __(':product removed from your cart.', ['product' => $product->name]);
        } else {
            $items = $items
                ->reject(fn (array $item) => $item['product_id'] === $product->getKey())
                ->push([
                    'product_id' => $product->getKey(),
                    'quantity' => $requestedQuantity,
                ]);
        }

        session()->put('cart.items', $items->toArray());

        // Calculate summary
        $products = Product::query()
            ->whereIn('id', $items->pluck('product_id'))
            ->get()
            ->keyBy('id');

        $summaryItems = $items->map(function (array $item) use ($products): ?array {
            $product = $products->get($item['product_id']);
            if (! $product) {
                return null;
            }

            return [
                'line_total' => $product->price * $item['quantity'],
            ];
        })->filter();

        $subtotal = $summaryItems->sum('line_total');
        $estimatedTax = round($subtotal * 0.08, 2);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $statusMessage,
                'cart_count' => $items->sum('quantity'),
                'summary' => [
                    'subtotal' => $subtotal,
                    'tax' => $estimatedTax,
                    'total' => $subtotal + $estimatedTax,
                ],
            ]);
        }

        return redirect()
            ->route('cart.index')
            ->with('status', $statusMessage);
    }

    public function destroy(Product $product): RedirectResponse
    {
        $items = $this->cartItems()
            ->reject(fn (array $item) => $item['product_id'] === $product->getKey())
            ->values();

        session()->put('cart.items', $items->toArray());

        return redirect()
            ->route('cart.index')
            ->with('status', __(':product removed from your cart.', ['product' => $product->name]));
    }

    public function checkout(): RedirectResponse
    {
        $sessionItems = $this->cartItems();

        if ($sessionItems->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('status', __('Your cart is empty.'));
        }

        $products = Product::query()
            ->whereIn('id', $sessionItems->pluck('product_id'))
            ->get()
            ->keyBy('id');

        $subtotal = 0;
        $orderItems = [];

        foreach ($sessionItems as $item) {
            $product = $products->get($item['product_id']);
            if (! $product) {
                continue;
            }

            $quantity = (int) $item['quantity'];
            $unitPrice = $product->price;
            $lineTotal = $unitPrice * $quantity;
            $subtotal += $lineTotal;

            $orderItems[] = [
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total' => $lineTotal,
            ];
        }

        if (empty($orderItems)) {
            return redirect()
                ->route('cart.index')
                ->with('status', __('Unable to process checkout. Please try again.'));
        }

        $taxTotal = round($subtotal * 0.08, 2);
        $grandTotal = $subtotal + $taxTotal;

        $order = auth()->user()->orders()->create([
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'currency' => 'USD',
            'subtotal' => $subtotal,
            'tax_total' => $taxTotal,
            'grand_total' => $grandTotal,
            'placed_at' => now(),
        ]);

        foreach ($orderItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product']->id,
                'name' => $item['product']->name,
                'sku' => $item['product']->sku ?? null,
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['total'],
            ]);
        }

        session()->forget('cart.items');

        return redirect()
            ->route('payment.checkout', $order)
            ->with('status', __('Order created. Please complete your payment.'));
    }

    private function cartItems(): Collection
    {
        return collect(session('cart.items', []))
            ->map(fn ($item) => [
                'product_id' => (int) ($item['product_id'] ?? 0),
                'quantity' => (int) ($item['quantity'] ?? 0),
            ])
            ->filter(fn (array $item) => $item['product_id'] > 0 && $item['quantity'] > 0)
            ->values();
    }
}

<x-landing-layout title="Payment Successful">
    <section class="border-b border-[#e5e7eb] bg-white">
        <div class="mx-auto max-w-6xl px-4 py-14 lg:py-16">
            <div class="text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-[#dcfce7]">
                    <svg class="h-10 w-10 text-[#16a34a]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h1 class="mt-6 text-3xl font-semibold text-[#0f172a] sm:text-4xl">
                    {{ __('Payment Successful!') }}
                </h1>
                <p class="mt-4 text-sm leading-relaxed text-[#475569]">
                    {{ __('Thank you for your order. Your payment has been processed successfully.') }}
                </p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-14 lg:py-16">
        <div class="grid gap-8 lg:grid-cols-2">
            <div class="rounded-3xl border border-[#e5e7eb] bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-[#0f172a] mb-4">{{ __('Order Information') }}</h2>
                <dl class="space-y-3 text-sm text-[#475569]">
                    <div class="flex justify-between">
                        <dt>{{ __('Order Number:') }}</dt>
                        <dd class="font-semibold text-[#0f172a]">{{ $order->order_number }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt>{{ __('Order Date:') }}</dt>
                        <dd class="font-semibold text-[#0f172a]">{{ $order->created_at->format('M d, Y') }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt>{{ __('Payment Status:') }}</dt>
                        <dd class="font-semibold text-[#16a34a]">{{ ucfirst($order->payment_status) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt>{{ __('Total Paid:') }}</dt>
                        <dd class="font-semibold text-[#0f172a]">${{ number_format($order->grand_total, 2) }}</dd>
                    </div>
                </dl>
            </div>

            <div class="rounded-3xl border border-[#e5e7eb] bg-[#f9fafb] p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-[#0f172a] mb-4">{{ __('What's Next?') }}</h2>
                <ul class="space-y-4 text-sm text-[#475569]">
                    <li class="flex items-start gap-3">
                        <svg class="h-5 w-5 text-[#2563eb] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ __('You will receive an email confirmation shortly with your order details.') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="h-5 w-5 text-[#2563eb] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ __('Our team will prepare your order for shipment.') }}</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <svg class="h-5 w-5 text-[#2563eb] mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ __('You will receive tracking information once your order ships.') }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:justify-center">
            <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center rounded-full border border-[#111827] px-6 py-3 text-sm font-semibold text-[#111827] hover:bg-[#111827] hover:text-white">
                {{ __('Continue Shopping') }}
            </a>
            @auth
                <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center justify-center rounded-full border border-[#6366f1] bg-[#6366f1] px-6 py-3 text-sm font-semibold text-white hover:bg-[#4f46e5]">
                    {{ __('View Order Details') }}
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-full border border-[#d1d5db] bg-white px-6 py-3 text-sm font-semibold text-[#475569] hover:bg-[#f9fafb]">
                    {{ __('View Dashboard') }}
                </a>
            @endauth
        </div>
    </section>
</x-landing-layout>


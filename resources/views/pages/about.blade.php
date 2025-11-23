@extends('layouts.storefront')

@section('content')
    <section class="border-b border-[#e5e7eb] bg-white">
        <div class="mx-auto max-w-6xl px-4 py-16 lg:py-20">
            <div class="max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-wide text-[#2563eb]">
                    {{ __('About SafeNest') }}
                </p>
                <h1 class="mt-4 text-3xl font-semibold text-[#0f172a] sm:text-4xl">
                    {{ __('We help families feel safe through intelligent technology') }}
                </h1>
                <p class="mt-4 text-base leading-relaxed text-[#475569]">
                    {{ __('SafeNest blends thoughtful hardware, ethical AI, and human expertise to create safer communities. From preventative monitoring to trusted installation experts, our mission is to make enlightened home security attainable for everyone.') }}
                </p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-14 lg:py-18">
        <div class="grid gap-10 lg:grid-cols-[1.1fr,0.9fr]">
            <div class="space-y-6">
                <h2 class="text-2xl font-semibold text-[#0f172a]">
                    {{ __('Our values keep us accountable') }}
                </h2>
                <div class="space-y-5">
                    @foreach ([
                        [
                            'title' => __('Trustworthy intelligence'),
                            'body' => __('We design privacy-first AI experiences that keep households in control. All insights are transparent, auditable, and built around consent.'),
                        ],
                        [
                            'title' => __('Human-first design'),
                            'body' => __('SafeNest products are crafted with care, so setup feels intuitive and long-term ownership is simple for everyone in the family.'),
                        ],
                        [
                            'title' => __('Sustainable protection'),
                            'body' => __('We partner with responsible manufacturers and offset the lifetime footprint of our flagship devices through circular refurbishment programs.'),
                        ],
                    ] as $value)
                        <div class="rounded-2xl border border-[#e5e7eb] bg-white p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-[#111827]">{{ $value['title'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-[#475569]">{{ $value['body'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="space-y-6 rounded-3xl border border-[#1f2937]/10 bg-[#0f172a] p-8 text-white shadow-xl">
                <h3 class="text-lg font-semibold">{{ __('Founded with peace of mind in mind') }}</h3>
                <p class="mt-3 text-sm leading-relaxed text-white/70">
                    {{ __('SafeNest launched in 2020 when our founders noticed legacy security systems were difficult to install, rarely updated, and frequently ignored alerts. We rebuilt the experience with empathy and continuous learning at the core.') }}
                </p>
                <dl class="mt-6 space-y-4 text-sm">
                    <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                        <dt class="font-medium text-white/80">{{ __('Households protected') }}</dt>
                        <dd class="text-base font-semibold">{{ __('48,000+') }}</dd>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                        <dt class="font-medium text-white/80">{{ __('Average response speed improvement') }}</dt>
                        <dd class="text-base font-semibold">{{ __('62% faster') }}</dd>
                    </div>
                    <div class="flex items-center justify-between rounded-2xl bg-white/10 px-4 py-3">
                        <dt class="font-medium text-white/80">{{ __('Certified security partners') }}</dt>
                        <dd class="text-base font-semibold">{{ __('230 experts worldwide') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>

    <section class="border-t border-[#e5e7eb] bg-[#f9fafb]">
        <div class="mx-auto max-w-6xl px-4 py-16">
            <div class="grid gap-12 lg:grid-cols-[0.85fr,1.15fr]">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-[#2563eb]">{{ __('Leadership') }}</p>
                    <h2 class="mt-3 text-2xl font-semibold text-[#0f172a]">
                        {{ __('A cross-disciplinary team dedicated to safer homes') }}
                    </h2>
                    <p class="mt-4 text-sm leading-relaxed text-[#475569]">
                        {{ __('Our globally distributed team blends backgrounds in emergency response, AI research, privacy law, and hardware engineering. We build solutions that respect both the safety and autonomy of modern households.') }}
                    </p>
                </div>
                <div class="grid gap-6 sm:grid-cols-2">
                    @foreach ([
                        ['name' => 'Amelia Turner', 'role' => __('Chief Experience Officer'), 'bio' => __('Former UX Director at Nest, Amelia champions inclusive home technology design.')],
                        ['name' => 'Dr. Ravi Kulkarni', 'role' => __('Head of Applied AI'), 'bio' => __('AI safety researcher who ensures every model meets SafeNest transparency standards.')],
                        ['name' => 'Leila Moreno', 'role' => __('VP of Field Operations'), 'bio' => __('Oversees our certified installer network and best practices trainings worldwide.')],
                        ['name' => 'Jonah Clarke', 'role' => __('Head of Privacy & Trust'), 'bio' => __('Cybersecurity advocate driving continuous audits and policy alignment with global frameworks.')],
                    ] as $leader)
                        <div class="rounded-2xl border border-white bg-white p-5 shadow-sm">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#2563eb]/10 text-sm font-semibold text-[#2563eb]">
                                {{ \Illuminate\Support\Str::of($leader['name'])->explode(' ')->map(fn ($segment) => \Illuminate\Support\Str::of($segment)->substr(0, 1))->join('') }}
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-[#111827]">{{ $leader['name'] }}</h3>
                            <p class="text-sm font-medium text-[#2563eb]">{{ $leader['role'] }}</p>
                            <p class="mt-2 text-sm leading-relaxed text-[#475569]">{{ $leader['bio'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto max-w-6xl px-4 py-16">
            <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-xl space-y-4">
                    <h2 class="text-2xl font-semibold text-[#0f172a]">
                        {{ __('Partner with SafeNest to elevate neighborhood safety programs') }}
                    </h2>
                    <p class="text-sm leading-relaxed text-[#475569]">
                        {{ __('We collaborate with municipalities, gated communities, and property developers to create connected security ecosystems tailored to local needs. Our specialists provide blueprints, training, and ongoing operational reviews.') }}
                    </p>
                </div>
                <a href="{{ route('support.index') }}" class="inline-flex items-center justify-center rounded-full bg-[#111827] px-6 py-3 text-sm font-semibold text-white hover:bg-[#0b1120]">
                    {{ __('Book a strategic session') }}
                </a>
            </div>
        </div>
    </section>
@endsection


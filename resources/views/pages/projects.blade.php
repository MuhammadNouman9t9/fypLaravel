@extends('layouts.storefront')

@section('content')
    <section class="border-b border-[#e5e7eb] bg-white">
        <div class="mx-auto max-w-6xl px-4 py-16 lg:py-20">
            <div class="max-w-3xl space-y-4">
                <p class="text-sm font-semibold uppercase tracking-wide text-[#2563eb]">
                    {{ __('SafeNest Projects') }}
                </p>
                <h1 class="text-3xl font-semibold text-[#0f172a] sm:text-4xl">
                    {{ __('Real deployments that keep communities more secure') }}
                </h1>
                <p class="text-base leading-relaxed text-[#475569]">
                    {{ __('Explore featured SafeNest implementations ranging from modern city lofts to expansive estates. Each project pairs proactive AI monitoring with expert installation to deliver confidence from day one.') }}
                </p>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-6xl px-4 py-14 lg:py-18">
        <div class="grid gap-10 lg:grid-cols-[1.2fr,0.8fr]">
            <div class="space-y-6">
                @foreach ([
                    [
                        'title' => __('Skyline Residences, Seattle'),
                        'badge' => __('High-rise living'),
                        'summary' => __('We deployed a network of SafeNest entry sensors and connected lighting scenes to reduce false alarms by 73% while improving concierge response times.'),
                        'metrics' => [
                            __('97% fewer late-night nuisance alerts'),
                            __('Full install completed across 42 floors in 6 days'),
                        ],
                    ],
                    [
                        'title' => __('Palm Grove Estates, Austin'),
                        'badge' => __('Gated community'),
                        'summary' => __('A hybrid of AI-enabled perimeter cameras and smart locks allows residents to manage deliveries without compromising privacy.'),
                        'metrics' => [
                            __('Community satisfaction scores up 24 points'),
                            __('Shared visitor logs accessible within 2 seconds'),
                        ],
                    ],
                    [
                        'title' => __('Heritage Brownstones, Boston'),
                        'badge' => __('Historic homes'),
                        'summary' => __('Discrete sensors+audio analytics preserved architectural details while elevating security oversight for multi-generational families.'),
                        'metrics' => [
                            __('Zero structural modifications required'),
                            __('24/7 monitoring with 11-second average response'),
                        ],
                    ],
                ] as $project)
                    <article class="rounded-3xl border border-[#e5e7eb] bg-white p-6 shadow-sm">
                        <span class="inline-flex items-center rounded-full bg-[#dbeafe] px-3 py-1 text-xs font-semibold uppercase tracking-wide text-[#1d4ed8]">
                            {{ $project['badge'] }}
                        </span>
                        <h2 class="mt-4 text-2xl font-semibold text-[#0f172a]">{{ $project['title'] }}</h2>
                        <p class="mt-3 text-sm leading-relaxed text-[#475569]">{{ $project['summary'] }}</p>
                        <ul class="mt-5 space-y-2 text-sm text-[#2563eb]">
                            @foreach ($project['metrics'] as $metric)
                                <li class="flex items-start gap-2">
                                    <span class="mt-1 h-1.5 w-1.5 rounded-full bg-[#2563eb]"></span>
                                    <span>{{ $metric }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </article>
                @endforeach
            </div>
            <aside class="space-y-6 rounded-3xl border border-[#1f2937]/10 bg-[#0f172a] p-8 text-white shadow-xl">
                <h3 class="text-lg font-semibold">{{ __('From blueprint to live monitoring') }}</h3>
                <p class="mt-3 text-sm leading-relaxed text-white/70">
                    {{ __('SafeNest specialists partner with architects, facility managers, and homeowner associations to map risks and deliver phased deployment roadmaps.') }}
                </p>
                <dl class="space-y-4 text-sm">
                    <div class="rounded-2xl bg-white/10 px-4 py-3">
                        <dt class="font-medium text-white/80">{{ __('Discovery workshops') }}</dt>
                        <dd class="mt-1 text-base font-semibold">{{ __('2 weeks average') }}</dd>
                    </div>
                    <div class="rounded-2xl bg-white/10 px-4 py-3">
                        <dt class="font-medium text-white/80">{{ __('Implementation specialists') }}</dt>
                        <dd class="mt-1 text-base font-semibold">{{ __('Certified + insured') }}</dd>
                    </div>
                    <div class="rounded-2xl bg-white/10 px-4 py-3">
                        <dt class="font-medium text-white/80">{{ __('Maintenance cadence') }}</dt>
                        <dd class="mt-1 text-base font-semibold">{{ __('Quarterly reviews') }}</dd>
                    </div>
                </dl>
                <a href="{{ route('support.index') }}" class="inline-flex items-center justify-center rounded-full bg-white px-6 py-2.5 text-sm font-semibold text-[#0f172a] hover:bg-white/90">
                    {{ __('Book a project consultation') }}
                </a>
            </aside>
        </div>
    </section>

    <section class="border-t border-[#e5e7eb] bg-[#f9fafb]">
        <div class="mx-auto max-w-6xl px-4 py-16">
            <div class="grid gap-10 lg:grid-cols-[0.95fr,1.05fr]">
                <div class="space-y-4">
                    <p class="text-sm font-semibold uppercase tracking-wide text-[#2563eb]">{{ __('Services') }}</p>
                    <h2 class="text-2xl font-semibold text-[#0f172a]">
                        {{ __('Complete security lifecycle support') }}
                    </h2>
                    <p class="text-sm leading-relaxed text-[#475569]">
                        {{ __('From threat modeling to proactive maintenance, SafeNest stays engaged long after installation day to keep your properties resilient.') }}
                    </p>
                </div>
                <div class="grid gap-6 sm:grid-cols-2">
                    @foreach ([
                        ['title' => __('AI risk assessments'), 'body' => __('Data-rich evaluations that prioritize mitigations aligned to your exposure level.')],
                        ['title' => __('Resident onboarding'), 'body' => __('Guided training helps households adopt best practices and use automation responsibly.')],
                        ['title' => __('Concierge monitoring'), 'body' => __('Partnered operations teams provide real-time escalation on your terms.')],
                        ['title' => __('Lifecycle upgrades'), 'body' => __('We schedule firmware, software, and hardware improvements before vulnerabilities emerge.')],
                    ] as $service)
                        <div class="rounded-2xl border border-white bg-white p-5 shadow-sm">
                            <h3 class="text-lg font-semibold text-[#111827]">{{ $service['title'] }}</h3>
                            <p class="mt-2 text-sm leading-relaxed text-[#475569]">{{ $service['body'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection



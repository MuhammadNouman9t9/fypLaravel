<x-landing-layout title="Home">
    <div class="bg-gradient-purple overflow-hidden">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-6 text-white position-relative" style="z-index: 1;">
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-pill border border-white border-opacity-25 bg-white bg-opacity-10 mb-4">
                        <svg style="width: 1rem; height: 1rem;" class="text-warning" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="small fw-medium">Trusted by 50,000+ Homeowners</span>
                    </div>

                    <h1 class="display-4 fw-bold lh-sm mb-4">
                        Smart Security for Your <span class="bg-white bg-opacity-25 px-2 rounded">Safe</span> <span class="bg-white bg-opacity-25 px-2 rounded">Home</span>
                    </h1>

                    <p class="lead text-purple-soft mb-4">
                        AI-powered smart home security solutions that protect what matters most. Experience intelligent protection with cutting-edge technology.
                    </p>

                    <div class="d-flex align-items-center gap-4">
                        <div class="d-flex gap-1">
                            @for ($i = 0; $i < 5; $i++)
                                <svg class="text-warning" style="width: 1.25rem; height: 1.25rem;" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            @endfor
                        </div>
                        <span class="text-white fw-medium">4.9/5 from 2,500+ reviews</span>
                    </div>
                </div>

                <div class="col-lg-6 d-flex justify-content-center justify-content-lg-end">
                    <div class="w-100" style="max-width: 28rem;">
                        <div class="bg-white bg-opacity-10 border border-white border-opacity-25 rounded-4 p-5 shadow-lg">
                            <x-shield-logo class="w-100 h-auto text-white opacity-90" style="max-height: 12rem;" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-landing-layout>

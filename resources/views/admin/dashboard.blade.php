@extends('admin.layout')

@section('title', 'Control panel')

@section('content')
    <div class="mb-4">
        <h1 class="h3 fw-semibold text-dark">Control panel</h1>
        <p class="small text-secondary mb-0">Choose an area to open. Detailed actions can be wired up later.</p>
    </div>

    <div class="row g-3">
        <div class="col-12 col-md-6 col-xl-4">
            <a href="{{ route('admin.users.index') }}" class="card h-100 text-decoration-none text-dark shadow-sm border hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10 text-primary p-2">
                            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </span>
                        <h2 class="h6 fw-semibold mb-0">Manage users</h2>
                    </div>
                    <p class="small text-secondary mb-0">View and manage registered accounts.</p>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <a href="{{ route('admin.products.index') }}" class="card h-100 text-decoration-none text-dark shadow-sm border hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-success bg-opacity-10 text-success p-2">
                            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                        </span>
                        <h2 class="h6 fw-semibold mb-0">Manage products</h2>
                    </div>
                    <p class="small text-secondary mb-0">Catalog and inventory overview.</p>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <a href="{{ route('admin.orders.index') }}" class="card h-100 text-decoration-none text-dark shadow-sm border hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-warning bg-opacity-10 text-warning-emphasis p-2">
                            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        </span>
                        <h2 class="h6 fw-semibold mb-0">View orders</h2>
                    </div>
                    <p class="small text-secondary mb-0">Order history and fulfillment.</p>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <a href="{{ route('admin.analytics.index') }}" class="card h-100 text-decoration-none text-dark shadow-sm border hover-shadow">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-primary bg-opacity-10 text-primary p-2">
                            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3v18m4-14v14m4-10v10M7 7v14M3 11v10" /></svg>
                        </span>
                        <h2 class="h6 fw-semibold mb-0">View analytics</h2>
                    </div>
                    <p class="small text-secondary mb-0">Reports and store metrics.</p>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <form method="POST" action="{{ route('admin.logout') }}" class="card h-100 border-danger border-opacity-25 shadow-sm">
                @csrf
                <button type="submit" class="card-body btn btn-link text-start text-decoration-none text-dark w-100 h-100">
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="d-inline-flex align-items-center justify-content-center rounded-3 bg-danger bg-opacity-10 text-danger p-2">
                            <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        </span>
                        <h2 class="h6 fw-semibold mb-0 text-danger">Logout</h2>
                    </div>
                    <p class="small text-secondary mb-0">End the admin session securely.</p>
                </button>
            </form>
        </div>
    </div>
@endsection

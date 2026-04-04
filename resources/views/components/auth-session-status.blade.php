@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'small text-success fw-medium']) }}>
        {{ $status }}
    </div>
@endif

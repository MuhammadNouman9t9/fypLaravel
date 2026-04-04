@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white'])

@php
    $alignClass = match ($align) {
        'left' => 'dropdown-menu-start',
        'top' => '',
        default => 'dropdown-menu-end',
    };
    $widthClass = match ($width) {
        '48' => '',
        default => '',
    };
@endphp

<div class="dropdown" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div
        x-show="open"
        x-transition
        class="dropdown-menu shadow {{ $alignClass }} {{ $widthClass }} {{ $contentClasses }}"
        style="display: none;"
        @click="open = false"
    >
        {{ $content }}
    </div>
</div>

@php
    $items = $field->getItems();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }"
        style="display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));gap:10px;max-height:420px;overflow-y:auto;padding:6px;border:1px solid rgba(0,0,0,0.08);border-radius:0.75rem"
    >
        @forelse ($items as $item)
            <button
                type="button"
                @click="state = state === {{ $item['id'] }} ? null : {{ $item['id'] }}"
                :style="state === {{ $item['id'] }}
                    ? 'border:3px solid #7b3ff2;border-radius:0.5rem;overflow:hidden;position:relative;aspect-ratio:1/1;padding:0;cursor:pointer'
                    : 'border:3px solid transparent;border-radius:0.5rem;overflow:hidden;position:relative;aspect-ratio:1/1;padding:0;cursor:pointer'"
                title="{{ $item['name'] }}"
            >
                <img
                    src="{{ $item['url'] }}"
                    alt="{{ $item['name'] }}"
                    style="width:100%;height:100%;object-fit:cover;display:block"
                    loading="lazy"
                >
                <span
                    x-show="state === {{ $item['id'] }}"
                    style="position:absolute;top:4px;right:4px;background:#7b3ff2;color:white;border-radius:9999px;width:22px;height:22px;display:flex;align-items:center;justify-content:center;font-size:14px"
                >✓</span>
            </button>
        @empty
            <p style="grid-column:1/-1;text-align:center;color:#9ca3af;padding:2rem 0">
                Todavía no hay imágenes en la biblioteca.
            </p>
        @endforelse
    </div>
</x-dynamic-component>

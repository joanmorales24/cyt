@php
    $items = $field->getItems();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div
        x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }"
        style="display:grid;grid-template-columns:repeat(auto-fill,minmax(110px,1fr));grid-auto-rows:110px;gap:10px;max-height:420px;overflow-y:auto;padding:8px;border:1px solid rgba(0,0,0,0.08);border-radius:0.75rem"
    >
        @forelse ($items as $item)
            <button
                type="button"
                @click="state = state === {{ $item['id'] }} ? null : {{ $item['id'] }}"
                style="display:block;width:100%;height:100%;padding:0;margin:0;position:relative;border-radius:0.5rem;overflow:hidden;cursor:pointer;background:none"
                :style="state === {{ $item['id'] }} ? 'display:block;width:100%;height:100%;padding:0;margin:0;position:relative;border-radius:0.5rem;overflow:hidden;cursor:pointer;box-shadow:0 0 0 3px #7b3ff2;background:none' : 'display:block;width:100%;height:100%;padding:0;margin:0;position:relative;border-radius:0.5rem;overflow:hidden;cursor:pointer;box-shadow:0 0 0 1px rgba(0,0,0,0.1);background:none'"
                title="{{ $item['name'] }}"
            >
                <img
                    src="{{ $item['url'] }}"
                    alt="{{ $item['name'] }}"
                    style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;display:block"
                    loading="lazy"
                >
                <span
                    x-show="state === {{ $item['id'] }}"
                    style="position:absolute;top:4px;right:4px;background:#7b3ff2;color:white;border-radius:9999px;width:22px;height:22px;display:flex;align-items:center;justify-content:center;font-size:13px;line-height:1"
                >✓</span>
            </button>
        @empty
            <p style="grid-column:1/-1;text-align:center;color:#9ca3af;padding:2rem 0">
                Todavía no hay imágenes en la biblioteca.
            </p>
        @endforelse
    </div>
</x-dynamic-component>

@php
    $editorId   = 'gb-' . $getId();
    $statePath  = $getStatePath();
    $uploadUrl  = $field->getUploadUrl();
    $initialVal = $getState() ?? '[]';
    if (is_array($initialVal)) $initialVal = json_encode($initialVal);
@endphp

@once
@push('styles')
<style>
/* ── Variables ──────────────────────────────────────── */
:root {
    --gb-bg:       #fff;
    --gb-text:     #1a1a1a;
    --gb-muted:    #9ca3af;
    --gb-border:   #e5e7eb;
    --gb-focus:    #f9fafb;
    --gb-accent:   #9333ea;
    --gb-radius:   10px;
    --gb-shadow:   0 2px 12px rgba(0,0,0,.08);
}
.dark {
    --gb-bg:     #1e293b;
    --gb-text:   #f1f5f9;
    --gb-muted:  #64748b;
    --gb-border: #334155;
    --gb-focus:  #0f172a;
}

/* ── Editor shell ───────────────────────────────────── */
.gb-editor {
    font-family: system-ui, sans-serif;
    color: var(--gb-text);
    background: var(--gb-bg);
    border: 1px solid var(--gb-border);
    border-radius: var(--gb-radius);
    overflow: hidden;
}

/* ── Top bar ────────────────────────────────────────── */
.gb-topbar {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    border-bottom: 1px solid var(--gb-border);
    background: var(--gb-focus);
    flex-wrap: wrap;
}
.gb-add-btn {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 6px;
    border: 1px solid var(--gb-border);
    background: var(--gb-bg);
    color: var(--gb-text);
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    white-space: nowrap;
}
.gb-add-btn:hover { background: var(--gb-border); }
.gb-block-count {
    margin-left: auto;
    font-size: 11px;
    color: var(--gb-muted);
}

/* ── Document area ──────────────────────────────────── */
.gb-doc {
    min-height: 320px;
    padding: 12px 16px;
    cursor: text;
}

/* ── Block row ──────────────────────────────────────── */
.gb-row {
    position: relative;
    margin-bottom: 1px;
}
.gb-row:hover .gb-insert-row,
.gb-row.is-selected .gb-insert-row { opacity: 1; }

/* insert line */
.gb-insert-row {
    display: flex;
    align-items: center;
    gap: 4px;
    opacity: 0;
    transition: opacity .15s;
    margin-bottom: 2px;
}
.gb-insert-line { flex: 1; height: 1px; background: var(--gb-border); }
.gb-insert-btn {
    width: 20px; height: 20px;
    border-radius: 50%;
    border: 1px solid var(--gb-border);
    background: var(--gb-bg);
    color: var(--gb-muted);
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.gb-insert-btn:hover { background: var(--gb-accent); color: #fff; border-color: var(--gb-accent); }

/* block + side handles */
.gb-block {
    display: flex;
    gap: 6px;
    border-radius: 8px;
    border: 2px solid transparent;
    transition: border-color .15s;
    padding: 2px 4px;
}
.gb-row.is-selected > .gb-block { border-color: var(--gb-accent); }

.gb-side {
    display: flex;
    flex-direction: column;
    gap: 2px;
    opacity: 0;
    transition: opacity .15s;
    padding-top: 4px;
}
.gb-row:hover .gb-side,
.gb-row.is-selected .gb-side { opacity: 1; }

.gb-side-btn {
    width: 22px; height: 22px;
    border-radius: 5px;
    border: none;
    background: transparent;
    color: var(--gb-muted);
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    padding: 0;
}
.gb-side-btn:hover { background: var(--gb-border); color: var(--gb-text); }
.gb-side-btn:disabled { opacity: .3; cursor: default; }
.gb-drag-icon {
    display: grid; grid-template-columns: 1fr 1fr; gap: 2px; width: 8px;
}
.gb-drag-icon span {
    width: 2px; height: 2px; border-radius: 50%;
    background: currentColor; display: block;
}

.gb-content { flex: 1; min-width: 0; }

/* ── Block toolbar ──────────────────────────────────── */
.gb-block-toolbar {
    display: flex;
    align-items: center;
    gap: 2px;
    padding: 3px 4px;
    margin-bottom: 4px;
    background: var(--gb-focus);
    border: 1px solid var(--gb-border);
    border-radius: 7px;
    flex-wrap: wrap;
}
.gb-tb-btn {
    padding: 3px 7px;
    border-radius: 5px;
    border: none;
    background: transparent;
    color: var(--gb-muted);
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    min-width: 28px;
    text-align: center;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 2px;
}
.gb-tb-btn svg { stroke: currentColor; fill: inherit; }
.gb-tb-btn:hover { background: var(--gb-border); color: var(--gb-text); }
.gb-tb-btn.is-active { background: var(--gb-accent); color: #fff; }
.gb-tb-btn.is-active svg { stroke: #fff; }
.gb-tb-sep { width: 1px; height: 16px; background: var(--gb-border); margin: 0 3px; }
.gb-tb-del {
    margin-left: auto;
    padding: 3px 7px;
    border-radius: 5px;
    border: none;
    background: transparent;
    color: #ef4444;
    font-size: 11px;
    cursor: pointer;
}
.gb-tb-del:hover { background: #fef2f2; }

/* ── Block content elements ─────────────────────────── */
[data-gb-para] {
    outline: none;
    min-height: 1.4em;
    line-height: 1.55;
    font-size: 14px;
    padding: 1px 0;
    font-family: system-ui, sans-serif;
    color: var(--gb-text);
    white-space: pre-wrap;
    word-break: break-word;
}
[data-gb-para]:empty::before {
    content: attr(data-placeholder);
    color: var(--gb-muted);
    pointer-events: none;
}
[data-gb-para] b, [data-gb-para] strong { font-weight: 700; }
[data-gb-para] i, [data-gb-para] em { font-style: italic; }
[data-gb-para] u { text-decoration: underline; }
[data-gb-para] s { text-decoration: line-through; }
[data-gb-para] a { color: var(--gb-accent); text-decoration: underline; }
[data-gb-para] code { font-family: monospace; background: var(--gb-focus); padding: 1px 4px; border-radius: 3px; font-size: .9em; }

.gb-h2 [data-gb-para] { font-size: 1.4rem;  font-weight: 700; line-height: 1.3; }
.gb-h3 [data-gb-para] { font-size: 1.15rem; font-weight: 700; line-height: 1.3; }
.gb-h4 [data-gb-para] { font-size: 1rem;    font-weight: 600; line-height: 1.3; }

/* quote */
.gb-quote-wrap {
    border-left: 3px solid var(--gb-accent);
    padding-left: 14px;
    margin: 0;
}
.gb-quote-cite {
    outline: none;
    font-size: 12px;
    color: var(--gb-muted);
    font-style: italic;
    padding: 2px 0;
    margin-top: 4px;
    font-family: system-ui, sans-serif;
}
.gb-quote-cite:empty::before { content: 'Fuente (opcional)'; color: var(--gb-muted); pointer-events: none; }

/* divider */
.gb-divider { border: none; border-top: 2px solid var(--gb-border); margin: 8px 0; }

/* code block */
.gb-code-wrap {
    background: #0f172a;
    border-radius: 8px;
    padding: 14px 16px;
    color: #e2e8f0;
    font-family: monospace;
    font-size: 13px;
    line-height: 1.6;
    overflow-x: auto;
}
.gb-code-input {
    outline: none;
    white-space: pre;
    min-height: 3em;
    display: block;
    width: 100%;
    word-break: break-all;
}
.gb-code-input:empty::before { content: '// Código…'; color: #64748b; pointer-events: none; }

/* list */
.gb-list { padding-left: 1.5rem; margin: 4px 0; }
.gb-list-item {
    outline: none;
    line-height: 1.6;
    font-size: 15px;
    padding: 2px 0;
    font-family: system-ui, sans-serif;
    color: var(--gb-text);
    min-height: 1em;
}
.gb-list-item:empty::before { content: 'Elemento de lista'; color: var(--gb-muted); pointer-events: none; }
.gb-list-item b, .gb-list-item strong { font-weight: 700; }
.gb-list-item i, .gb-list-item em { font-style: italic; }
.gb-list-item u { text-decoration: underline; }
.gb-list-item s { text-decoration: line-through; }
.gb-list-item a { color: var(--gb-accent); text-decoration: underline; }

/* html legacy block */
.gb-html-legacy { border: 1px dashed #f59e0b; border-radius: 6px; overflow: hidden; }
.gb-html-legacy-badge { background: #fef3c7; color: #92400e; font-size: 11px; font-weight: 600; padding: 4px 10px; letter-spacing: .04em; }
.gb-html-legacy-preview { padding: 12px 14px; font-size: 14px; color: var(--gb-text); max-height: 240px; overflow: auto; border-bottom: 1px dashed #f59e0b; }
.gb-html-legacy-textarea { width: 100%; min-height: 120px; padding: 10px 14px; font-family: ui-monospace,monospace; font-size: 12px; color: #374151; background: #fffbeb; border: none; outline: none; resize: vertical; display: block; }

/* image */
.gb-img-wrap { position: relative; }
.gb-img-wrap img { max-width: 100%; border-radius: 8px; display: block; }
.gb-img-meta {
    display: flex;
    gap: 6px;
    margin-top: 6px;
    flex-wrap: wrap;
    align-items: center;
    font-family: system-ui;
}
.gb-img-meta input {
    flex: 1;
    min-width: 120px;
    border: none;
    border-bottom: 1px solid var(--gb-border);
    outline: none;
    background: transparent;
    font-size: 12px;
    color: var(--gb-muted);
    padding: 2px 0;
}

/* drop zone */
.gb-drop-zone {
    border: 2px dashed var(--gb-border);
    border-radius: 10px;
    padding: 1rem 1.5rem;
    text-align: center;
    cursor: pointer;
    transition: border-color .15s;
    display: block;
}
.gb-drop-zone:hover { border-color: var(--gb-accent); }
.gb-drop-url {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 8px;
}
.gb-drop-url input {
    flex: 1;
    border: none;
    border-bottom: 1px solid var(--gb-border);
    outline: none;
    background: transparent;
    font-size: 13px;
    padding: 3px 0;
    color: var(--gb-text);
    font-family: system-ui;
}

/* video embed */
.gb-video-wrap { border-radius: 1rem; overflow: hidden; }
.gb-video-embed { position: relative; padding-bottom: 56.25%; height: 0; }
.gb-video-embed iframe {
    position: absolute; top: 0; left: 0;
    width: 100%; height: 100%; border: 0;
}

/* uploading state */
.gb-uploading { opacity: .6; pointer-events: none; }

/* ── Block picker modal ─────────────────────────────── */
.gb-picker-backdrop {
    position: fixed; inset: 0; z-index: 200;
    background: rgba(0,0,0,.45);
    display: flex; align-items: center; justify-content: center;
    animation: gbFadeIn .15s ease;
}
.gb-picker {
    background: var(--gb-bg);
    border: 1px solid var(--gb-border);
    border-radius: 14px;
    box-shadow: 0 8px 40px rgba(0,0,0,.18);
    padding: 20px;
    width: 380px;
    max-width: calc(100vw - 32px);
    animation: gbSlideUp .18s ease;
}
.gb-picker-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}
.gb-picker-title {
    font-size: 15px;
    font-weight: 600;
    color: var(--gb-text);
    font-family: system-ui;
}
.gb-picker-close {
    width: 28px; height: 28px;
    border-radius: 6px;
    border: none;
    background: transparent;
    color: var(--gb-muted);
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px;
    line-height: 1;
}
.gb-picker-close:hover { background: var(--gb-focus); color: var(--gb-text); }
.gb-picker-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
}
.gb-picker-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    padding: 14px 8px;
    border-radius: 10px;
    border: 1px solid var(--gb-border);
    cursor: pointer;
    font-size: 11px;
    font-weight: 500;
    color: var(--gb-muted);
    font-family: system-ui;
    background: none;
    text-align: center;
    transition: all .12s;
}
.gb-picker-item:hover {
    background: var(--gb-accent);
    border-color: var(--gb-accent);
    color: #fff;
}
.gb-picker-item:hover svg { stroke: #fff; }
@keyframes gbSlideUp {
    from { opacity: 0; transform: translateY(12px) scale(.97); }
    to   { opacity: 1; transform: none; }
}

/* ── Inline toolbar ─────────────────────────────────── */
.gb-inline-toolbar {
    position: fixed;
    z-index: 60;
    background: #1e293b;
    border-radius: 7px;
    padding: 4px 5px;
    display: flex;
    gap: 2px;
    box-shadow: 0 4px 16px rgba(0,0,0,.25);
    animation: gbFadeIn .1s ease;
}
.gb-inline-btn {
    width: 28px; height: 28px;
    border-radius: 5px;
    border: none;
    background: transparent;
    color: #94a3b8;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700;
}
.gb-inline-btn:hover { background: #334155; color: #fff; }
.gb-inline-btn.is-active { background: var(--gb-accent); color: #fff; }

/* ── Link modal ─────────────────────────────────────── */
.gb-link-backdrop {
    position: fixed; inset: 0; z-index: 300;
    background: rgba(0,0,0,.45);
    display: flex; align-items: center; justify-content: center;
    animation: gbFadeIn .15s ease;
}
.gb-link-modal-card {
    background: var(--gb-bg);
    border: 1px solid var(--gb-border);
    border-radius: 14px;
    box-shadow: 0 8px 40px rgba(0,0,0,.22);
    width: 440px;
    max-width: calc(100vw - 32px);
    animation: gbSlideUp .18s ease;
    font-family: system-ui;
}
.gb-link-modal-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid var(--gb-border);
}
.gb-link-modal-header h3 { margin: 0; font-size: 15px; font-weight: 600; color: var(--gb-text); }
.gb-link-modal-close {
    width: 28px; height: 28px; border-radius: 6px;
    border: none; background: transparent; color: var(--gb-muted);
    cursor: pointer; display: flex; align-items: center; justify-content: center;
}
.gb-link-modal-close:hover { background: var(--gb-focus); color: var(--gb-text); }
.gb-link-modal-body { padding: 18px 20px; display: flex; flex-direction: column; gap: 14px; }
.gb-link-field label {
    display: block; font-size: 11px; font-weight: 600; color: var(--gb-muted);
    text-transform: uppercase; letter-spacing: .05em; margin-bottom: 5px;
}
.gb-link-field input[type=url],
.gb-link-field input[type=text] {
    width: 100%; padding: 8px 10px;
    border: 1px solid var(--gb-border);
    border-radius: 7px; outline: none;
    background: var(--gb-focus); color: var(--gb-text);
    font-size: 13px; font-family: system-ui;
    box-sizing: border-box; transition: border-color .15s;
}
.gb-link-field input:focus { border-color: var(--gb-accent); }
.gb-link-check-row label {
    display: flex; align-items: center; gap: 7px;
    font-size: 13px; color: var(--gb-text); cursor: pointer;
}
.gb-link-check-row input[type=checkbox] { accent-color: var(--gb-accent); width: 15px; height: 15px; flex-shrink: 0; }
.gb-link-rel-group { display: flex; flex-direction: column; gap: 8px; }
.gb-link-rel-group > span {
    font-size: 11px; font-weight: 600; color: var(--gb-muted);
    text-transform: uppercase; letter-spacing: .05em;
}
.gb-link-rel-options { display: flex; flex-wrap: wrap; gap: 6px; }
.gb-link-rel-option {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px; border-radius: 20px;
    border: 1px solid var(--gb-border);
    font-size: 12px; color: var(--gb-muted);
    cursor: pointer; font-family: monospace;
    transition: all .12s; user-select: none;
}
.gb-link-rel-option.is-active { background: var(--gb-accent); border-color: var(--gb-accent); color: #fff; }
.gb-link-rel-option input { display: none; }
.gb-link-seo-tip {
    font-size: 11px; color: var(--gb-muted); line-height: 1.55;
    padding: 8px 10px; background: var(--gb-focus);
    border-radius: 7px; border-left: 3px solid var(--gb-accent);
}
.gb-link-modal-footer {
    display: flex; align-items: center; gap: 8px;
    padding: 14px 20px;
    border-top: 1px solid var(--gb-border);
}
.gb-link-apply-btn {
    padding: 7px 16px; border-radius: 7px; border: none;
    background: var(--gb-accent); color: #fff;
    font-size: 13px; font-weight: 600; cursor: pointer; font-family: system-ui;
}
.gb-link-apply-btn:hover { background: #7e22ce; }
.gb-link-cancel-btn {
    padding: 7px 14px; border-radius: 7px;
    border: 1px solid var(--gb-border); background: transparent;
    color: var(--gb-text); font-size: 13px; cursor: pointer; font-family: system-ui;
}
.gb-link-cancel-btn:hover { background: var(--gb-border); }
.gb-link-remove-btn {
    padding: 7px 14px; border-radius: 7px;
    border: 1px solid #fecaca; background: transparent;
    color: #ef4444; font-size: 13px; cursor: pointer; font-family: system-ui;
}
.gb-link-remove-btn:hover { background: #fef2f2; }

/* ── Media modal ────────────────────────────────────── */
.gb-media-backdrop {
    position: fixed; inset: 0; z-index: 70;
    background: rgba(0,0,0,.5);
    display: flex; align-items: center; justify-content: center;
    padding: 16px;
}
.gb-media-modal {
    background: var(--gb-bg);
    border-radius: 14px;
    box-shadow: 0 20px 60px rgba(0,0,0,.3);
    width: 100%; max-width: 680px;
    max-height: 85vh;
    display: flex; flex-direction: column;
    animation: gbFadeIn .15s ease;
}
.gb-media-header {
    display: flex; align-items: center; justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid var(--gb-border);
    flex-shrink: 0;
}
.gb-media-header h3 { margin: 0; font-size: 15px; font-weight: 600; color: var(--gb-text); font-family: system-ui; }
.gb-media-close {
    width: 28px; height: 28px; border-radius: 50%;
    border: none; background: var(--gb-focus);
    color: var(--gb-muted); cursor: pointer;
    display: flex; align-items: center; justify-content: center;
}
.gb-media-close:hover { background: var(--gb-border); color: var(--gb-text); }
.gb-media-tabs {
    display: flex; border-bottom: 1px solid var(--gb-border); flex-shrink: 0;
}
.gb-media-tab {
    padding: 10px 20px; border: none; background: transparent;
    color: var(--gb-muted); font-size: 13px; cursor: pointer;
    border-bottom: 2px solid transparent; font-family: system-ui;
    transition: color .15s;
}
.gb-media-tab.active { color: var(--gb-text); border-bottom-color: var(--gb-accent); font-weight: 600; }
.gb-media-body { flex: 1; overflow-y: auto; padding: 16px 20px; }
.gb-media-search {
    width: 100%; padding: 8px 12px;
    border: 1px solid var(--gb-border);
    border-radius: 8px; outline: none;
    background: var(--gb-focus);
    color: var(--gb-text);
    font-size: 13px; font-family: system-ui;
    margin-bottom: 12px;
    box-sizing: border-box;
}
.gb-media-search:focus { border-color: var(--gb-accent); }
.gb-media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
    gap: 8px;
}
.gb-media-thumb {
    aspect-ratio: 1;
    border-radius: 8px;
    overflow: hidden;
    cursor: pointer;
    border: 2px solid transparent;
    transition: border-color .15s;
}
.gb-media-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.gb-media-thumb.selected { border-color: var(--gb-accent); }
.gb-media-thumb:hover { border-color: var(--gb-border); }
.gb-media-empty {
    text-align: center; padding: 40px 20px;
    color: var(--gb-muted); font-size: 13px; font-family: system-ui;
}
.gb-media-load-more {
    display: block; width: 100%; margin-top: 12px;
    padding: 8px; border: 1px solid var(--gb-border);
    border-radius: 8px; background: transparent;
    color: var(--gb-muted); font-size: 13px; cursor: pointer;
    font-family: system-ui;
}
.gb-media-load-more:hover { background: var(--gb-focus); }
.gb-media-upload-zone {
    display: block; border: 2px dashed var(--gb-border);
    border-radius: 12px; padding: 40px 20px;
    text-align: center; cursor: pointer;
    transition: border-color .15s;
}
.gb-media-upload-zone:hover { border-color: var(--gb-accent); }
.gb-media-upload-zone input { display: none; }
.gb-media-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 20px;
    border-top: 1px solid var(--gb-border);
    flex-shrink: 0;
}
.gb-media-selected-info { font-size: 12px; color: var(--gb-muted); font-family: system-ui; }
.gb-media-insert {
    padding: 8px 18px; border: none; border-radius: 8px;
    background: var(--gb-accent); color: #fff;
    font-size: 13px; font-weight: 600; cursor: pointer; font-family: system-ui;
}
.gb-media-insert:disabled { opacity: .4; cursor: default; }
.gb-media-insert:not(:disabled):hover { background: #d97706; }

/* ── Animations ─────────────────────────────────────── */
@keyframes gbFadeIn { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: none; } }
@keyframes gb-spin  { to { transform: rotate(360deg); } }
</style>
@endpush
@endonce

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">

<div
    id="{{ $editorId }}"
    x-data="gutenbergEditor({{ json_encode($initialVal) }}, '{{ $statePath }}', '{{ $uploadUrl }}')"
    x-init="init()"
    wire:ignore
    class="gb-editor"
>
    {{-- Hidden input synced to Livewire --}}
    <input type="hidden" :name="statePath" x-ref="hiddenInput" :value="JSON.stringify(blocks)">

    {{-- Top bar --}}
    <div class="gb-topbar">
        <button type="button" class="gb-add-btn" @click.stop="openPicker(blocks.length, true)">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.8" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
            Añadir bloque
        </button>
        <span class="gb-block-count" x-text="blocks.length + ' bloque' + (blocks.length !== 1 ? 's' : '')"></span>
    </div>

    {{-- Document area --}}
    <div class="gb-doc" @click.self="focusEnd()">
        <div style="max-width:42rem;margin:0 auto" x-ref="container">

            <template x-for="(block, index) in blocks" :key="block._id">
                <div
                    class="gb-row"
                    :class="{ 'is-selected': selectedIndex === index }"
                    :data-block="block._id"
                >
                    {{-- Insert-before line --}}
                    <div class="gb-insert-row">
                        <div class="gb-insert-line"></div>
                        <button type="button" class="gb-insert-btn" @click.stop="openPicker(index, false)" title="Insertar bloque aquí">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.8" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4"/></svg>
                        </button>
                        <div class="gb-insert-line"></div>
                    </div>

                    <div class="gb-block" @click="selectedIndex = index">

                        {{-- Side handles --}}
                        <div class="gb-side">
                            <div class="gb-side-btn" style="cursor:grab" title="Reordenar">
                                <div class="gb-drag-icon"><span></span><span></span><span></span><span></span><span></span><span></span></div>
                            </div>
                            <button type="button" class="gb-side-btn" @click.stop="moveBlock(index,-1)" :disabled="index===0" title="Subir">
                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 15l-6-6-6 6"/></svg>
                            </button>
                            <button type="button" class="gb-side-btn" @click.stop="moveBlock(index,1)" :disabled="index===blocks.length-1" title="Bajar">
                                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                            </button>
                        </div>

                        {{-- Content --}}
                        <div class="gb-content">

                            {{-- Toolbar (when selected) --}}
                            <div x-show="selectedIndex === index" class="gb-block-toolbar" @click.stop>

                                {{-- Tipo párrafo / título --}}
                                <template x-if="block.type==='paragraph'||block.type==='heading'">
                                    <div style="display:contents">
                                        <button type="button" class="gb-tb-btn" :class="{'is-active':block.type==='paragraph'}" @mousedown.prevent @click.stop="setType(index,'paragraph')" title="Párrafo">P</button>
                                        <button type="button" class="gb-tb-btn" :class="{'is-active':block.type==='heading'&&block.data.level==='h2'}" @mousedown.prevent @click.stop="setHeading(index,'h2')" title="Título 2">H2</button>
                                        <button type="button" class="gb-tb-btn" :class="{'is-active':block.type==='heading'&&block.data.level==='h3'}" @mousedown.prevent @click.stop="setHeading(index,'h3')" title="Título 3">H3</button>
                                        <button type="button" class="gb-tb-btn" :class="{'is-active':block.type==='heading'&&block.data.level==='h4'}" @mousedown.prevent @click.stop="setHeading(index,'h4')" title="Título 4">H4</button>
                                        <div class="gb-tb-sep"></div>
                                        {{-- Alineación: botones explícitos con paths reales de Tabler Icons --}}
                                        <button type="button" class="gb-tb-btn" :class="{'is-active':(block.data.align||'left')==='left'}" @mousedown.prevent @click.stop="block.data.align='left'" title="Alinear izquierda">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16"/><path d="M4 12h10"/><path d="M4 18h14"/></svg>
                                        </button>
                                        <button type="button" class="gb-tb-btn" :class="{'is-active':(block.data.align||'left')==='center'}" @mousedown.prevent @click.stop="block.data.align='center'" title="Centrar">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16"/><path d="M7 12h10"/><path d="M5 18h14"/></svg>
                                        </button>
                                        <button type="button" class="gb-tb-btn" :class="{'is-active':(block.data.align||'left')==='right'}" @mousedown.prevent @click.stop="block.data.align='right'" title="Alinear derecha">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16"/><path d="M10 12h10"/><path d="M6 18h14"/></svg>
                                        </button>
                                        <div class="gb-tb-sep"></div>
                                        {{-- Formato de texto --}}
                                        <button type="button" class="gb-tb-btn" :class="{'is-active':queryCmd('bold')}" @mousedown.prevent @click.stop="fmt('bold')" title="Negrita">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 5h6a3.5 3.5 0 0 1 0 7H7z"/><path d="M13 12h1a3.5 3.5 0 0 1 0 7H7v-7"/></svg>
                                        </button>
                                        <button type="button" class="gb-tb-btn" :class="{'is-active':queryCmd('italic')}" @mousedown.prevent @click.stop="fmt('italic')" title="Cursiva">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="11" y1="5" x2="17" y2="5"/><line x1="7" y1="19" x2="13" y2="19"/><line x1="14" y1="5" x2="10" y2="19"/></svg>
                                        </button>
                                        <button type="button" class="gb-tb-btn" :class="{'is-active':queryCmd('underline')}" @mousedown.prevent @click.stop="fmt('underline')" title="Subrayado">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M7 5v5a5 5 0 0 0 10 0V5"/><line x1="5" y1="19" x2="19" y2="19"/></svg>
                                        </button>
                                        <button type="button" class="gb-tb-btn" :class="{'is-active':queryCmd('strikeThrough')}" @mousedown.prevent @click.stop="fmt('strikeThrough')" title="Tachado">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/><path d="M16 6.5a3.5 2 0 0 0-3.5-1.5h-1a3.5 3.5 0 0 0 0 7h2a3.5 3.5 0 0 1 0 7h-1.5a3.5 2 0 0 1-3.5-1.5"/></svg>
                                        </button>
                                        <button type="button" class="gb-tb-btn" @mousedown.prevent @click.stop="openLinkPanel()" title="Enlace">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 15l6-6"/><path d="M11 6l.463-.536a5 5 0 0 1 7.071 7.072L18 13"/><path d="M13 18l-.397.534a5.068 5.068 0 0 1-7.127 0a4.972 4.972 0 0 1 0-7.071L6 11"/></svg>
                                        </button>
                                    </div>
                                </template>

                                {{-- Quote formato --}}
                                <template x-if="block.type==='quote'">
                                    <div style="display:contents">
                                        <button type="button" class="gb-tb-btn" :class="{'is-active':queryCmd('bold')}" @mousedown.prevent @click.stop="fmt('bold')" title="Negrita">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 5h6a3.5 3.5 0 0 1 0 7H7z"/><path d="M13 12h1a3.5 3.5 0 0 1 0 7H7v-7"/></svg>
                                        </button>
                                        <button type="button" class="gb-tb-btn" :class="{'is-active':queryCmd('italic')}" @mousedown.prevent @click.stop="fmt('italic')" title="Cursiva">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="11" y1="5" x2="17" y2="5"/><line x1="7" y1="19" x2="13" y2="19"/><line x1="14" y1="5" x2="10" y2="19"/></svg>
                                        </button>
                                        <button type="button" class="gb-tb-btn" :class="{'is-active':queryCmd('underline')}" @mousedown.prevent @click.stop="fmt('underline')" title="Subrayado">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M7 5v5a5 5 0 0 0 10 0V5"/><line x1="5" y1="19" x2="19" y2="19"/></svg>
                                        </button>
                                        <button type="button" class="gb-tb-btn" :class="{'is-active':queryCmd('strikeThrough')}" @mousedown.prevent @click.stop="fmt('strikeThrough')" title="Tachado">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/><path d="M16 6.5a3.5 2 0 0 0-3.5-1.5h-1a3.5 3.5 0 0 0 0 7h2a3.5 3.5 0 0 1 0 7h-1.5a3.5 2 0 0 1-3.5-1.5"/></svg>
                                        </button>
                                        <button type="button" class="gb-tb-btn" @mousedown.prevent @click.stop="openLinkPanel()" title="Enlace">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 15l6-6"/><path d="M11 6l.463-.536a5 5 0 0 1 7.071 7.072L18 13"/><path d="M13 18l-.397.534a5.068 5.068 0 0 1-7.127 0a4.972 4.972 0 0 1 0-7.071L6 11"/></svg>
                                        </button>
                                    </div>
                                </template>

                                {{-- Lista --}}
                                <template x-if="block.type==='list'">
                                    <div style="display:contents">
                                        <button type="button" class="gb-tb-btn" :class="{'is-active':(block.data.style||'unordered')==='unordered'}" @mousedown.prevent @click.stop="block.data.style='unordered'" title="Lista sin orden">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><circle cx="5" cy="6" r="1" fill="currentColor" stroke="none"/><circle cx="5" cy="12" r="1" fill="currentColor" stroke="none"/><circle cx="5" cy="18" r="1" fill="currentColor" stroke="none"/></svg>
                                        </button>
                                        <button type="button" class="gb-tb-btn" :class="{'is-active':(block.data.style||'unordered')==='ordered'}" @mousedown.prevent @click.stop="block.data.style='ordered'" title="Lista numerada">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path d="M4 6h1v4"/><path d="M4 10h2"/><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg>
                                        </button>
                                        <div class="gb-tb-sep"></div>
                                        <button type="button" class="gb-tb-btn" @mousedown.prevent @click.stop="addListItem(index)" title="Añadir ítem">+ ítem</button>
                                    </div>
                                </template>

                                <button type="button" class="gb-tb-del" @click.stop="removeBlock(index)">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                    Eliminar
                                </button>
                            </div>

                            {{-- ═══ PARAGRAPH ═══ --}}
                            <template x-if="block.type==='paragraph'">
                                <div
                                    :class="{'gb-h2':block.data.level==='h2','gb-h3':block.data.level==='h3','gb-h4':block.data.level==='h4'}"
                                    :style="'text-align:'+(block.data.align||'left')"
                                >
                                    <div
                                        data-gb-para
                                        contenteditable="true"
                                        data-placeholder="Escribe algo, o pulsa / para elegir un bloque"
                                        x-ref="'para_'+block._id"
                                        x-init="$el.innerHTML = block.data.text || ''"
                                        @input="block.data.text = $event.target.innerHTML; syncState()"
                                        @keydown.slash.prevent="openPicker(index, false)"
                                        @keydown.enter.prevent="splitOrNewBlock(index, $event)"
                                        @keydown.backspace="handleBackspace(index, $event)"
                                        @focus="selectedIndex = index"
                                        @mouseup="checkInlineSelection()"
                                        @keyup="checkInlineSelection()"
                                        :style="'text-align:'+(block.data.align||'left')"
                                    ></div>
                                </div>
                            </template>

                            {{-- ═══ HEADING ═══ --}}
                            <template x-if="block.type==='heading'">
                                <div :style="'text-align:'+(block.data.align||'left')">
                                    <div
                                        data-gb-para
                                        contenteditable="true"
                                        :data-placeholder="'Título ' + (block.data.level||'h2')"
                                        @input="block.data.text = $event.target.innerHTML; syncState()"
                                        @keydown.enter.prevent="splitOrNewBlock(index, $event)"
                                        @keydown.backspace="handleBackspace(index, $event)"
                                        @focus="selectedIndex = index"
                                        @mouseup="checkInlineSelection()"
                                        @keyup="checkInlineSelection()"
                                        x-init="$el.innerHTML = block.data.text || ''"
                                        :style="'font-size:'+(block.data.level==='h2'?'1.65rem':block.data.level==='h3'?'1.3rem':'1.1rem')+';font-weight:700;text-align:'+(block.data.align||'left')"
                                    ></div>
                                </div>
                            </template>

                            {{-- ═══ IMAGE ═══ --}}
                            <template x-if="block.type==='image'">
                                <div>
                                    <template x-if="block.data.src">
                                        <figure class="gb-img-wrap">
                                            <img :src="imgSrc(block.data.src)" :alt="block.data.alt||''">
                                            <div x-show="selectedIndex===index" class="gb-img-meta">
                                                <input type="text" :value="block.data.alt" @input="block.data.alt=$event.target.value" placeholder="Texto alternativo (alt)">
                                                <input type="text" :value="block.data.caption" @input="block.data.caption=$event.target.value" placeholder="Pie de foto">
                                                <button type="button" @click.stop="block.data.src=''" style="font-size:11px;color:#ef4444;background:none;border:none;cursor:pointer;font-family:system-ui">Cambiar</button>
                                            </div>
                                            <figcaption x-show="block.data.caption && selectedIndex!==index" x-text="block.data.caption" style="text-align:center;font-size:0.875rem;margin-top:0.25rem;font-family:system-ui;color:var(--gb-muted)"></figcaption>
                                        </figure>
                                    </template>
                                    <template x-if="!block.data.src">
                                        <div :class="uploadingIndex===index?'gb-uploading':''">
                                            <button type="button" class="gb-drop-zone" style="width:100%;background:none;cursor:pointer" @click.stop="openMediaModal(index)">
                                                <svg width="20" height="20" style="display:block;margin:0 auto 6px;opacity:.3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                <p style="font-family:system-ui;font-size:13px;color:var(--gb-muted)" x-text="uploadingIndex===index?'Subiendo…':'Seleccionar imagen'"></p>
                                                <p style="font-family:system-ui;font-size:11px;color:#d1d5db;margin-top:2px">Abre la biblioteca de medios</p>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            {{-- ═══ VIDEO ═══ --}}
                            <template x-if="block.type==='video'">
                                <div>
                                    <template x-if="block.data.url">
                                        <div>
                                            <div class="gb-video-wrap">
                                                <div class="gb-video-embed">
                                                    <iframe :src="toEmbedUrl(block.data.url)" allowfullscreen></iframe>
                                                </div>
                                            </div>
                                            <div x-show="selectedIndex===index" style="display:flex;gap:8px;margin-top:8px;align-items:center;font-family:system-ui">
                                                <input type="url" :value="block.data.url" @change="block.data.url=$event.target.value" style="flex:1;border:none;border-bottom:1px solid var(--gb-border);outline:none;background:transparent;font-size:12px;color:var(--gb-muted);padding:2px 0">
                                                <button type="button" @click.stop="block.data.url=''" style="font-size:11px;color:#ef4444;background:none;border:none;cursor:pointer;font-family:system-ui">Quitar</button>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!block.data.url">
                                        <label class="gb-drop-zone" style="cursor:default">
                                            <svg width="20" height="20" style="display:block;margin:0 auto 6px;opacity:.3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <p style="font-family:system-ui;font-size:13px;color:var(--gb-muted)">Pega una URL de YouTube o Vimeo</p>
                                            <div class="gb-drop-url" @click.stop>
                                                <svg width="14" height="14" style="color:var(--gb-muted);flex-shrink:0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><circle cx="12" cy="12" r="9"/></svg>
                                                <input type="url" placeholder="https://www.youtube.com/watch?v=…" @change="if($event.target.value) block.data.url=$event.target.value">
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </template>

                            {{-- ═══ QUOTE ═══ --}}
                            <template x-if="block.type==='quote'">
                                <blockquote class="gb-quote-wrap">
                                    <div data-gb-para contenteditable="true" data-placeholder="Cita…"
                                        x-init="$el.innerHTML = block.data.text || ''"
                                        @input="block.data.text=$event.target.innerHTML;syncState()"
                                        @focus="selectedIndex=index"></div>
                                    <div class="gb-quote-cite" contenteditable="true"
                                        x-init="$el.textContent = block.data.cite || ''"
                                        @input="block.data.cite=$event.target.innerText;syncState()"
                                        @focus="selectedIndex=index"></div>
                                </blockquote>
                            </template>

                            {{-- ═══ LIST ═══ --}}
                            <template x-if="block.type==='list'">
                                <div>
                                    <template x-if="(block.data.style||'unordered')==='unordered'">
                                        <ul class="gb-list">
                                            <template x-for="(item, i) in block.data.items" :key="i">
                                                <li class="gb-list-item" contenteditable="true"
                                                    x-init="$el.innerHTML = item.text || ''"
                                                    @input="item.text=$event.target.innerHTML;syncState()"
                                                    @keydown.enter.prevent="addListItem(index)"
                                                    @keydown.backspace="removeListItemIfEmpty(index,i,$event)"
                                                    @focus="selectedIndex=index"
                                                    @mouseup="showInlineToolbar($event)"
                                                    @keyup="showInlineToolbar($event)"></li>
                                            </template>
                                        </ul>
                                    </template>
                                    <template x-if="(block.data.style||'unordered')==='ordered'">
                                        <ol class="gb-list">
                                            <template x-for="(item, i) in block.data.items" :key="i">
                                                <li class="gb-list-item" contenteditable="true"
                                                    x-init="$el.innerHTML = item.text || ''"
                                                    @input="item.text=$event.target.innerHTML;syncState()"
                                                    @keydown.enter.prevent="addListItem(index)"
                                                    @keydown.backspace="removeListItemIfEmpty(index,i,$event)"
                                                    @focus="selectedIndex=index"
                                                    @mouseup="showInlineToolbar($event)"
                                                    @keyup="showInlineToolbar($event)"></li>
                                            </template>
                                        </ol>
                                    </template>
                                </div>
                            </template>

                            {{-- ═══ DIVIDER ═══ --}}
                            <template x-if="block.type==='divider'">
                                <hr class="gb-divider">
                            </template>

                            {{-- ═══ CODE ═══ --}}
                            <template x-if="block.type==='code'">
                                <div class="gb-code-wrap">
                                    <div class="gb-code-input" contenteditable="true"
                                        data-placeholder="// Código…"
                                        x-init="$el.textContent = block.data.code || ''"
                                        @input="block.data.code=$event.target.innerText;syncState()"
                                        @focus="selectedIndex=index"></div>
                                </div>
                            </template>

                            {{-- ═══ HTML LEGACY ═══ --}}
                            <template x-if="block.type==='html'">
                                <div class="gb-html-legacy">
                                    <div class="gb-html-legacy-badge">HTML importado</div>
                                    <div class="gb-html-legacy-preview" x-html="block.data.html"></div>
                                    <textarea class="gb-html-legacy-textarea"
                                        spellcheck="false"
                                        x-init="$el.value = block.data.html || ''"
                                        @input="block.data.html=$event.target.value;syncState()"
                                        @focus="selectedIndex=index"
                                        placeholder="HTML…"></textarea>
                                </div>
                            </template>

                        </div>{{-- /gb-content --}}
                    </div>{{-- /gb-block --}}
                </div>{{-- /gb-row --}}
            </template>

            {{-- Empty state --}}
            <template x-if="blocks.length===0">
                <div style="padding:40px 0;text-align:center;color:var(--gb-muted);font-family:system-ui;font-size:14px">
                    <p>Pulsa <strong>Añadir bloque</strong> para empezar a escribir.</p>
                </div>
            </template>

        </div>{{-- /container --}}
    </div>{{-- /gb-doc --}}

    {{-- ── Block picker modal ── --}}
    <template x-if="picker.open">
        <div class="gb-picker-backdrop" @click.self="picker.open=false" @keydown.escape.window="picker.open=false">
            <div class="gb-picker" @click.stop>
                <div class="gb-picker-header">
                    <span class="gb-picker-title">Añadir bloque</span>
                    <button type="button" class="gb-picker-close" @click="picker.open=false">✕</button>
                </div>
                <div class="gb-picker-grid">
                    <template x-for="opt in pickerOptions" :key="opt.type">
                        <button type="button" class="gb-picker-item" @click="addBlock(opt.type)">
                            <span x-html="opt.icon"></span>
                            <span x-text="opt.label"></span>
                        </button>
                    </template>
                </div>
            </div>
        </div>
    </template>

    {{-- ── Inline toolbar ── --}}
    <div
        class="gb-inline-toolbar"
        x-show="inlineToolbar.visible"
        :style="'top:'+inlineToolbar.y+'px;left:'+inlineToolbar.x+'px'"
        @mousedown.prevent
        style="display:none"
    >
        <button type="button" class="gb-inline-btn" :class="{'is-active':queryCmd('bold')}" @click="fmt('bold')" title="Negrita">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 5h6a3.5 3.5 0 0 1 0 7H7z"/><path d="M13 12h1a3.5 3.5 0 0 1 0 7H7v-7"/></svg>
        </button>
        <button type="button" class="gb-inline-btn" :class="{'is-active':queryCmd('italic')}" @click="fmt('italic')" title="Cursiva">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="11" y1="5" x2="17" y2="5"/><line x1="7" y1="19" x2="13" y2="19"/><line x1="14" y1="5" x2="10" y2="19"/></svg>
        </button>
        <button type="button" class="gb-inline-btn" :class="{'is-active':queryCmd('underline')}" @click="fmt('underline')" title="Subrayado">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M7 5v5a5 5 0 0 0 10 0V5"/><line x1="5" y1="19" x2="19" y2="19"/></svg>
        </button>
        <button type="button" class="gb-inline-btn" :class="{'is-active':queryCmd('strikeThrough')}" @click="fmt('strikeThrough')" title="Tachado">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/><path d="M16 6.5a3.5 2 0 0 0-3.5-1.5h-1a3.5 3.5 0 0 0 0 7h2a3.5 3.5 0 0 1 0 7h-1.5a3.5 2 0 0 1-3.5-1.5"/></svg>
        </button>
        <button type="button" class="gb-inline-btn" @click="openLinkPanel()" title="Enlace">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 15l6-6"/><path d="M11 6l.463-.536a5 5 0 0 1 7.071 7.072L18 13"/><path d="M13 18l-.397.534a5.068 5.068 0 0 1-7.127 0a4.972 4.972 0 0 1 0-7.071L6 11"/></svg>
        </button>
    </div>

    {{-- ── Link modal ── --}}
    <template x-if="linkModal.open">
        <div class="gb-link-backdrop" @click.self="linkModal.open=false" @keydown.escape.window="linkModal.open=false">
            <div class="gb-link-modal-card" @click.stop>

                <div class="gb-link-modal-header">
                    <h3>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-3px;margin-right:6px"><path d="M9 15l6-6"/><path d="M11 6l.463-.536a5 5 0 0 1 7.071 7.072L18 13"/><path d="M13 18l-.397.534a5.068 5.068 0 0 1-7.127 0a4.972 4.972 0 0 1 0-7.071L6 11"/></svg>
                        Insertar enlace
                    </h3>
                    <button type="button" class="gb-link-modal-close" @click="linkModal.open=false">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="gb-link-modal-body">

                    {{-- URL --}}
                    <div class="gb-link-field">
                        <label>URL del enlace *</label>
                        <input type="url" x-ref="linkUrlInput" x-model="linkModal.url"
                            placeholder="https://ejemplo.com/pagina/"
                            @keydown.enter.prevent="applyLink()">
                    </div>

                    {{-- Título --}}
                    <div class="gb-link-field">
                        <label>Título del enlace <span style="font-weight:400;text-transform:none">(atributo title / tooltip)</span></label>
                        <input type="text" x-model="linkModal.title"
                            placeholder="Descripción visible al pasar el cursor">
                    </div>

                    {{-- Nueva pestaña --}}
                    <div class="gb-link-check-row">
                        <label>
                            <input type="checkbox" x-model="linkModal.newTab">
                            Abrir en nueva pestaña (<code style="font-size:11px;background:var(--gb-focus);padding:1px 5px;border-radius:4px">target="_blank"</code>)
                        </label>
                    </div>

                    {{-- Rel SEO --}}
                    <div class="gb-link-rel-group">
                        <span>Atributo <code style="font-size:10px;background:var(--gb-focus);padding:1px 5px;border-radius:4px;font-weight:400;text-transform:none">rel</code> — para SEO</span>
                        <div class="gb-link-rel-options">
                            <label class="gb-link-rel-option" :class="{'is-active': linkModal.rel.includes('nofollow')}">
                                <input type="checkbox" :checked="linkModal.rel.includes('nofollow')" @change="toggleRel('nofollow', $event)">
                                nofollow
                            </label>
                            <label class="gb-link-rel-option" :class="{'is-active': linkModal.rel.includes('sponsored')}">
                                <input type="checkbox" :checked="linkModal.rel.includes('sponsored')" @change="toggleRel('sponsored', $event)">
                                sponsored
                            </label>
                            <label class="gb-link-rel-option" :class="{'is-active': linkModal.rel.includes('ugc')}">
                                <input type="checkbox" :checked="linkModal.rel.includes('ugc')" @change="toggleRel('ugc', $event)">
                                ugc
                            </label>
                            <label class="gb-link-rel-option" :class="{'is-active': linkModal.rel.includes('noreferrer')}">
                                <input type="checkbox" :checked="linkModal.rel.includes('noreferrer')" @change="toggleRel('noreferrer', $event)">
                                noreferrer
                            </label>
                            <label class="gb-link-rel-option" :class="{'is-active': linkModal.rel.includes('noopener')}">
                                <input type="checkbox" :checked="linkModal.rel.includes('noopener')" @change="toggleRel('noopener', $event)">
                                noopener
                            </label>
                        </div>
                        <p class="gb-link-seo-tip">
                            <strong>nofollow</strong>: no pasar autoridad SEO · <strong>sponsored</strong>: enlace patrocinado · <strong>ugc</strong>: contenido generado por usuarios · <strong>noreferrer + noopener</strong>: recomendado con nueva pestaña por seguridad.
                        </p>
                    </div>

                </div>

                <div class="gb-link-modal-footer">
                    <button type="button" class="gb-link-remove-btn" x-show="linkModal.hasLink" @click="removeLink()">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="display:inline-block;vertical-align:-2px;margin-right:4px"><path d="M18 6L6 18M6 6l12 12"/></svg>
                        Quitar enlace
                    </button>
                    <div style="display:flex;gap:8px;margin-left:auto">
                        <button type="button" class="gb-link-cancel-btn" @click="linkModal.open=false">Cancelar</button>
                        <button type="button" class="gb-link-apply-btn" @click="applyLink()">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="display:inline-block;vertical-align:-2px;margin-right:4px"><path d="M20 6L9 17l-5-5"/></svg>
                            Aplicar enlace
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </template>

    {{-- ── Media modal ── --}}
    <template x-if="mediaModal.open">
        <div class="gb-media-backdrop" @click.self="mediaModal.open=false">
            <div class="gb-media-modal" @click.stop>

                <div class="gb-media-header">
                    <h3>Biblioteca de medios</h3>
                    <button type="button" class="gb-media-close" @click="mediaModal.open=false">✕</button>
                </div>

                <div class="gb-media-tabs">
                    <button type="button" class="gb-media-tab" :class="{active:mediaModal.tab==='library'}" @click="mediaModal.tab='library';loadMedia()">Biblioteca</button>
                    <button type="button" class="gb-media-tab" :class="{active:mediaModal.tab==='upload'}" @click="mediaModal.tab='upload'">Subir imagen</button>
                </div>

                <div class="gb-media-body">

                    <template x-if="mediaModal.tab==='library'">
                        <div>
                            <input
                                type="search"
                                class="gb-media-search"
                                placeholder="Buscar por nombre…"
                                x-model="mediaModal.search"
                                @input.debounce.350ms="loadMedia()"
                            >
                            <div class="gb-media-grid">
                                <template x-if="!mediaModal.loading && mediaModal.items.length===0">
                                    <div class="gb-media-empty">
                                        <svg width="40" height="40" style="display:block;margin:0 auto 8px;opacity:.2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <p>No hay imágenes. Usa "Subir imagen".</p>
                                    </div>
                                </template>
                                <template x-for="item in mediaModal.items" :key="item.id">
                                    <div class="gb-media-thumb" :class="{selected:mediaModal.selected?.id===item.id}" @click="mediaModal.selected=item" @dblclick="insertSelectedMedia()" :title="item.filename">
                                        <img :src="item.url" :alt="item.filename" loading="lazy">
                                    </div>
                                </template>
                                <template x-if="mediaModal.loading">
                                    <div class="gb-media-empty" style="grid-column:1/-1">
                                        <svg width="24" height="24" style="display:block;margin:0 auto;opacity:.4;animation:gb-spin 1s linear infinite" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="32" stroke-dashoffset="8"/></svg>
                                    </div>
                                </template>
                            </div>
                            <button type="button" class="gb-media-load-more" x-show="mediaModal.hasMore && !mediaModal.loading" @click="loadMoreMedia()">
                                Cargar más imágenes
                            </button>
                        </div>
                    </template>

                    <template x-if="mediaModal.tab==='upload'">
                        <div>
                            <label class="gb-media-upload-zone" :class="mediaModal.uploading?'gb-uploading':''">
                                <input type="file" accept="image/*" multiple @change="uploadToLibrary($event)">
                                <svg width="24" height="24" style="display:block;margin:0 auto 8px;opacity:.3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                <p style="font-size:14px;font-weight:600;color:var(--gb-text)" x-text="mediaModal.uploading?'Subiendo…':'Haz clic o arrastra imágenes aquí'"></p>
                                <p style="font-size:12px;color:var(--gb-muted);margin-top:4px">JPG, PNG, WebP · máx. 8 MB</p>
                            </label>
                        </div>
                    </template>

                </div>

                <div class="gb-media-footer">
                    <span class="gb-media-selected-info" x-text="mediaModal.selected?mediaModal.selected.filename:'Ninguna imagen seleccionada'"></span>
                    <button type="button" class="gb-media-insert" :disabled="!mediaModal.selected" @click="insertSelectedMedia()">Insertar imagen</button>
                </div>

            </div>
        </div>
    </template>

</div>{{-- /gb-editor --}}

</x-dynamic-component>

<script>
function gutenbergEditor(initialBlocks, statePath, uploadUrl) {
    return {
        blocks: [],
        _nextId: 1,
        selectedIndex: -1,
        uploadingIndex: -1,
        statePath,
        uploadUrl,

        picker: { open: false, atIndex: 0, append: true },
        inlineToolbar: { visible: false, x: 0, y: 0 },
        linkModal: { open: false, url: '', title: '', newTab: false, rel: [], hasLink: false, savedRange: null },
        mediaModal: {
            open: false, tab: 'library', targetIndex: -1,
            items: [], loading: false, hasMore: false, page: 1, search: '', selected: null, uploading: false,
        },

        pickerOptions: [
            { type: 'paragraph', label: 'Párrafo',   icon: '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h10"/></svg>' },
            { type: 'heading',   label: 'Título',    icon: '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h1m0 0v12m0-6h14m0-6h1m-1 0v12m-1 0h1"/></svg>' },
            { type: 'image',     label: 'Imagen',    icon: '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 15l5-5 4 4 3-3 4 4"/></svg>' },
            { type: 'video',     label: 'Video',     icon: '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' },
            { type: 'quote',     label: 'Cita',      icon: '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"/></svg>' },
            { type: 'list',      label: 'Lista',     icon: '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 6h11M9 12h11M9 18h11M5 6h.01M5 12h.01M5 18h.01"/></svg>' },
            { type: 'divider',   label: 'Divisor',   icon: '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14"/></svg>' },
            { type: 'code',      label: 'Código',    icon: '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 20l4-16M6.5 17.5l-5-5 5-5M17.5 6.5l5 5-5 5"/></svg>' },
        ],

        init() {
            try {
                const parsed = typeof initialBlocks === 'string' ? JSON.parse(initialBlocks) : initialBlocks;
                this.blocks = Array.isArray(parsed) ? parsed.map(b => ({ ...b, _id: this._nextId++ })) : [];
            } catch {
                // Contenido legacy (HTML puro o WordPress) → convertir a bloques
                const raw = typeof initialBlocks === 'string' ? initialBlocks.trim() : '';
                this.blocks = raw ? this.htmlToBlocks(raw) : [];
            }

            if (this.blocks.length === 0) this.blocks = [this.newBlock('paragraph')];

            this.$watch('blocks', () => this.syncState());
            document.addEventListener('click', () => { this.inlineToolbar.visible = false; });
            document.addEventListener('selectionchange', () => { if (!window.getSelection()?.toString()) this.inlineToolbar.visible = false; });
        },

        newBlock(type, data = {}) {
            const defaults = {
                paragraph: { text: '', align: 'left' },
                heading:   { text: '', level: 'h2', align: 'left' },
                html:      { html: '' },
                image:     { src: '', alt: '', caption: '' },
                video:     { url: '' },
                quote:     { text: '', cite: '' },
                list:      { style: 'unordered', items: [{ text: '' }] },
                divider:   {},
                code:      { code: '' },
            };
            return { _id: this._nextId++, type, data: { ...(defaults[type] || {}), ...data } };
        },

        htmlToBlocks(html) {
            // Quita comentarios WordPress (<!-- wp:... --> y <!-- /wp:... -->)
            const clean = html.replace(/<!--\s*\/?wp:[^>]*-->/g, '').trim();

            const doc = new DOMParser().parseFromString(clean, 'text/html');
            const blocks = [];

            const push = (type, data) => blocks.push(this.newBlock(type, data));

            const innerHtml = (el) => el.innerHTML.trim();
            const innerText = (el) => el.textContent.trim();

            // Limpia atributos de estilo inline (color, font-size, etc.) dejando
            // solo las etiquetas semánticas: <strong>, <em>, <a>, <u>, <s>
            const cleanInline = (html) => {
                const d = document.createElement('div');
                d.innerHTML = html;
                d.querySelectorAll('[style],[class]').forEach(el => {
                    el.removeAttribute('style');
                    el.removeAttribute('class');
                    // Spans sin atributos → reemplazar con su contenido
                    if (el.tagName === 'SPAN' && el.attributes.length === 0) {
                        el.replaceWith(...el.childNodes);
                    }
                });
                return d.innerHTML.trim();
            };

            const children = [...doc.body.childNodes];
            for (const node of children) {
                if (node.nodeType === Node.TEXT_NODE) {
                    const t = node.textContent.trim();
                    if (t) push('paragraph', { text: t, align: 'left' });
                    continue;
                }
                if (node.nodeType !== Node.ELEMENT_NODE) continue;

                const tag = node.tagName.toLowerCase();

                if (/^h[1-6]$/.test(tag)) {
                    const text = cleanInline(innerHtml(node));
                    if (text) push('heading', { text, level: tag, align: 'left' });

                } else if (tag === 'p') {
                    const text = cleanInline(innerHtml(node));
                    if (text) push('paragraph', { text, align: 'left' });

                } else if (tag === 'ul' || tag === 'ol') {
                    const items = [...node.querySelectorAll(':scope > li')]
                        .map(li => ({ text: cleanInline(li.innerHTML) }))
                        .filter(i => i.text);
                    if (items.length) push('list', { style: tag === 'ol' ? 'ordered' : 'unordered', items });

                } else if (tag === 'blockquote') {
                    const cite = node.querySelector('cite');
                    const citeText = cite ? innerText(cite) : '';
                    if (cite) cite.remove();
                    push('quote', { text: cleanInline(node.innerHTML), cite: citeText });

                } else if (tag === 'hr') {
                    push('divider');

                } else if (tag === 'pre') {
                    push('code', { code: innerText(node) });

                } else if (tag === 'figure') {
                    const img = node.querySelector('img');
                    const caption = node.querySelector('figcaption');
                    if (img) push('image', { src: img.getAttribute('src') || '', alt: img.getAttribute('alt') || '', caption: caption ? innerText(caption) : '' });

                } else if (tag === 'img') {
                    push('image', { src: node.getAttribute('src') || '', alt: node.getAttribute('alt') || '', caption: '' });

                } else if (tag === 'table') {
                    // Tabla → párrafo con HTML limpio (no tenemos bloque tabla)
                    push('paragraph', { text: node.outerHTML, align: 'left' });

                } else {
                    // div, span, section, article, etc. → extraer texto como párrafo
                    const text = cleanInline(innerHtml(node));
                    if (text) push('paragraph', { text, align: 'left' });
                }
            }

            // Si el body no tenía hijos tipo bloque (solo inline/texto puro)
            if (blocks.length === 0 && doc.body.innerHTML.trim()) {
                push('paragraph', { text: cleanInline(doc.body.innerHTML), align: 'left' });
            }

            return blocks.length ? blocks : [this.newBlock('paragraph')];
        },

        syncState() {
            const clean = this.blocks.map(({ _id, ...rest }) => rest);
            const json  = JSON.stringify(clean);

            // Actualizar el input oculto (fallback)
            this.$refs.hiddenInput.value = json;

            // Actualizar el estado de Livewire directamente, con debounce
            // para no disparar un request por cada tecla pulsada
            clearTimeout(this._syncTimer);
            this._syncTimer = setTimeout(() => {
                if (this.$wire) {
                    this.$wire.$set(this.statePath, json);
                }
            }, 400);
        },

        openPicker(index, append) {
            this.picker = { open: true, atIndex: index, append };
        },

        addBlock(type) {
            const b = this.newBlock(type);
            if (this.picker.append) {
                this.blocks.push(b);
                this.selectedIndex = this.blocks.length - 1;
            } else {
                this.blocks.splice(this.picker.atIndex, 0, b);
                this.selectedIndex = this.picker.atIndex;
            }
            this.picker.open = false;
            this.$nextTick(() => this.focusBlock(this.selectedIndex));
        },

        removeBlock(index) {
            this.blocks.splice(index, 1);
            if (this.blocks.length === 0) this.blocks = [this.newBlock('paragraph')];
            this.selectedIndex = Math.min(index, this.blocks.length - 1);
        },

        moveBlock(index, dir) {
            const to = index + dir;
            if (to < 0 || to >= this.blocks.length) return;
            [this.blocks[index], this.blocks[to]] = [this.blocks[to], this.blocks[index]];
            this.selectedIndex = to;
        },

        setType(index, type) {
            const old = this.blocks[index];
            this.blocks[index] = this.newBlock(type, { text: old.data.text || '' });
        },

        setHeading(index, level) {
            const old = this.blocks[index];
            this.blocks[index] = this.newBlock('heading', { text: old.data.text || '', level });
        },

        focusBlock(index) {
            const row = this.$el.querySelector(`[data-block="${this.blocks[index]?._id}"]`);
            const el  = row?.querySelector('[contenteditable]');
            if (el) { el.focus(); const r = document.createRange(); r.selectNodeContents(el); r.collapse(false); window.getSelection().removeAllRanges(); window.getSelection().addRange(r); }
        },

        focusEnd() {
            if (this.blocks.length) this.focusBlock(this.blocks.length - 1);
        },

        splitOrNewBlock(index, e) {
            const sel = window.getSelection();
            if (!sel?.rangeCount) { this.blocks.splice(index + 1, 0, this.newBlock('paragraph')); this.selectedIndex = index + 1; this.$nextTick(() => this.focusBlock(index + 1)); return; }
            const range = sel.getRangeAt(0);
            const el = e.target;
            const afterRange = document.createRange();
            afterRange.setStart(range.endContainer, range.endOffset);
            afterRange.setEndAfter(el);
            const frag = afterRange.extractContents();
            const afterHtml = frag.textContent || '' ? frag.querySelector('*') ? frag.firstChild?.outerHTML || frag.textContent : frag.textContent : '';
            this.blocks[index].data.text = el.innerHTML;
            this.blocks.splice(index + 1, 0, this.newBlock('paragraph', { text: afterHtml }));
            this.selectedIndex = index + 1;
            this.$nextTick(() => this.focusBlock(index + 1));
        },

        handleBackspace(index, e) {
            const el = e.target;
            if (el.innerText.replace(/\n/g, '') === '' && this.blocks.length > 1) {
                e.preventDefault();
                this.removeBlock(index);
                this.$nextTick(() => this.focusBlock(Math.max(0, index - 1)));
            }
        },

        addListItem(index) {
            this.blocks[index].data.items.push({ text: '' });
            this.$nextTick(() => {
                const row = this.$el.querySelector(`[data-block="${this.blocks[index]._id}"]`);
                const items = row?.querySelectorAll('.gb-list-item');
                items?.[items.length - 1]?.focus();
            });
        },

        removeListItemIfEmpty(blockIndex, itemIndex, e) {
            const items = this.blocks[blockIndex].data.items;
            if (e.target.innerText === '' && items.length > 1) {
                e.preventDefault();
                items.splice(itemIndex, 1);
            }
        },

        /* ── Inline formatting ── */
        checkInlineSelection() {
            const sel = window.getSelection();
            if (!sel || sel.isCollapsed || !sel.toString()) { this.inlineToolbar.visible = false; return; }
            const range = sel.getRangeAt(0);
            const rect  = range.getBoundingClientRect();
            const pr    = this.$el.getBoundingClientRect();
            this.inlineToolbar.x = rect.left - pr.left + rect.width / 2 - 80;
            this.inlineToolbar.y = rect.top - pr.top - 44;
            this.inlineToolbar.visible = true;
        },

        fmt(cmd) { document.execCommand(cmd, false, null); this.syncAfterFmt(); },
        queryCmd(cmd) { try { return document.queryCommandState(cmd); } catch { return false; } },

        syncAfterFmt() {
            const sel = window.getSelection();
            if (!sel?.rangeCount) return;
            const el = sel.getRangeAt(0).commonAncestorContainer.parentElement?.closest('[data-gb-para]');
            if (el) { const idx = [...this.$el.querySelectorAll('[data-gb-para]')].indexOf(el); if (idx > -1 && this.blocks[idx]) this.blocks[idx].data.text = el.innerHTML; }
            this.syncState();
        },

        openLinkPanel() {
            const sel = window.getSelection();
            if (!sel?.rangeCount) return;
            this.linkModal.savedRange = sel.getRangeAt(0).cloneRange();
            const anchor = sel.anchorNode?.parentElement?.closest('a');
            this.linkModal.url     = anchor?.href || '';
            this.linkModal.title   = anchor?.title || '';
            this.linkModal.newTab  = anchor?.target === '_blank';
            this.linkModal.rel     = anchor?.rel ? anchor.rel.split(' ').filter(Boolean) : [];
            this.linkModal.hasLink = !!anchor;
            this.linkModal.open    = true;
            this.inlineToolbar.visible = false;
            this.$nextTick(() => this.$refs.linkUrlInput?.focus());
        },

        applyLink() {
            if (!this.linkModal.url || !this.linkModal.savedRange) { this.linkModal.open = false; return; }
            const sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(this.linkModal.savedRange);
            document.execCommand('createLink', false, this.linkModal.url);

            // Find the created/updated anchor and apply SEO attributes
            const newSel = window.getSelection();
            let anchor = null;
            if (newSel?.rangeCount) {
                const node = newSel.getRangeAt(0).startContainer;
                anchor = node.nodeType === 3 ? node.parentElement?.closest('a') : node.closest?.('a');
            }
            if (!anchor) {
                const candidates = this.$el.querySelectorAll(`a[href="${this.linkModal.url}"]`);
                if (candidates.length) anchor = candidates[candidates.length - 1];
            }
            if (anchor) {
                if (this.linkModal.title) anchor.setAttribute('title', this.linkModal.title);
                else anchor.removeAttribute('title');

                if (this.linkModal.newTab) anchor.setAttribute('target', '_blank');
                else anchor.removeAttribute('target');

                const rels = [...this.linkModal.rel];
                if (this.linkModal.newTab && !rels.includes('noreferrer')) rels.push('noreferrer');
                if (this.linkModal.newTab && !rels.includes('noopener')) rels.push('noopener');
                if (rels.length) anchor.setAttribute('rel', rels.join(' '));
                else anchor.removeAttribute('rel');
            }

            this.linkModal.open = false;
            this.syncAfterFmt();
        },

        removeLink() {
            if (!this.linkModal.savedRange) return;
            const sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(this.linkModal.savedRange);
            document.execCommand('unlink', false, null);
            this.linkModal.open = false;
            this.syncAfterFmt();
        },

        toggleRel(rel, event) {
            if (event.target.checked) {
                if (!this.linkModal.rel.includes(rel)) this.linkModal.rel.push(rel);
            } else {
                this.linkModal.rel = this.linkModal.rel.filter(r => r !== rel);
            }
        },

        /* ── Image helpers ── */
        imgSrc(src) { return src?.startsWith('http') ? src : '/storage/' + src; },

        toEmbedUrl(url) {
            const yt = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/);
            if (yt) return 'https://www.youtube.com/embed/' + yt[1];
            const vi = url.match(/vimeo\.com\/(\d+)/);
            if (vi) return 'https://player.vimeo.com/video/' + vi[1];
            return url;
        },

        /* ── Media modal ── */
        openMediaModal(index) {
            this.mediaModal.targetIndex = index;
            this.mediaModal.open = true;
            this.mediaModal.tab  = 'library';
            this.mediaModal.selected = null;
            this.loadMedia();
        },

        async loadMedia(reset = true) {
            if (reset) { this.mediaModal.page = 1; this.mediaModal.items = []; }
            this.mediaModal.loading = true;
            try {
                const qs  = new URLSearchParams({ page: this.mediaModal.page, search: this.mediaModal.search });
                const res = await fetch(`/admin/media?${qs}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '' },
                    credentials: 'same-origin',
                });
                if (!res.ok) { console.error('Media API error', res.status); return; }
                const json = await res.json();
                this.mediaModal.items   = reset ? json.data : [...this.mediaModal.items, ...json.data];
                this.mediaModal.hasMore = json.current_page < json.last_page;
            } catch(e) { console.error('Media load error', e); }
            finally { this.mediaModal.loading = false; }
        },

        loadMoreMedia() { this.mediaModal.page++; this.loadMedia(false); },

        async uploadToLibrary(e) {
            const files = [...e.target.files];
            if (!files.length) return;
            this.mediaModal.uploading = true;
            for (const file of files) {
                const fd = new FormData();
                fd.append('image', file);
                try {
                    const res = await fetch('/admin/media', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '', 'Accept': 'application/json' },
                        credentials: 'same-origin',
                        body: fd,
                    });
                    if (res.ok) { const data = await res.json(); this.mediaModal.items.unshift(data); }
                } catch(e) { console.error('Upload error', e); }
            }
            this.mediaModal.uploading = false;
            this.mediaModal.tab = 'library';
            e.target.value = '';
        },

        insertSelectedMedia() {
            if (!this.mediaModal.selected) return;
            const { url, path } = this.mediaModal.selected;
            if (this.mediaModal.targetIndex > -1) {
                this.blocks[this.mediaModal.targetIndex].data.src = path || url;
            }
            this.mediaModal.open = false;
            this.syncState();
        },
    };
}
</script>

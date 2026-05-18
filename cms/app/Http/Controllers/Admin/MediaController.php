<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $media = Media::latest()
            ->when($request->search, fn ($q, $s) => $q->where('filename', 'like', "%{$s}%"))
            ->paginate(24);

        return response()->json($media->through(fn ($m) => [
            'id'       => $m->id,
            'path'     => $m->path,
            'url'      => $m->url,
            'filename' => $m->filename,
            'alt_text' => $m->alt_text ?? '',
            'size'     => $m->size,
        ]));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate(['image' => 'required|image|max:8192']);

        $file  = $request->file('image');
        $path  = $file->store('media', 'public');

        $media = Media::create([
            'filename'  => $file->getClientOriginalName(),
            'path'      => $path,
            'disk'      => 'public',
            'mime_type' => $file->getMimeType(),
            'size'      => $file->getSize(),
        ]);

        return response()->json(['path' => $media->path, 'url' => $media->url]);
    }

    public function update(Request $request, Media $media): JsonResponse
    {
        $media->update($request->only('alt_text'));

        return response()->json(['ok' => true]);
    }
}

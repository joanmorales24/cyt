<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaItem;
use App\Services\ImageSanitizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlockImageUploadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|file|max:8192|mimes:jpg,jpeg,png,gif,webp',
        ]);

        $clean = ImageSanitizer::sanitize($request->file('image'));

        $libraryItem = MediaItem::create(['name' => pathinfo($clean->getClientOriginalName(), PATHINFO_FILENAME)]);
        $media = $libraryItem->addMedia($clean->getRealPath())
            ->usingName($libraryItem->name)
            ->toMediaCollection('default');

        return response()->json([
            'url'  => $media->getUrl(),
            'path' => $media->getUrl(),
        ]);
    }
}

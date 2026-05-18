<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ImageSanitizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlockImageUploadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|file|max:8192|mimes:jpg,jpeg,png,gif,webp',
        ]);

        $clean = ImageSanitizer::sanitize($request->file('image'));
        $path  = $clean->store('blocks', 'public');

        return response()->json([
            'url'  => Storage::disk('public')->url($path),
            'path' => $path,
        ]);
    }
}

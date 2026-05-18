<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlockImageUploadController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate(['image' => 'required|image|max:8192']);

        $path = $request->file('image')->store('blocks', 'public');

        return response()->json([
            'url'  => Storage::disk('public')->url($path),
            'path' => $path,
        ]);
    }
}

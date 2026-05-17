<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['categories', 'tags'])
            ->where('status', 'published')
            ->orderByDesc('published_at');

        if ($request->filled('categoria')) {
            $query->whereHas('categories', fn ($q) => $q->where('slug', $request->categoria));
        }

        if ($request->filled('etiqueta')) {
            $query->whereHas('tags', fn ($q) => $q->where('slug', $request->etiqueta));
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(fn ($q) => $q->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%"));
        }

        $posts = $query->paginate(12)->withQueryString();
        $categories = Category::withCount(['posts' => fn ($q) => $q->where('status', 'published')])->get();
        $tags = Tag::withCount(['posts' => fn ($q) => $q->where('status', 'published')])->get();

        return view('blog.index', compact('posts', 'categories', 'tags'));
    }

    public function show(string $slug)
    {
        $post = Post::with(['categories', 'tags'])
            ->where('status', 'published')
            ->where('slug', $slug)
            ->firstOr(function () use ($slug) {
                // Try old_slug redirect
                $redirect = Post::where('old_slug', $slug)->where('status', 'published')->first();
                if ($redirect) {
                    return redirect()->route('blog.show', $redirect->slug, 301);
                }
                abort(404);
            });

        if ($post instanceof \Illuminate\Http\RedirectResponse) {
            return $post;
        }

        $related = Post::with('categories')
            ->where('status', 'published')
            ->where('id', '!=', $post->id)
            ->whereHas('categories', fn ($q) => $q->whereIn('categories.id', $post->categories->pluck('id')))
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('blog.show', compact('post', 'related'));
    }

    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $posts = Post::with(['categories', 'tags'])
            ->where('status', 'published')
            ->whereHas('categories', fn ($q) => $q->where('slug', $slug))
            ->orderByDesc('published_at')
            ->paginate(12);
        $categories = Category::withCount(['posts' => fn ($q) => $q->where('status', 'published')])->get();

        return view('blog.category', compact('category', 'posts', 'categories'));
    }

    public function tag(string $slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();
        $posts = Post::with(['categories', 'tags'])
            ->where('status', 'published')
            ->whereHas('tags', fn ($q) => $q->where('slug', $slug))
            ->orderByDesc('published_at')
            ->paginate(12);

        $tags = Tag::withCount(['posts' => fn ($q) => $q->where('status', 'published')])->get();

        return view('blog.tag', compact('tag', 'posts', 'tags'));
    }
}

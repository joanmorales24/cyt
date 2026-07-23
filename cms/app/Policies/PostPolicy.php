<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, Post $post): bool
    {
        return $user->is_admin;
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Post $post): bool
    {
        return $user->is_admin && ($user->is_super_admin || $post->user_id === $user->id);
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->is_admin && ($user->is_super_admin || $post->user_id === $user->id);
    }

    public function restore(User $user, Post $post): bool
    {
        return $user->is_admin && $user->is_super_admin;
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return $user->is_admin && $user->is_super_admin;
    }
}

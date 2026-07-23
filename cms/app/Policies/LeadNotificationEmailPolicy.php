<?php

namespace App\Policies;

use App\Models\LeadNotificationEmail;
use App\Models\User;

class LeadNotificationEmailPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->is_admin;
    }

    public function view(User $user, LeadNotificationEmail $email): bool
    {
        return $user->is_admin;
    }

    public function create(User $user): bool
    {
        return $user->is_admin && $user->is_super_admin;
    }

    public function update(User $user, LeadNotificationEmail $email): bool
    {
        return $user->is_admin && $user->is_super_admin;
    }

    public function delete(User $user, LeadNotificationEmail $email): bool
    {
        return $user->is_admin && $user->is_super_admin;
    }

    public function restore(User $user, LeadNotificationEmail $email): bool
    {
        return $user->is_admin && $user->is_super_admin;
    }

    public function forceDelete(User $user, LeadNotificationEmail $email): bool
    {
        return $user->is_admin && $user->is_super_admin;
    }
}

<?php

namespace App\Providers;

use App\Models\LeadNotificationEmail;
use App\Models\Post;
use App\Policies\LeadNotificationEmailPolicy;
use App\Policies\PostPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Post::class => PostPolicy::class,
        LeadNotificationEmail::class => LeadNotificationEmailPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}

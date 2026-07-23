<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class LeadNotificationEmail extends Model
{
    use HasUuids;
    protected $fillable = [
        'email',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}

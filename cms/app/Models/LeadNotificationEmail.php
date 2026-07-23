<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadNotificationEmail extends Model
{
    protected $fillable = [
        'email',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasUuids;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'message',
        'source',
    ];
}

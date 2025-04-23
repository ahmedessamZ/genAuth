<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationCode extends model
{
    protected $fillable = [
        'user_id',
        'code',
        'created_at',
        'updated_at',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Admin extends Authenticatable implements HasMedia
{
    use HasUuids, HasFactory, InteractsWithMedia, Notifiable;
    protected $fillable = [
        "name", 'email', 'password', 'status', 'remember_token', 'created_at', 'updated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

}

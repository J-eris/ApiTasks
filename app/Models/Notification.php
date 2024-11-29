<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'is_read',
        'type',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_user')
            ->withPivot('id', 'is_read')
            ->withTimestamps();
    }
}

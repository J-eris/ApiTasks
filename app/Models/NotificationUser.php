<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationUser extends Model
{
    use HasFactory;

    protected $table = 'notification_user';

    protected $fillable = [
        'notification_id',
        'user_id',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function notificationUser()
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }
}

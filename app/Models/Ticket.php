<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'status',
        'user_id',
        'auction_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

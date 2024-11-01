<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'method_name',
        'details',
        'proof_image',
        'optional_text',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

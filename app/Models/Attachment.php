<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id',
        'file_path',
        'file_type',
    ];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }
}

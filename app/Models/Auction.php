<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'starting_price',
        'min_price',
        'max_price',
        'reference_price',
        'category_id',
        'created_by',
        'status',
        'expiration_date',
        'final_price',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id',
        'username',
        'final_price',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $with = [
        'auction'
    ];

    public function auction()
    {
        return $this->belongsTo('App\Models\Auction', 'auction_id', 'id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'seller',
        'item_id',
        'bid_id',
        'buyout',
        'status',
        'start_datetime',
        'end_datetime',
        'user_id',
        'is_active',
    ];

    protected $hidden = [
        //
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'buyout' => 'double',
    ];

    protected $appends = [
        //
    ];

    protected $with = [
        'item',
        'bid',
        'user'
    ];

    public function item()
    {
        return $this->belongsTo('App\Models\Item', 'item_id', 'id');
    }

    public function bid()
    {
        return $this->belongsTo('App\Models\Bid', 'bid_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}

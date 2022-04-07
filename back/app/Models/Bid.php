<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use Illuminate\Support\Facades\DB;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'username',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    /**
     * Deactivate a certain Bid by ID, usually the last and only bid of the Auction it belongs to.
     */
    public static function deactivateBid($bid_id)
    {
        DB::table('bids')
            ->where('id', $bid_id)
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'updated_at' => Carbon::now()
            ]);
    }

}

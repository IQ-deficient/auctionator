<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'item_id',
    ];

    protected $casts = [
//        'image' => 'blob',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}

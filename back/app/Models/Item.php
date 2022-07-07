<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'condition',
        'warehouse_id',
    ];

    protected $hidden = [
        //
    ];

    protected $casts = [];

    protected $appends = [];

    protected $with = [
        'warehouse',
        'category'
    ];

    public function warehouse()
    {
        return $this->belongsTo('App\Models\Warehouse', 'warehouse_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category', 'name');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}

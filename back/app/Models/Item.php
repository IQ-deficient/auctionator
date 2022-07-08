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
//        'created_at',
//        'updated_at',
    ];

    protected $casts = [];

    protected $appends = [];

    protected $with = [
//        'category_all',
        'warehouse',
        'category'
    ];

    public function category_all()
    {
        return $this->belongsTo('App\Models\Category', 'category', 'name');
    }

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

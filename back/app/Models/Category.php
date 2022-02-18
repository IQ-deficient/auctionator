<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'master_category_id',
        'is_active',
    ];

    protected $hidden = [
//        'created_at',
//        'updated_at',
    ];

    protected $casts = [];

    protected $appends = [];

    protected $with = [
//        'master_category',
    ];

    public function master_category()
    {
        return $this->belongsTo('App\Models\Category', 'master_category_id', 'id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}

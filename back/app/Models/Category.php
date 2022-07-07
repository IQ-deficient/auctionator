<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'master_category_id',
        'is_active',
    ];

    protected $hidden = [
        //
    ];

    protected $casts = [];

    protected $appends = [];

    protected $with = [
        //
    ];

    /**
     * For selected category get all conditions that exist for parent (master) category of that subcategory.
     * @return Collection
     */
    public static function getConditionsByCategory($category)
    {
        return DB::table('category_conditions')
            ->where(
                'category',
                DB::table('categories')
                    ->where(
                        'id',
                        DB::table('categories')
                            ->where('is_active', true)
                            ->where('name', $category)
                            ->value('master_category_id')
                    )
                    ->value('name')
            )
            ->pluck('condition');
    }

    public function master_category()
    {
        return $this->belongsTo('App\Models\Category', 'master_category_id', 'id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

}

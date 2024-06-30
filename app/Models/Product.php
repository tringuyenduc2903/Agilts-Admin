<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    use CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'enabled',
        'visibility',
        'status',
        'type',
        'specifications',
    ];

    /**
     * @return HasMany
     */
    public function options(): HasMany
    {
        return $this->hasMany(ProductOption::class);
    }
}

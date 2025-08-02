<?php

namespace Modules\Category\Models;

use Modules\Medicine\Models\Medicine;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Category extends Model implements HasMedia
{
    use HasFactory,InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name','parent_id'];

    public function medicines()
    {
        return $this->hasMany(Medicine::class);
    }

    /**
     * Get the parent category.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
}

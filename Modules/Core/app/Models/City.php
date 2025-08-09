<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Core\Database\Factories\CityFactory;

class City extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['name', 'parent_id'];

    public function children()
    {
        return $this->hasMany(City::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(City::class, 'parent_id');
    }

    public function users()
    {
        return $this->belongsToMany(\Modules\User\Models\User::class, 'city_user');
    }
}

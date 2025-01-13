<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    //
    protected $fillable = ['title', 'description', 'slug'];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($category) {
            if ($category->isDirty('title')) {
                $category->slug =  Str::slug($category->title);
            }
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}

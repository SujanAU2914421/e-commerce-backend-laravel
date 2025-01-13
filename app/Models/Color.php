<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'name',
        'images',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
        ];
    }

    public function getImagesAttribute($value): array
    {
        $value = json_decode($value, true);
        if (request()->is('api/*')) {
            $paths = [];
            foreach ($value as $path) {
                $paths[] = asset('storage/' . $path);
            }
            return $paths;
        }

        return $value;
    }

    /**
     * Get the product that owns the color.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

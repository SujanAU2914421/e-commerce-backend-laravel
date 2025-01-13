<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ColorImage extends Model
{
    use HasFactory;

    protected $fillable = ['color_id', 'image_path'];

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }
}

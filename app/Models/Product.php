<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'section_id',
    ];

    protected $casts = [
        'price' => 'int',
        // 'images' => 'array',
    ];

    /**
     * Get the section that owns the Product
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * Get all of the images for the Product
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    /**
     * Get the image associated with the Product
     */
    public function image(): HasOne
    {
        return $this->hasOne(Image::class);
    }

    public function scopeSearch($query, string $search = '')
    {
        $query->where('name', 'LIKE', "%{$search}%");
    }
}

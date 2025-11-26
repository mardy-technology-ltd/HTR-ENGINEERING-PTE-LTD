<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutContent extends Model
{
    protected $fillable = [
        'section_key',
        'title',
        'subtitle',
        'content',
        'content_secondary',
        'image',
        'items',
        'is_active',
        'order'
    ];

    protected $casts = [
        'items' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Scope for active content
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered content
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Get content by section key
     */
    public static function getBySection(string $sectionKey)
    {
        return static::where('section_key', $sectionKey)
            ->active()
            ->first();
    }
}

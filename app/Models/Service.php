<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'details',
        'icon',
        'image',
        'features',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'features' => 'array',
    ];

    /**
     * Boot the model and attach event listeners.
     */
    protected static function boot()
    {
        parent::boot();

        // Automatically generate slug when creating
        static::creating(function ($service) {
            if (empty($service->slug)) {
                $service->slug = static::generateUniqueSlug($service->title);
            }
        });

        // Automatically update slug when title changes
        static::updating(function ($service) {
            if ($service->isDirty('title')) {
                $service->slug = static::generateUniqueSlug($service->title, $service->id);
            }
        });
    }

    /**
     * Generate a unique slug from title.
     *
     * @param string $title
     * @param int|null $ignoreId
     * @return string
     */
    protected static function generateUniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        // Check if slug exists and make it unique
        while (static::where('slug', $slug)->when($ignoreId, function ($query) use ($ignoreId) {
            return $query->where('id', '!=', $ignoreId);
        })->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        // Use slug for frontend routes, id for admin routes
        if (request()->is('admin/*')) {
            return 'id';
        }
        return 'slug';
    }

    /**
     * Scope a query to only include active services.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order services by order field.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}

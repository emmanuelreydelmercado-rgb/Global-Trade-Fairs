<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Form extends Model
{
    use HasFactory;

    protected $table = 'forms';

    protected $guarded = [];

    public $timestamps = false;

    /**
     * Boot the model and add event listeners
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug when creating
        static::creating(function ($form) {
            if (empty($form->slug)) {
                $form->slug = static::generateUniqueSlug($form->ExponName);
            }
        });

        // Auto-update slug when ExponName changes
        static::updating(function ($form) {
            if ($form->isDirty('ExponName') && empty($form->slug)) {
                $form->slug = static::generateUniqueSlug($form->ExponName);
            }
        });
    }

    /**
     * Generate a unique slug
     */
    protected static function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Get the route key for the model (use slug instead of id)
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}

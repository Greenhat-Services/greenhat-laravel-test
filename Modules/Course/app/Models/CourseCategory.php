<?php

namespace Modules\Course\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Base\Traits\CamelCasing;
use Modules\Base\Traits\HasSlugAttribute;

class CourseCategory extends Model
{
    use HasFactory;
    use CamelCasing;
    use HasSlugAttribute;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all courses associated with this category.
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_course_category');
    }

    /**
     * Scope to filter only active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

<?php

namespace Modules\Course\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Base\Traits\CamelCasing;
use Modules\Base\Traits\HasSlugAttribute;
use Modules\Employee\Models\Employee;
use Spatie\Sluggable\SlugOptions;

class Course extends Model
{
    use HasFactory;
    use CamelCasing;
    use HasSlugAttribute;

    protected $guarded = [];

    protected $casts = [
        'duration_hours' => 'integer',
        'max_enrollments' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Override slug generation to use 'title' instead of 'name'.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    /**
     * Get all categories associated with this course.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(CourseCategory::class, 'course_course_category');
    }

    /**
     * Get all enrollments for this course.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    /**
     * Get all enrolled employees for this course.
     */
    public function enrolledEmployees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'course_enrollments')
            ->withPivot(['status', 'enrolled_at', 'completed_at', 'progress_percentage', 'notes'])
            ->withTimestamps();
    }

    /**
     * Scope to filter only published courses.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope to filter courses available for enrollment.
     */
    public function scopeAvailableForEnrollment($query)
    {
        return $query->published()
            ->where(function ($query) {
                $query->whereNull('max_enrollments')
                    ->orWhereRaw('(SELECT COUNT(*) FROM course_enrollments WHERE course_id = courses.id AND status IN ("enrolled", "in_progress")) < max_enrollments');
            });
    }

    /**
     * Get available slots for enrollment.
     */
    public function getAvailableSlotsAttribute(): ?int
    {
        if ($this->max_enrollments === null) {
            return null; // Unlimited
        }

        $currentEnrollments = $this->enrollments()
            ->whereIn('status', ['enrolled', 'in_progress'])
            ->count();

        return max(0, $this->max_enrollments - $currentEnrollments);
    }

    /**
     * Check if enrollment is currently open for this course.
     */
    public function isEnrollmentOpen(): bool
    {
        // Check if course is published
        if ($this->status !== 'published') {
            return false;
        }

        // Check if there are available slots
        $availableSlots = $this->available_slots;
        if ($availableSlots !== null && $availableSlots <= 0) {
            return false;
        }

        // Check date range if specified
        $now = now();
        if ($this->start_date && $now->gt($this->start_date)) {
            return false;
        }
        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        return true;
    }
}

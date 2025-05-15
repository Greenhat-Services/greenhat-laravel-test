<?php

namespace Modules\Course\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Base\Traits\CamelCasing;
use Modules\Employee\Models\Employee;

// use Modules\Course\Database\Factories\CourseFactory;

class Course extends Model
{
    use HasFactory, SoftDeletes, CamelCasing;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    // protected static function newFactory(): CourseFactory
    // {
    //     // return CourseFactory::new();
    // }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'course_category');
    }

    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'course_employee');
    }

    public static function getStatusOptions(): array
    {
        return [
            'draft' => 'draft',
            'published' => 'published',
        ];
    }

    // filter by category
    public function scopeFilterByStatus($query, $status): Builder
    {
        return $query->where('status', $status);
    }

    // general search
    public function scopeSearch($query, $search): Builder
    {
        return $query->where('title', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%");
    }
}

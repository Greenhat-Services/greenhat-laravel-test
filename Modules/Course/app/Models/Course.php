<?php

namespace Modules\Course\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Base\Traits\CamelCasing;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Employee\Models\Employee;
use Modules\Course\Database\Factories\CourseFactory;
// use Modules\Course\Database\Factories\CourseFactory;

class Course extends Model
{
    use CamelCasing, HasFactory;
    protected $guarded = [];

    /**
     * The categories that belong to the course.
     */

     public function categories(): BelongsToMany
    {
        return $this->belongsToMany(CourseCategory::class, 'category_course', 'course_id', 'category_id');
    }

    /**
     * The employees that are enrolled in the course.
     */
    public function employees(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'course_employee', 'course_id', 'employee_id')->withTimestamps();
    }

    protected static function newFactory(): CourseFactory // this is the factory for the model
    {
        return CourseFactory::new();
    }
}

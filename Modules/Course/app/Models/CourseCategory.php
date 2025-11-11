<?php

namespace Modules\Course\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Base\Traits\CamelCasing;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Course\Database\Factories\CourseCategoryFactory;

// use Modules\Course\Database\Factories\CourseCategoryFactory;

class CourseCategory extends Model
{
    use CamelCasing, HasFactory;

    protected $guarded = [];


    /**
     * The courses that belong to the category.
     */

     public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'category_course', 'category_id', 'course_id');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): CourseCategoryFactory // this is the factory for the model
    {
        return CourseCategoryFactory::new();
    }
}

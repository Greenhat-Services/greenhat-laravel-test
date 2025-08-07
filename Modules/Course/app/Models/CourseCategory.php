<?php

namespace Modules\Course\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Base\Traits\CamelCasing;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

// use Modules\Course\Database\Factories\CourseCategoryFactory;

class CourseCategory extends Model
{
    use CamelCasing;

    protected $guarded = [];


    /**
     * The courses that belong to the category.
     */

     public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'category_course');
    }
}

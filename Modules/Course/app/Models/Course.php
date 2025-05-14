<?php

namespace Modules\Course\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Employee\Models\Employee;

// use Modules\Course\Database\Factories\CourseFactory;

class Course extends Model
{
    use HasFactory, SoftDeletes;

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
}

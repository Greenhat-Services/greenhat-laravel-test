<?php

namespace Modules\Course\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Course\Models\CourseCategory;
use Modules\Course\Models\Course;


class CourseDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = CourseCategory::factory()->count(5)->create(); // creates 5 categories
        Course::factory()->count(8)->create()->each(function ($course) use ($categories) {
            // For each course, attach 1 to 3 random categories from the ones we just created
            $course->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}

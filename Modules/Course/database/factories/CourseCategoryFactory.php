<?php

namespace Modules\Course\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Course\Models\CourseCategory;

class CourseCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = CourseCategory::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            // More realistic course categories
            'name' => $this->faker->unique()->randomElement([
                'Technical Skills',
                'Leadership & Management',
                'Communication',
                'Project Management',
                'Software Development',
                'Data Science',
                'Cybersecurity',
                'Human Resources',
                'Marketing',
                'Sales Techniques',
            ]),
            'description' => $this->faker->sentence(),
        ];
    }
}


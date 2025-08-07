<?php

namespace Modules\Course\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Course\Models\Course;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            // More realistic course titles
            'title' => $this->faker->unique()->randomElement([
                'Advanced Leadership Strategies',
                'Introduction to Python Programming',
                'Effective Communication in the Workplace',
                'Agile Project Management Fundamentals',
                'Mastering Data Analysis with Excel',
                'Cybersecurity Essentials for Employees',
                'Digital Marketing Masterclass',
                'Public Speaking and Presentation Skills',
            ]),
            'description' => $this->faker->paragraph(5),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
        ];
    }
}


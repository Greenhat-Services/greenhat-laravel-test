<?php

namespace Modules\Course\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Course\Models\Course;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Soft skills for Developers',
                'description' => 'Learn essential soft skills for developers.',
                'status' => 'published',
                'categoryIds' => [1, 3],
            ],
            [
                'title' => 'Advanced PHP Techniques',
                'description' => 'Master advanced PHP programming concepts.',
                'status' => 'published',
                'categoryIds' => [1],
            ],
            [
                'title' => 'Leader in Tech',
                'description' => 'Become a leader in the tech industry.',
                'status' => 'draft',
                'categoryIds' => [2],
            ],
            [
                'title' => 'Database Design Fundamentals',
                'description' => 'Understand the principles of database design.',
                'status' => 'draft',
                'categoryIds' => [3],
            ],
            [
                'title' => 'Building RESTful APIs',
                'description' => 'Learn how to build RESTful APIs with Laravel.',
                'status' => 'published',
                'categoryIds' => [1],
            ]
        ];

        foreach ($courses as $data) {
            $course = Course::create([
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => $data['status'],
            ]);

            $course->categories()->sync($data['categoryIds']);
        }
    }
}

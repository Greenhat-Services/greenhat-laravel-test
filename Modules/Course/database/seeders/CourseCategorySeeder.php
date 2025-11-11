<?php

namespace Modules\Course\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Course\Models\CourseCategory;

class CourseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Web Development',
                'description' => 'Courses focused on building modern web applications using various frameworks and technologies.',
                'is_active' => true,
            ],
            [
                'name' => 'Mobile Development',
                'description' => 'Learn to build native and cross-platform mobile applications for iOS and Android.',
                'is_active' => true,
            ],
            [
                'name' => 'DevOps',
                'description' => 'Master the art of continuous integration, deployment, and infrastructure management.',
                'is_active' => true,
            ],
            [
                'name' => 'Security',
                'description' => 'Cybersecurity courses covering application security, network security, and ethical hacking.',
                'is_active' => true,
            ],
            [
                'name' => 'Leadership',
                'description' => 'Develop essential leadership and management skills for technical teams.',
                'is_active' => true,
            ],
            [
                'name' => 'Team Management',
                'description' => 'Learn effective strategies for managing and motivating development teams.',
                'is_active' => true,
            ],
            [
                'name' => 'Communication',
                'description' => 'Improve your professional communication and presentation skills.',
                'is_active' => true,
            ],
            [
                'name' => 'UI/UX Design',
                'description' => 'Create beautiful and user-friendly interfaces with modern design principles.',
                'is_active' => true,
            ],
            [
                'name' => 'Project Management',
                'description' => 'Agile, Scrum, and traditional project management methodologies.',
                'is_active' => true,
            ],
            [
                'name' => 'Data Science',
                'description' => 'Analytics, machine learning, and data visualization courses.',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            CourseCategory::create($category);
        }
    }
}

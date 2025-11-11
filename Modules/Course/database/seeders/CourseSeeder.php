<?php

namespace Modules\Course\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Course\Models\Course;
use Modules\Course\Models\CourseCategory;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Advanced Laravel Development',
                'description' => 'Master advanced Laravel concepts including queues, events, broadcasting, and package development.',
                'instructor' => 'John Smith',
                'duration_hours' => 20,
                'difficulty_level' => 'advanced',
                'status' => 'published',
                'max_enrollments' => 30,
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(37),
                'categories' => ['Web Development'],
            ],
            [
                'title' => 'Leadership Fundamentals',
                'description' => 'Essential leadership skills for emerging tech leaders and team leads.',
                'instructor' => 'Sarah Johnson',
                'duration_hours' => 15,
                'difficulty_level' => 'beginner',
                'status' => 'published',
                'max_enrollments' => 25,
                'start_date' => now()->addDays(5),
                'end_date' => now()->addDays(35),
                'categories' => ['Leadership', 'Team Management'],
            ],
            [
                'title' => 'React and Next.js Masterclass',
                'description' => 'Build production-ready applications with React 18 and Next.js 14.',
                'instructor' => 'Michael Chen',
                'duration_hours' => 30,
                'difficulty_level' => 'intermediate',
                'status' => 'published',
                'max_enrollments' => 40,
                'start_date' => now()->addDays(10),
                'end_date' => now()->addDays(50),
                'categories' => ['Web Development'],
            ],
            [
                'title' => 'DevOps with Docker and Kubernetes',
                'description' => 'Learn containerization and orchestration for modern applications.',
                'instructor' => 'David Rodriguez',
                'duration_hours' => 25,
                'difficulty_level' => 'intermediate',
                'status' => 'published',
                'max_enrollments' => 20,
                'start_date' => now()->addDays(14),
                'end_date' => now()->addDays(54),
                'categories' => ['DevOps'],
            ],
            [
                'title' => 'iOS Development with SwiftUI',
                'description' => 'Build native iOS applications using SwiftUI and modern Swift patterns.',
                'instructor' => 'Emily Watson',
                'duration_hours' => 35,
                'difficulty_level' => 'intermediate',
                'status' => 'published',
                'max_enrollments' => 15,
                'start_date' => now()->addDays(21),
                'end_date' => now()->addDays(71),
                'categories' => ['Mobile Development'],
            ],
            [
                'title' => 'Cybersecurity Essentials',
                'description' => 'Introduction to application security, common vulnerabilities, and secure coding practices.',
                'instructor' => 'Robert Brown',
                'duration_hours' => 18,
                'difficulty_level' => 'beginner',
                'status' => 'published',
                'max_enrollments' => 35,
                'start_date' => now()->addDays(3),
                'end_date' => now()->addDays(33),
                'categories' => ['Security'],
            ],
            [
                'title' => 'UI/UX Design for Developers',
                'description' => 'Learn design principles, user research, and prototyping for better product development.',
                'instructor' => 'Lisa Anderson',
                'duration_hours' => 20,
                'difficulty_level' => 'beginner',
                'status' => 'published',
                'max_enrollments' => 30,
                'start_date' => now()->addDays(8),
                'end_date' => now()->addDays(38),
                'categories' => ['UI/UX Design'],
            ],
            [
                'title' => 'Agile Project Management',
                'description' => 'Scrum, Kanban, and agile methodologies for software development teams.',
                'instructor' => 'James Wilson',
                'duration_hours' => 12,
                'difficulty_level' => 'beginner',
                'status' => 'published',
                'max_enrollments' => 50,
                'start_date' => now()->addDays(4),
                'end_date' => now()->addDays(24),
                'categories' => ['Project Management'],
            ],
            [
                'title' => 'Python for Data Science',
                'description' => 'Data analysis, visualization, and machine learning with Python libraries.',
                'instructor' => 'Dr. Maria Garcia',
                'duration_hours' => 40,
                'difficulty_level' => 'intermediate',
                'status' => 'published',
                'max_enrollments' => 25,
                'start_date' => now()->addDays(15),
                'end_date' => now()->addDays(75),
                'categories' => ['Data Science'],
            ],
            [
                'title' => 'Effective Communication for Engineers',
                'description' => 'Improve your technical communication, presentation, and stakeholder management skills.',
                'instructor' => 'Patricia Miller',
                'duration_hours' => 10,
                'difficulty_level' => 'beginner',
                'status' => 'published',
                'max_enrollments' => 40,
                'start_date' => now()->addDays(6),
                'end_date' => now()->addDays(26),
                'categories' => ['Communication'],
            ],
            [
                'title' => 'Vue.js 3 Composition API',
                'description' => 'Modern Vue.js development with Composition API, Pinia, and TypeScript.',
                'instructor' => 'Alex Turner',
                'duration_hours' => 22,
                'difficulty_level' => 'intermediate',
                'status' => 'draft',
                'max_enrollments' => 30,
                'start_date' => now()->addDays(30),
                'end_date' => now()->addDays(60),
                'categories' => ['Web Development'],
            ],
            [
                'title' => 'Android Development with Kotlin',
                'description' => 'Build professional Android apps using Kotlin and Jetpack Compose.',
                'instructor' => 'Kevin Lee',
                'duration_hours' => 32,
                'difficulty_level' => 'intermediate',
                'status' => 'published',
                'max_enrollments' => 20,
                'start_date' => now()->addDays(12),
                'end_date' => now()->addDays(52),
                'categories' => ['Mobile Development'],
            ],
        ];

        foreach ($courses as $courseData) {
            $categoryNames = $courseData['categories'];
            unset($courseData['categories']);

            $course = Course::create($courseData);

            // Attach categories
            $categoryIds = CourseCategory::whereIn('name', $categoryNames)->pluck('id')->toArray();
            $course->categories()->sync($categoryIds);
        }
    }
}

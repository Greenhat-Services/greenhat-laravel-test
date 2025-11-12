<?php

namespace Modules\Course\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Course\Models\Course;
use Modules\User\Models\User;

class CoursePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        // All authenticated users can view courses
        return true;
    }

    public function view(User $user, Course $course): bool
    {
        // All authenticated users can view a course
        return true;
    }

    public function create(User $user): bool
    {
        // Only admins can create courses
        return $user->isA('admin');
    }

    public function update(User $user, Course $course): bool
    {
        // Only admins can update courses
        return $user->isA('admin');
    }

    public function delete(User $user, Course $course): bool
    {
        // Only admins can delete courses
        return $user->isA('admin');
    }
}

<?php

namespace Modules\Course\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Course\Models\CourseCategory;
use Modules\User\Models\User;

class CourseCategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        // All authenticated users can view categories
        return true;
    }

    public function view(User $user, CourseCategory $category): bool
    {
        // All authenticated users can view a category
        return true;
    }

    public function create(User $user): bool
    {
        // Only admins can create categories
        return $user->isA('admin');
    }

    public function update(User $user, CourseCategory $category): bool
    {
        // Only admins can update categories
        return $user->isA('admin');
    }

    public function delete(User $user, CourseCategory $category): bool
    {
        // Only admins can delete categories
        return $user->isA('admin');
    }
}

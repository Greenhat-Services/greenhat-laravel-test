<?php

namespace Modules\Course\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Course\Models\CourseEnrollment;
use Modules\User\Models\User;

class CourseEnrollmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        // All authenticated users can view enrollments
        return true;
    }

    public function view(User $user, CourseEnrollment $enrollment): bool
    {
        // Employee can view own enrollments, admins can view all
        return $user->isA('admin') || (
                $user->employee && $user->employee->id === $enrollment->employee_id
            );
    }

    public function create(User $user): bool
    {
        // Employees and admins can create enrollments
        return $user->isA('admin') || $user->isA('employee');
    }

    public function delete(User $user, CourseEnrollment $enrollment): bool
    {
        // Employee can unenroll themselves, admins can unenroll anyone
        return $user->isA('admin') || (
                $user->employee && $user->employee->id === $enrollment->employee_id
            );
    }
}

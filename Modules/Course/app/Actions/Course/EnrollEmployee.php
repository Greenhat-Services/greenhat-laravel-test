<?php

namespace Modules\Course\Actions\Course;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Data\Course\EnrollEmployeeData;
use Modules\Course\Exceptions\CourseEnrollmentException;
use Modules\Course\Models\Course;
use Modules\Course\Models\CourseEnrollment;
use Modules\Course\Transformers\CourseEnrollmentTransformer;
use Spatie\Fractal\Fractal;

class EnrollEmployee
{
    use AsAction;

    public function handle(EnrollEmployeeData $data): CourseEnrollment
    {
        // Convert Optional to actual value or throw error
        $employeeId = $data->employeeId instanceof \Spatie\LaravelData\Optional
            ? null
            : $data->employeeId;

        if ($employeeId === null) {
            throw new CourseEnrollmentException(
                'Employee ID is required for enrollment',
                422
            );
        }

        $course = Course::findOrFail($data->courseId);

        // Check if course is published
        if ($course->status !== 'published') {
            throw new CourseEnrollmentException(
                'Cannot enroll in a course that is not published',
                422
            );
        }

        // Check if course has available slots
        $availableSlots = $course->available_slots;
        if ($availableSlots !== null && $availableSlots <= 0) {
            throw new CourseEnrollmentException(
                'Course is at maximum capacity',
                422
            );
        }

        // Check if enrollment is within date range
        if (!$course->isEnrollmentOpen()) {
            throw new CourseEnrollmentException(
                'Enrollment is not currently open for this course',
                422
            );
        }

        // Check for duplicate enrollment
        $existingEnrollment = CourseEnrollment::where('course_id', $data->courseId)
            ->where('employee_id', $employeeId)
            ->whereIn('status', ['enrolled', 'in_progress'])
            ->first();

        if ($existingEnrollment) {
            throw new CourseEnrollmentException(
                'Employee is already enrolled in this course',
                422
            );
        }

        // Handle Optional notes
        $notes = $data->notes instanceof \Spatie\LaravelData\Optional
            ? null
            : $data->notes;

        // Create enrollment
        $enrollment = CourseEnrollment::create([
            'course_id' => $data->courseId,
            'employee_id' => $employeeId,
            'notes' => $notes,
            'status' => 'enrolled',
            'enrolled_at' => now(),
            'progress_percentage' => 0,
        ]);

        $enrollment->load(['course', 'employee']);

        return $enrollment;
    }

    public function authorize(ActionRequest $request): bool
    {
        $user = $request->user();

        // Allow admins to enroll any employee
        if ($user->isAn('admin')) {
            return true;
        }

        // Employees can only enroll themselves
        if ($user->isAn('employee')) {
            $requestedEmployeeId = $request->input('employee_id') ?? $request->input('employeeId');

            // If employeeId is provided, ensure they're enrolling themselves
            if ($requestedEmployeeId) {
                return $user->employee && $user->employee->id == $requestedEmployeeId;
            }

            // If no employeeId provided, it will be auto-populated with their own ID
            return $user->employee !== null;
        }

        return false;
    }

    public function asController(ActionRequest $request): CourseEnrollment
    {
        $data = $request->all();

        $employee = $request->user()->employee;

        if (!$employee) {
            throw new CourseEnrollmentException(
                'User does not have an associated employee record',
                422
            );
        }

        // Set both variants to ensure it's picked up by validation
        $data['employeeId'] = $employee->id;
        $data['employee_id'] = $employee->id;

        $enrollData = EnrollEmployeeData::validateAndCreate($data);

        return $this->handle($enrollData);
    }

    public function jsonResponse(CourseEnrollment $enrollment): Fractal
    {
        return fractal($enrollment, CourseEnrollmentTransformer::class)
            ->parseIncludes('course,employee')
            ->addMeta(['message' => 'Successfully enrolled in course']);
    }
}

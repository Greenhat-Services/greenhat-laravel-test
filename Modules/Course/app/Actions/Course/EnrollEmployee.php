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
            ->where('employee_id', $data->employeeId)
            ->whereIn('status', ['enrolled', 'in_progress'])
            ->first();

        if ($existingEnrollment) {
            throw new CourseEnrollmentException(
                'Employee is already enrolled in this course',
                422
            );
        }

        // Create enrollment
        $enrollment = CourseEnrollment::create([
            'course_id' => $data->courseId,
            'employee_id' => $data->employeeId,
            'notes' => $data->notes ?? null,
            'status' => 'enrolled',
            'enrolled_at' => now(),
            'progress_percentage' => 0,
        ]);

        $enrollment->load(['course', 'employee']);

        return $enrollment;
    }

    public function authorize(ActionRequest $request): bool
    {
        // Public access for testing
        return true;
    }

    public function asController(ActionRequest $request): CourseEnrollment
    {
        $data = EnrollEmployeeData::validateAndCreate($request->all());

        return $this->handle($data);
    }

    public function jsonResponse(CourseEnrollment $enrollment): Fractal
    {
        return fractal($enrollment, CourseEnrollmentTransformer::class)
            ->parseIncludes('course,employee')
            ->addMeta(['message' => 'Successfully enrolled in course']);
    }
}

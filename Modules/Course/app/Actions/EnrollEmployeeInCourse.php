<?php

namespace Modules\Course\Actions;

use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Models\Course;
use Modules\Employee\Models\Employee;
use Lorisleiva\Actions\ActionRequest;
use Spatie\Fractal\Fractal;
use Modules\Course\Transformers\CourseEnrollmentTransformer;
use Illuminate\Http\JsonResponse;
use Modules\Course\Emails\SendEnrollmentNotification;




class EnrollEmployeeInCourse{

    use AsAction;

    /**
     * The core logic for enrolling an employee in a course.
     */

     public function handle(Course $course, Employee $employee): bool
    {

        $courseId = (int) $course->getKey(); 
        if (! $courseId) {
            abort(404, 'Course not found');      
        }
        // Check if the employee is already enrolled in the course
        // syncWithoutDetaching() is used to avoid duplicate entries
        //Safetly creates the link and prevents errors if the employee is already enrolled in the course
        $changes = $employee->courses()->syncWithoutDetaching([$courseId]);
        $justAttached = in_array($courseId, $changes['attached'] ?? [], true);

        // Dispatch Notfication to the employee
        // This is a queued job that will be processed in the background

        if ($justAttached) {
            SendEnrollmentNotification::dispatch($employee, $course);
        }

        return $justAttached;

    }

    /**
     * This method acts as the controller for the route.
     */
    public function asController(Course $course, ActionRequest $request): Fractal
    {
        $employee = $request->user()->employee;

        $enrolledNow = $this->handle($course, $employee);

        $payload = [
            'course'       => ['id' => (int) $course->getKey(), 'title' => $course->title, 'status' => $course->status ?? null],
            'employee'     => ['id' => $employee->id, 'name' => $employee->user->name],
            'enrolled_now' => $enrolledNow,
        ];

        // Force a single item (prevents duplicate array response)
        return fractal()
            ->item($payload, new CourseEnrollmentTransformer());
    }


    /**
     * This method formats the final JSON response.
     */
    public function jsonResponse(Fractal $fractal): JsonResponse
    {
        // The status code can be set here
        return $fractal->respond(200);
    }

}
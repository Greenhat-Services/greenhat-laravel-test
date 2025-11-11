<?php

namespace Modules\Course\Actions\Course;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Models\CourseEnrollment;
use Spatie\Fractal\Fractal;

class UnenrollEmployee
{
    use AsAction;

    public function handle(CourseEnrollment $enrollment): CourseEnrollment
    {
        $enrollment->update(['status' => 'dropped']);
        $enrollment->refresh();

        return $enrollment;
    }

    public function authorize(ActionRequest $request): bool
    {
        $enrollment = CourseEnrollment::findOrFail($request->route('id'));
        $request->merge(['enrollment' => $enrollment]);

        // Public access for testing
        return true;
    }

    public function asController(ActionRequest $request)
    {
        $enrollment = $request->enrollment;
        $this->handle($enrollment);

        return response()->noContent(); // 204 No Content
    }
}

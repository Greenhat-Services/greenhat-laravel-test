<?php

namespace Modules\Course\Actions\CourseCategory;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Data\CourseCategory\CreateCourseCategoryData;
use Modules\Course\Models\CourseCategory;
use Modules\Course\Transformers\CourseCategoryTransformer;
use Spatie\Fractal\Fractal;

class CreateCourseCategory
{
    use AsAction;

    public function handle(CreateCourseCategoryData $data): CourseCategory
    {
        $category = CourseCategory::create($data->toArray());

        return $category;
    }

    public function authorize(ActionRequest $request): bool
    {
        // Public access for testing
        return true;
    }

    public function asController(ActionRequest $request): CourseCategory
    {
        $data = CreateCourseCategoryData::validateAndCreate($request->all());

        return $this->handle($data);
    }

    public function jsonResponse(CourseCategory $category): Fractal
    {
        return fractal($category, CourseCategoryTransformer::class)
            ->addMeta(['message' => 'Course category created successfully']);
    }
}

<?php

namespace Modules\Course\Actions\CourseCategory;

use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;
use Modules\Course\Data\CourseCategory\UpdateCourseCategoryData;
use Modules\Course\Models\CourseCategory;
use Modules\Course\Transformers\CourseCategoryTransformer;
use Spatie\Fractal\Fractal;

class UpdateCourseCategory
{
    use AsAction;

    public function handle(UpdateCourseCategoryData $data): CourseCategory
    {
        $data->category->update(
            $data->except('category')->toArray()
        );

        $data->category->refresh();

        return $data->category;
    }

    public function authorize(ActionRequest $request): bool
    {
        $category = CourseCategory::findOrFail($request->route('id'));
        $request->merge(['category' => $category]);

        // Public access for testing
        return true;
    }

    public function asController(ActionRequest $request): CourseCategory
    {
        $category = $request->category;

        $data = UpdateCourseCategoryData::validateAndCreate([
            ...$request->all(),
            'category' => $category,
        ]);

        return $this->handle($data);
    }

    public function jsonResponse(CourseCategory $category): Fractal
    {
        return fractal($category, CourseCategoryTransformer::class)
            ->addMeta(['message' => 'Course category updated successfully']);
    }
}

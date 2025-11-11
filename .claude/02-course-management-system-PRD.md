# Course Management System - Product Requirements Document (PRD)

## Overview
Implement a comprehensive Course Management System with categories, course enrollment capabilities, Filament admin interface, and employee enrollment API. This module will allow organizing courses into categories and enable employees to enroll in courses for their professional development.

---

## Backend Implementation Checklist

### 1. Module Setup

#### 1.1 Create Course Module
**Command:** `php artisan module:make Course`

- [ ] Create Course module structure
- [ ] Verify module is registered in `modules_statuses.json`
- [ ] Ensure module service provider is registered

---

### 2. Database Layer

#### 2.1 Create CourseCategory Migration
**File:** `Modules/Course/database/migrations/YYYY_MM_DD_HHMMSS_create_course_categories_table.php`

- [ ] Create `course_categories` table with columns:
  - `id` (bigIncrements/UUID primary key)
  - `name` (string, required, max:255)
  - `slug` (string, unique, for URL-friendly names)
  - `description` (text, nullable)
  - `is_active` (boolean, default true)
  - `created_at`, `updated_at` (timestamps)
- [ ] Add indexes on `slug`, `is_active` for performance

**Verification:**
```bash
php artisan migrate
php artisan tinker
Schema::hasTable('course_categories');
```

#### 2.2 Create Course Migration
**File:** `Modules/Course/database/migrations/YYYY_MM_DD_HHMMSS_create_courses_table.php`

- [ ] Create `courses` table with columns:
  - `id` (bigIncrements/UUID primary key)
  - `title` (string, required, max:255)
  - `slug` (string, unique)
  - `description` (text, nullable)
  - `instructor` (string, nullable)
  - `duration_hours` (integer, nullable) - Course duration in hours
  - `difficulty_level` (enum: 'beginner', 'intermediate', 'advanced', nullable)
  - `status` (enum: 'draft', 'published', 'archived', default 'draft')
  - `max_enrollments` (integer, nullable) - Maximum number of students
  - `start_date` (date, nullable)
  - `end_date` (date, nullable)
  - `created_at`, `updated_at` (timestamps)
- [ ] Add indexes on `slug`, `status`, `start_date`

#### 2.3 Create Course-Category Pivot Migration
**File:** `Modules/Course/database/migrations/YYYY_MM_DD_HHMMSS_create_course_course_category_table.php`

- [ ] Create `course_course_category` pivot table:
  - `id` (bigIncrements)
  - `course_id` (foreignId, constrained to courses)
  - `course_category_id` (foreignId, constrained to course_categories)
  - `created_at` (timestamp)
- [ ] Add composite unique index on (`course_id`, `course_category_id`)
- [ ] Add foreign key constraints with CASCADE on delete

#### 2.4 Create Course Enrollments Migration
**File:** `Modules/Course/database/migrations/YYYY_MM_DD_HHMMSS_create_course_enrollments_table.php`

- [ ] Create `course_enrollments` table:
  - `id` (bigIncrements/UUID primary key)
  - `course_id` (foreignId, constrained to courses)
  - `employee_id` (foreignId, constrained to employees)
  - `status` (enum: 'enrolled', 'completed', 'dropped', 'in_progress', default 'enrolled')
  - `enrolled_at` (timestamp, default now)
  - `completed_at` (timestamp, nullable)
  - `progress_percentage` (integer, default 0, range 0-100)
  - `notes` (text, nullable)
  - `created_at`, `updated_at` (timestamps)
- [ ] Add composite unique index on (`course_id`, `employee_id`) to prevent duplicate enrollments
- [ ] Add indexes on `status`, `enrolled_at`
- [ ] Add foreign key constraints with CASCADE on delete

#### 2.5 Run Migrations
```bash
php artisan migrate
```

---

### 3. Model Layer

#### 3.1 Create CourseCategory Model
**File:** `Modules/Course/app/Models/CourseCategory.php`

- [ ] Extend `Illuminate\Database\Eloquent\Model`
- [ ] Use traits:
  - `HasFactory`
  - `HasUUID` (if using UUID)
  - `HasSlugAttribute` (from Base module for auto-slug)
  - `CamelCasing` (for API response formatting)
- [ ] Define `$fillable`: name, slug, description, is_active
- [ ] Define `$casts`: is_active => 'boolean'
- [ ] Implement relationships:
  - `courses()` - belongsToMany(Course::class, 'course_course_category')
- [ ] Add scopes:
  - `scopeActive($query)` - Filter only active categories

**Reference:**
- Existing model: `Modules/Employee/app/Models/Employee.php`

#### 3.2 Create Course Model
**File:** `Modules/Course/app/Models/Course.php`

- [ ] Extend `Illuminate\Database\Eloquent\Model`
- [ ] Use traits: HasFactory, HasUUID, HasSlugAttribute, CamelCasing
- [ ] Define `$fillable`: title, slug, description, instructor, duration_hours, difficulty_level, status, max_enrollments, start_date, end_date
- [ ] Define `$casts`:
  - status => 'string'
  - difficulty_level => 'string'
  - duration_hours => 'integer'
  - max_enrollments => 'integer'
  - start_date => 'date'
  - end_date => 'date'
- [ ] Implement relationships:
  - `categories()` - belongsToMany(CourseCategory::class, 'course_course_category')
  - `enrollments()` - hasMany(CourseEnrollment::class)
  - `enrolledEmployees()` - belongsToMany(Employee::class, 'course_enrollments')->withPivot(...)
- [ ] Add scopes:
  - `scopePublished($query)` - Filter published courses
  - `scopeAvailableForEnrollment($query)` - Courses that aren't at max capacity
- [ ] Add accessors/methods:
  - `getAvailableSlotsAttribute()` - Calculate remaining slots
  - `isEnrollmentOpen()` - Check if enrollment is allowed

#### 3.3 Create CourseEnrollment Model
**File:** `Modules/Course/app/Models/CourseEnrollment.php`

- [ ] Extend `Illuminate\Database\Eloquent\Model`
- [ ] Use traits: HasFactory, HasUUID (if using), CamelCasing
- [ ] Define `$fillable`: course_id, employee_id, status, enrolled_at, completed_at, progress_percentage, notes
- [ ] Define `$casts`:
  - status => 'string'
  - enrolled_at => 'datetime'
  - completed_at => 'datetime'
  - progress_percentage => 'integer'
- [ ] Implement relationships:
  - `course()` - belongsTo(Course::class)
  - `employee()` - belongsTo(Employee::class)
- [ ] Add scopes:
  - `scopeActive($query)` - Enrolled or in progress
  - `scopeCompleted($query)` - Completed enrollments

---

### 4. Data Transfer Object (DTO) Layer

#### 4.1 Create CourseCategory DTOs
**Files:**
- `Modules/Course/app/Data/CourseCategory/CreateCourseCategoryData.php`
- `Modules/Course/app/Data/CourseCategory/UpdateCourseCategoryData.php`

- [ ] Extend `Spatie\LaravelData\Data`
- [ ] Define properties:
  - `string $name` (validation: required, min:2, max:255)
  - `?string $slug` (validation: nullable, unique:course_categories,slug)
  - `?string $description` (validation: nullable, max:5000)
  - `bool $is_active` (validation: boolean, default true)
- [ ] Implement validation rules in DTO
- [ ] For Update DTO, make slug unique except current record

#### 4.2 Create Course DTOs
**Files:**
- `Modules/Course/app/Data/Course/CreateCourseData.php`
- `Modules/Course/app/Data/Course/UpdateCourseData.php`

- [ ] Extend `Spatie\LaravelData\Data`
- [ ] Define properties:
  - `string $title` (validation: required, min:2, max:255)
  - `?string $slug` (validation: nullable, unique:courses,slug)
  - `?string $description` (validation: nullable, max:10000)
  - `?string $instructor` (validation: nullable, max:255)
  - `?int $duration_hours` (validation: nullable, integer, min:1)
  - `?string $difficulty_level` (validation: nullable, in:beginner,intermediate,advanced)
  - `string $status` (validation: required, in:draft,published,archived)
  - `?int $max_enrollments` (validation: nullable, integer, min:1)
  - `?string $start_date` (validation: nullable, date)
  - `?string $end_date` (validation: nullable, date, after:start_date)
  - `?array $category_ids` (validation: nullable, array, exists:course_categories,id)
- [ ] Implement validation rules

#### 4.3 Create Course Enrollment DTO
**File:** `Modules/Course/app/Data/Course/EnrollEmployeeData.php`

- [ ] Extend `Spatie\LaravelData\Data`
- [ ] Define properties:
  - `int $course_id` (validation: required, exists:courses,id)
  - `int $employee_id` (validation: required, exists:employees,id)
  - `?string $notes` (validation: nullable, max:1000)
- [ ] Custom validation rules:
  - Check course is published
  - Check course has available slots
  - Check employee not already enrolled
  - Check course enrollment period (start_date/end_date)

**Reference:**
- Existing DTOs: `Modules/Employee/app/Data/`
- Spatie Data docs: https://spatie.be/docs/laravel-data

---

### 5. Action Layer (Business Logic)

#### 5.1 Create CourseCategory Actions

##### CreateCourseCategory
**File:** `Modules/Course/app/Actions/CourseCategory/CreateCourseCategory.php`

- [ ] Use `AsAction` trait
- [ ] Implement `handle(CreateCourseCategoryData $data): CourseCategory`
  - Create CourseCategory with validated data
  - Auto-generate slug if not provided
  - Return created model
- [ ] Implement `authorize(ActionRequest $request): bool`
  - Check user can create categories (admin only)
- [ ] Implement `asController(ActionRequest $request): CourseCategory`
  - Validate and create DTO
  - Call handle()
- [ ] Implement `jsonResponse(CourseCategory $category): Fractal`
  - Use CourseCategoryTransformer
  - Add success message

##### UpdateCourseCategory
**File:** `Modules/Course/app/Actions/CourseCategory/UpdateCourseCategory.php`

- [ ] Similar structure to Create
- [ ] Handle updates to existing category
- [ ] Validate slug uniqueness except current record

##### DeleteCourseCategory
**File:** `Modules/Course/app/Actions/CourseCategory/DeleteCourseCategory.php`

- [ ] Implement soft delete or check for associated courses
- [ ] Return success response

##### GetCourseCategories (List)
**File:** `Modules/Course/app/Actions/CourseCategory/GetCourseCategories.php`

- [ ] Implement filtering: is_active, search by name
- [ ] Implement sorting: name, created_at
- [ ] Support pagination
- [ ] Include optional course count

##### GetCourseCategoryById
**File:** `Modules/Course/app/Actions/CourseCategory/GetCourseCategoryById.php`

- [ ] Fetch single category by ID
- [ ] Support includes: courses

#### 5.2 Create Course Actions

##### CreateCourse
**File:** `Modules/Course/app/Actions/Course/CreateCourse.php`

- [ ] Use `AsAction` trait
- [ ] Implement `handle(CreateCourseData $data): Course`
  - Create Course with validated data
  - Auto-generate slug if not provided
  - Sync categories if provided ($course->categories()->sync($data->category_ids))
  - Return created model with relationships loaded
- [ ] Implement authorization (admin only)
- [ ] Implement controller method with DTO validation
- [ ] Implement JSON response with transformer

##### UpdateCourse
**File:** `Modules/Course/app/Actions/Course/UpdateCourse.php`

- [ ] Similar structure to Create
- [ ] Update course and sync categories
- [ ] Handle status transitions (draft -> published)

##### DeleteCourse
**File:** `Modules/Course/app/Actions/Course/DeleteCourse.php`

- [ ] Check for active enrollments before deletion
- [ ] Implement soft delete or prevent deletion if enrollments exist

##### GetCourses (List)
**File:** `Modules/Course/app/Actions/Course/GetCourses.php`

- [ ] Implement filtering:
  - status (draft, published, archived)
  - difficulty_level
  - category_id
  - instructor
  - search (title, description)
  - available_for_enrollment (has slots)
- [ ] Implement sorting:
  - title, start_date, created_at, enrollments_count
- [ ] Support pagination
- [ ] Support includes: categories, enrollments

##### GetCourseById
**File:** `Modules/Course/app/Actions/Course/GetCourseById.php`

- [ ] Fetch single course by ID
- [ ] Support includes: categories, enrollments, enrolledEmployees

#### 5.3 Create Course Enrollment Action

##### EnrollEmployee
**File:** `Modules/Course/app/Actions/Course/EnrollEmployee.php`

- [ ] Use `AsAction` trait
- [ ] Implement `handle(EnrollEmployeeData $data): CourseEnrollment`
  - Validate course is published
  - Validate course has available slots
  - Check employee not already enrolled (no duplicate)
  - Check enrollment is within date range (start_date/end_date)
  - Create CourseEnrollment record
  - Dispatch notification job
  - Return created enrollment with relationships
- [ ] Implement authorization
  - Employee can enroll themselves
  - Admin can enroll any employee
- [ ] Implement controller method
- [ ] Implement JSON response

##### UnenrollEmployee (Optional)
**File:** `Modules/Course/app/Actions/Course/UnenrollEmployee.php`

- [ ] Update enrollment status to 'dropped'
- [ ] Authorization checks

##### GetEmployeeEnrollments
**File:** `Modules/Course/app/Actions/Course/GetEmployeeEnrollments.php`

- [ ] List all enrollments for an employee
- [ ] Filter by status, course
- [ ] Include course details

**Reference:**
- Existing actions: `Modules/Employee/app/Actions/`
- Laravel Actions docs: https://laravelactions.com/

---

### 6. Transformer Layer (API Response)

#### 6.1 Create CourseCategory Transformer
**File:** `Modules/Course/app/Transformers/CourseCategoryTransformer.php`

- [ ] Extend `League\Fractal\TransformerAbstract`
- [ ] Define `$availableIncludes = ['courses']`
- [ ] Implement `transform(CourseCategory $category)` returning:
  - id, name, slug, description, is_active
  - courses_count (count of associated courses)
  - created_at, updated_at
- [ ] Implement `includeCourses()` to include course data

#### 6.2 Create Course Transformer
**File:** `Modules/Course/app/Transformers/CourseTransformer.php`

- [ ] Extend `League\Fractal\TransformerAbstract`
- [ ] Define `$availableIncludes = ['categories', 'enrollments']`
- [ ] Implement `transform(Course $course)` returning:
  - id, title, slug, description, instructor
  - duration_hours, difficulty_level, status
  - max_enrollments, available_slots
  - start_date, end_date
  - enrollments_count
  - created_at, updated_at
- [ ] Implement `includeCategories()` using CourseCategoryTransformer
- [ ] Implement `includeEnrollments()` using CourseEnrollmentTransformer

#### 6.3 Create CourseEnrollment Transformer
**File:** `Modules/Course/app/Transformers/CourseEnrollmentTransformer.php`

- [ ] Extend `League\Fractal\TransformerAbstract`
- [ ] Define `$availableIncludes = ['course', 'employee']`
- [ ] Implement `transform(CourseEnrollment $enrollment)` returning:
  - id, course_id, employee_id
  - status, enrolled_at, completed_at
  - progress_percentage, notes
  - created_at, updated_at
- [ ] Implement `includeCourse()` using CourseTransformer
- [ ] Implement `includeEmployee()` using EmployeeTransformer

**Reference:**
- Existing transformers: `Modules/Employee/app/Transformers/`
- Fractal docs: https://fractal.thephpleague.com/

---

### 7. Route Layer

#### 7.1 CourseCategory Routes
**File:** `Modules/Course/routes/CourseCategory.v1.private.php`

- [ ] Define routes:
```php
Route::post('/course-categories', CreateCourseCategory::class);
Route::get('/course-categories', GetCourseCategories::class);
Route::get('/course-categories/{id}', GetCourseCategoryById::class);
Route::put('/course-categories/{id}', UpdateCourseCategory::class);
Route::delete('/course-categories/{id}', DeleteCourseCategory::class);
```

#### 7.2 Course Routes
**File:** `Modules/Course/routes/Course.v1.private.php`

- [ ] Define routes:
```php
Route::post('/courses', CreateCourse::class);
Route::get('/courses', GetCourses::class);
Route::get('/courses/{id}', GetCourseById::class);
Route::put('/courses/{id}', UpdateCourse::class);
Route::delete('/courses/{id}', DeleteCourse::class);
```

#### 7.3 Course Enrollment Routes
**File:** `Modules/Course/routes/CourseEnrollment.v1.private.php`

- [ ] Define routes:
```php
Route::post('/course-enrollments', EnrollEmployee::class);
Route::get('/course-enrollments', GetEmployeeEnrollments::class);
Route::delete('/course-enrollments/{id}', UnenrollEmployee::class);
```

**Route Naming Convention:**
- Format: `{ActionName}.{version}.{visibility}.php`
- `private` visibility = `auth:sanctum` middleware applied automatically
- Route names auto-generated as `{ActionName}_{version}`

---

### 8. Notification Layer

#### 8.1 Create Enrollment Notification Job
**File:** `Modules/Course/app/Jobs/SendCourseEnrollmentNotification.php`

- [ ] Implement `ShouldQueue` interface
- [ ] Accept CourseEnrollment model in constructor
- [ ] Implement `handle()` method:
  - Send email to employee
  - Send Slack notification (optional)
  - Log enrollment
- [ ] Set queue name and retries

#### 8.2 Create Enrollment Email
**File:** `Modules/Course/app/Emails/CourseEnrollmentConfirmationEmail.php`

- [ ] Extend `Illuminate\Mail\Mailable`
- [ ] Accept CourseEnrollment model in constructor
- [ ] Implement `build()` method:
  - Subject: "Course Enrollment Confirmation - {Course Title}"
  - View: `course::emails.enrollment-confirmation`
  - Pass enrollment, course, employee data

#### 8.3 Create Email Template
**File:** `Modules/Course/resources/views/emails/enrollment-confirmation.blade.php`

- [ ] Welcome message
- [ ] Course details (title, instructor, duration, dates)
- [ ] Enrollment confirmation
- [ ] Next steps or access instructions
- [ ] Use responsive email design

**Reference:**
- Existing emails: `Modules/Employee/resources/views/emails/`

---

### 9. Observer Layer (Optional)

#### 9.1 Create CourseEnrollment Observer
**File:** `Modules/Course/app/Observers/CourseEnrollmentObserver.php`

- [ ] Implement `created()` event:
  - Dispatch SendCourseEnrollmentNotification job
- [ ] Implement `updated()` event:
  - Check if status changed to 'completed'
  - Send completion notification

#### 9.2 Register Observer
**File:** `Modules/Course/app/Providers/EventServiceProvider.php`

- [ ] Register CourseEnrollmentObserver
- [ ] Bind to CourseEnrollment model

---

### 10. Filament Admin Interface

#### 10.1 Create CourseCategory Filament Resource
**File:** `Modules/Course/app/Filament/Resources/CourseCategoryResource.php`

- [ ] Extend base Filament Resource class
- [ ] Define form schema:
  - TextInput for name (required)
  - Textarea for description
  - Toggle for is_active
- [ ] Define table columns:
  - TextColumn: name, slug
  - BooleanColumn: is_active
  - TextColumn: courses_count (counts relationship)
  - TextColumn: created_at
- [ ] Define filters:
  - Active/Inactive filter
- [ ] Define actions:
  - EditAction, DeleteAction
- [ ] Define bulk actions:
  - BulkDeleteAction

#### 10.2 Create Course Filament Resource
**File:** `Modules/Course/app/Filament/Resources/CourseResource.php`

- [ ] Extend base Filament Resource class
- [ ] Define form schema:
  - TextInput: title (required)
  - Textarea: description
  - TextInput: instructor
  - TextInput: duration_hours (numeric)
  - Select: difficulty_level (options: beginner, intermediate, advanced)
  - Select: status (options: draft, published, archived)
  - TextInput: max_enrollments (numeric)
  - DatePicker: start_date, end_date
  - Select: categories (relationship, multiple, searchable)
- [ ] Define table columns:
  - TextColumn: title, instructor, status, difficulty_level
  - TextColumn: duration_hours, max_enrollments
  - TextColumn: enrollments_count
  - TextColumn: available_slots (computed)
  - DateColumn: start_date, end_date
  - TextColumn: created_at
- [ ] Define filters:
  - SelectFilter: status
  - SelectFilter: difficulty_level
  - SelectFilter: categories (relationship)
- [ ] Define actions:
  - ViewAction, EditAction, DeleteAction
- [ ] Define pages:
  - ListCourses, CreateCourse, EditCourse, ViewCourse

#### 10.3 Create CourseEnrollment Filament Resource (Optional)
**File:** `Modules/Course/app/Filament/Resources/CourseEnrollmentResource.php`

- [ ] Form with course, employee, status, progress
- [ ] Table with enrollments, status, dates
- [ ] Filters by status, course, employee

#### 10.4 Register Resources in Module
**File:** `Modules/Course/app/Providers/CourseServiceProvider.php`

- [ ] Register Filament resources in `boot()` method
- [ ] Ensure resources are discoverable

**Reference:**
- FilamentPHP docs: https://filamentphp.com/docs
- Existing resources: `app/Filament/Resources/`

---

### 11. Seeder Layer

#### 11.1 Create CourseCategory Seeder
**File:** `Modules/Course/database/seeders/CourseCategorySeeder.php`

- [ ] Create realistic categories:
  - Technical (Web Development, Mobile Development, DevOps, Security)
  - Leadership (Team Management, Strategic Thinking, Communication)
  - Soft Skills (Presentation, Negotiation, Time Management)
  - Business (Marketing, Sales, Finance, Project Management)
  - Design (UI/UX, Graphic Design, Product Design)
- [ ] Set is_active = true for all
- [ ] Generate slugs automatically

#### 11.2 Create Course Seeder
**File:** `Modules/Course/database/seeders/CourseSeeder.php`

- [ ] Create 20-30 realistic courses with:
  - Varied titles (e.g., "Advanced Laravel Development", "Leadership Fundamentals")
  - Detailed descriptions
  - Instructors (generate or use real names)
  - Duration: 4-40 hours
  - Difficulty: mix of beginner, intermediate, advanced
  - Status: mix of draft, published
  - Max enrollments: 10-50
  - Start/end dates (some past, some future, some ongoing)
- [ ] Attach categories to courses (1-3 categories per course)
- [ ] Use Faker for realistic data

#### 11.3 Create CourseEnrollment Seeder (Optional)
**File:** `Modules/Course/database/seeders/CourseEnrollmentSeeder.php`

- [ ] Create sample enrollments for existing employees
- [ ] Vary status (enrolled, in_progress, completed, dropped)
- [ ] Set progress_percentage realistically
- [ ] Set enrolled_at, completed_at dates

#### 11.4 Register Seeders
**File:** `Modules/Course/database/seeders/CourseDatabaseSeeder.php`

- [ ] Call category seeder
- [ ] Call course seeder
- [ ] Call enrollment seeder

```bash
php artisan module:seed Course
```

**Reference:**
- Existing seeders: `Modules/Employee/database/seeders/`
- Laravel Seeder docs: https://laravel.com/docs/seeding

---

### 12. Exception Handling

#### 12.1 Create Custom Exceptions
**Files:**
- `Modules/Course/app/Exceptions/CourseNotFoundException.php`
- `Modules/Course/app/Exceptions/CourseEnrollmentException.php`

- [ ] Extend base Exception
- [ ] Define specific error messages:
  - Course not found
  - Course not published
  - Course at max capacity
  - Employee already enrolled
  - Enrollment period not active
- [ ] Return appropriate HTTP status codes (404, 422, 400)

**Reference:**
- Existing exceptions: `Modules/Employee/app/Exceptions/`

---

### 13. Policy Layer (Authorization)

#### 13.1 Create Course Policy
**File:** `Modules/Course/app/Policies/CoursePolicy.php`

- [ ] Define authorization rules:
  - `viewAny()` - All authenticated users can view
  - `view()` - All authenticated users can view single course
  - `create()` - Only admins
  - `update()` - Only admins
  - `delete()` - Only admins

#### 13.2 Create CourseEnrollment Policy
**File:** `Modules/Course/app/Policies/CourseEnrollmentPolicy.php`

- [ ] Define rules:
  - `create()` - Employee can enroll themselves, admins can enroll anyone
  - `view()` - Employee can view own enrollments, admins view all
  - `delete()` - Employee can unenroll themselves, admins can unenroll anyone

#### 13.3 Register Policies
**File:** `Modules/Course/app/Providers/AuthServiceProvider.php`

- [ ] Register Course and CourseEnrollment policies
- [ ] Import in module service provider

**Reference:**
- Laravel Policies: https://laravel.com/docs/authorization#creating-policies
- Bouncer integration if needed

---

### 14. Testing (Optional but Recommended)

#### 14.1 Feature Tests - Course Category
**File:** `Modules/Course/tests/Feature/CourseCategoryTest.php`

- [ ] Test create category
- [ ] Test update category
- [ ] Test delete category
- [ ] Test list categories with filters
- [ ] Test validation errors

#### 14.2 Feature Tests - Course
**File:** `Modules/Course/tests/Feature/CourseTest.php`

- [ ] Test create course with categories
- [ ] Test update course and sync categories
- [ ] Test delete course
- [ ] Test list courses with filters (status, category, difficulty)
- [ ] Test sorting (title, start_date)
- [ ] Test available slots calculation

#### 14.3 Feature Tests - Course Enrollment
**File:** `Modules/Course/tests/Feature/CourseEnrollmentTest.php`

- [ ] Test successful enrollment
- [ ] Test enrollment fails when course at capacity
- [ ] Test duplicate enrollment prevention
- [ ] Test enrollment fails for draft courses
- [ ] Test enrollment outside date range
- [ ] Test enrollment notification is queued
- [ ] Test unenrollment

#### 14.4 Run Tests
```bash
php artisan test --filter Course
vendor/bin/pest Modules/Course/tests
```

---

## Frontend Considerations (Optional Extension)

### 15. Course Catalog Page
- [ ] List all published courses with filters
- [ ] Show course details (title, description, instructor, dates)
- [ ] Show available slots
- [ ] Filter by category, difficulty, status
- [ ] Search by title/description

### 16. Enrollment Interface
- [ ] Course detail page with enrollment button
- [ ] Enrollment confirmation modal
- [ ] Show enrollment status
- [ ] My Courses page showing employee's enrollments

---

## File Structure Summary

```
Modules/Course/
├── app/
│   ├── Actions/
│   │   ├── CourseCategory/
│   │   │   ├── CreateCourseCategory.php              ✓ NEW
│   │   │   ├── UpdateCourseCategory.php              ✓ NEW
│   │   │   ├── DeleteCourseCategory.php              ✓ NEW
│   │   │   ├── GetCourseCategories.php               ✓ NEW
│   │   │   └── GetCourseCategoryById.php             ✓ NEW
│   │   └── Course/
│   │       ├── CreateCourse.php                      ✓ NEW
│   │       ├── UpdateCourse.php                      ✓ NEW
│   │       ├── DeleteCourse.php                      ✓ NEW
│   │       ├── GetCourses.php                        ✓ NEW
│   │       ├── GetCourseById.php                     ✓ NEW
│   │       ├── EnrollEmployee.php                    ✓ NEW
│   │       ├── UnenrollEmployee.php                  ✓ NEW
│   │       └── GetEmployeeEnrollments.php            ✓ NEW
│   ├── Data/
│   │   ├── CourseCategory/
│   │   │   ├── CreateCourseCategoryData.php          ✓ NEW
│   │   │   └── UpdateCourseCategoryData.php          ✓ NEW
│   │   └── Course/
│   │       ├── CreateCourseData.php                  ✓ NEW
│   │       ├── UpdateCourseData.php                  ✓ NEW
│   │       └── EnrollEmployeeData.php                ✓ NEW
│   ├── Emails/
│   │   └── CourseEnrollmentConfirmationEmail.php     ✓ NEW
│   ├── Exceptions/
│   │   ├── CourseNotFoundException.php               ✓ NEW
│   │   └── CourseEnrollmentException.php             ✓ NEW
│   ├── Filament/
│   │   └── Resources/
│   │       ├── CourseCategoryResource.php            ✓ NEW
│   │       ├── CourseResource.php                    ✓ NEW
│   │       └── CourseEnrollmentResource.php          ✓ NEW (optional)
│   ├── Jobs/
│   │   └── SendCourseEnrollmentNotification.php      ✓ NEW
│   ├── Models/
│   │   ├── CourseCategory.php                        ✓ NEW
│   │   ├── Course.php                                ✓ NEW
│   │   └── CourseEnrollment.php                      ✓ NEW
│   ├── Observers/
│   │   └── CourseEnrollmentObserver.php              ✓ NEW
│   ├── Policies/
│   │   ├── CoursePolicy.php                          ✓ NEW
│   │   └── CourseEnrollmentPolicy.php                ✓ NEW
│   └── Transformers/
│       ├── CourseCategoryTransformer.php             ✓ NEW
│       ├── CourseTransformer.php                     ✓ NEW
│       └── CourseEnrollmentTransformer.php           ✓ NEW
├── database/
│   ├── migrations/
│   │   ├── YYYY_MM_DD_create_course_categories_table.php    ✓ NEW
│   │   ├── YYYY_MM_DD_create_courses_table.php              ✓ NEW
│   │   ├── YYYY_MM_DD_create_course_course_category_table.php ✓ NEW
│   │   └── YYYY_MM_DD_create_course_enrollments_table.php   ✓ NEW
│   └── seeders/
│       ├── CourseCategorySeeder.php                  ✓ NEW
│       ├── CourseSeeder.php                          ✓ NEW
│       ├── CourseEnrollmentSeeder.php                ✓ NEW
│       └── CourseDatabaseSeeder.php                  ✓ NEW
├── resources/
│   └── views/
│       └── emails/
│           └── enrollment-confirmation.blade.php     ✓ NEW
├── routes/
│   ├── CourseCategory.v1.private.php                 ✓ NEW
│   ├── Course.v1.private.php                         ✓ NEW
│   └── CourseEnrollment.v1.private.php               ✓ NEW
├── tests/
│   └── Feature/
│       ├── CourseCategoryTest.php                    ✓ NEW
│       ├── CourseTest.php                            ✓ NEW
│       └── CourseEnrollmentTest.php                  ✓ NEW
├── config/
│   └── config.php
└── module.json                                       ✓ UPDATE
```

---

## Skill Level Alignment

### Essential (Must Have)
- ✓ Database migrations for all tables
- ✓ Model relationships (belongsToMany, hasMany)
- ✓ Basic model setup with fillable/casts

### Proficient
- ✓ CRUD Actions for courses and categories
- ✓ DTO validation with Laravel Data
- ✓ Fractal transformers for API responses
- ✓ Filtering and sorting in list endpoints
- ✓ Realistic seeders with Faker

### Advanced
- ✓ Filament resources for admin management
- ✓ Course enrollment API with complex validation
- ✓ Observer pattern for enrollment notifications
- ✓ Queue job for email notifications
- ✓ Policy-based authorization
- ✓ Custom exceptions for business logic
- ✓ Many-to-many pivot table management
- ✓ Complex query scopes and computed attributes

---

## Success Criteria

✅ Course module created and registered
✅ All migrations run successfully
✅ Models with proper relationships work correctly
✅ CourseCategory CRUD API endpoints functional
✅ Course CRUD API endpoints functional
✅ Filtering and sorting work on list endpoints
✅ Course enrollment API prevents duplicates and capacity issues
✅ Enrollment notification email queued and sent
✅ Filament resources display and manage courses/categories
✅ Seeders populate realistic test data
✅ Authorization policies enforced
✅ Many-to-many relationship between courses and categories works
✅ Available slots calculated correctly
✅ All validation rules working as expected

---

## Common Pitfalls to Avoid

1. ❌ Don't forget to sync categories when creating/updating courses
2. ❌ Don't allow enrollment in draft courses
3. ❌ Don't allow duplicate enrollments (same employee + course)
4. ❌ Don't exceed max_enrollments capacity
5. ❌ Don't forget to validate date ranges (end_date > start_date)
6. ❌ Don't expose sensitive data in transformers
7. ❌ Don't forget indexes on frequently queried columns
8. ❌ Don't skip authorization checks in actions
9. ❌ Don't forget to register Filament resources
10. ❌ Don't forget to import Employee model for enrollments relationship

---

## Timeline Estimate

### Backend
- Module setup: 15 min
- Database migrations (4 tables): 1 hour
- Models with relationships: 1 hour
- DTOs (6 files): 1.5 hours
- Actions - CourseCategory CRUD: 1.5 hours
- Actions - Course CRUD: 2 hours
- Actions - Enrollment: 1.5 hours
- Transformers (3 files): 1 hour
- Routes (3 files): 30 min
- Notification job & email: 1 hour
- Observer: 30 min
- Policies: 45 min
- Exceptions: 30 min

### Filament Admin
- CourseCategory Resource: 1 hour
- Course Resource: 1.5 hours
- CourseEnrollment Resource: 45 min

### Seeders & Testing
- Seeders (3 files): 1.5 hours
- Feature tests: 2 hours

**Total: 18-22 hours**

---

## Quick Reference Commands

```bash
# Module Commands
php artisan module:make Course
php artisan module:list
php artisan module:seed Course

# Migrations
php artisan migrate
php artisan migrate:fresh --seed

# Development
php artisan serve
php artisan queue:work
php artisan pail

# Testing
php artisan test --filter Course
vendor/bin/pest Modules/Course/tests

# Database
php artisan tinker
# In tinker:
Course::with('categories', 'enrollments')->first()
Employee::first()->courseEnrollments
```

---

## API Endpoint Examples

### CourseCategory Endpoints
```
POST   /v1/course-categories
GET    /v1/course-categories
GET    /v1/course-categories/{id}
PUT    /v1/course-categories/{id}
DELETE /v1/course-categories/{id}
```

### Course Endpoints
```
POST   /v1/courses
GET    /v1/courses?status=published&category_id=1&difficulty_level=intermediate&sort=title
GET    /v1/courses/{id}?include=categories,enrollments
PUT    /v1/courses/{id}
DELETE /v1/courses/{id}
```

### Enrollment Endpoints
```
POST   /v1/course-enrollments
GET    /v1/course-enrollments?employee_id=1&status=enrolled
DELETE /v1/course-enrollments/{id}
```

---

## Sample API Request/Response

### Enroll Employee in Course

**Request:**
```http
POST /v1/course-enrollments
Content-Type: application/json
Authorization: Bearer {token}

{
  "course_id": 5,
  "employee_id": 2,
  "notes": "Looking forward to this course!"
}
```

**Response:**
```json
{
  "data": {
    "id": 123,
    "course_id": 5,
    "employee_id": 2,
    "status": "enrolled",
    "enrolled_at": "2025-01-15T14:30:00.000000Z",
    "completed_at": null,
    "progress_percentage": 0,
    "notes": "Looking forward to this course!",
    "created_at": "2025-01-15T14:30:00.000000Z",
    "course": {
      "id": 5,
      "title": "Advanced Laravel Development",
      "instructor": "John Smith",
      "duration_hours": 20,
      "available_slots": 14
    },
    "employee": {
      "id": 2,
      "name": "Jane Doe",
      "email": "jane.doe@example.com"
    }
  },
  "meta": {
    "message": "Successfully enrolled in course"
  }
}
```

---

*This PRD serves as a comprehensive guide to implement the Course Management System. Each checkbox can be marked as you complete tasks, allowing you to track progress throughout the implementation.*

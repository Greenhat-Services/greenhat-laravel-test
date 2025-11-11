# Employee Registration Feature - Product Requirements Document (PRD)

## Overview
Implement a public API endpoint for employee self-registration with email confirmation, following the existing Laravel modular architecture pattern. Frontend will be built with Vue.js/Nuxt.

---

## Backend Implementation Checklist

### 1. Database Layer

#### 1.1 Create Migration for Missing Columns
**File:** `Modules/Employee/database/migrations/YYYY_MM_DD_HHMMSS_add_employee_registration_fields.php`

- [ ] Add `confirmation_email_sent_at` (timestamp, nullable) to `employees` table
- [ ] Verify `employees` table has these existing columns:
  - `role` (string)
  - `highest_qualification` (string)
  - `desired_salary` (decimal/integer)
  - `note` (text, nullable)
- [ ] Add any missing columns if they don't exist
- [ ] Run migration: `php artisan migrate`

**Verification:**
```bash
php artisan tinker
Schema::hasColumn('employees', 'confirmation_email_sent_at');
```

---

### 2. Data Transfer Object (DTO) Layer

#### 2.1 Create Registration DTO
**File:** `Modules/Employee/app/Data/Employee/CreateEmployeeRegistrationData.php`

- [ ] Extend `Spatie\LaravelData\Data`
- [ ] Define properties:
  - `string $first_name` (validation: required, min:2, max:255)
  - `string $last_name` (validation: required, min:2, max:255)
  - `string $name` (validation: required, min:2, max:255)
  - `string $email` (validation: required, email, unique:users,email)
  - `string $role` (validation: required, max:255)
  - `string $highest_qualification` (validation: required, max:255)
  - `?float $desired_salary` (validation: nullable, numeric, min:0)
  - `?string $note` (validation: nullable, max:5000)
- [ ] Implement validation rules in DTO

**Reference:**
- Existing DTOs: `Modules/Employee/app/Data/Employee/UpdateEmployeeData.php`
- Spatie Data docs: https://spatie.be/docs/laravel-data

---

### 3. Action Layer (Business Logic)

#### 3.1 Create Employee Registration Action
**File:** `Modules/Employee/app/Actions/Employee/CreateEmployeeRegistration.php`

- [ ] Use `Lorisleiva\Actions\Concerns\AsAction` trait
- [ ] Implement `handle()` method:
  - [ ] Auto-generate secure password using `Str::random(16)` or similar
  - [ ] Create User record with:
    - first_name, last_name, email, password (hashed)
    - name = first_name + last_name
  - [ ] Create Employee record linked to User with:
    - user_id, role, highest_qualification, desired_salary, note
  - [ ] Return created Employee model with User relationship loaded
- [ ] Implement `authorize(ActionRequest $request): bool`
  - [ ] Return `true` (public endpoint, no auth needed)
- [ ] Implement `asController(ActionRequest $request): Employee`
  - [ ] Validate and create DTO using `CreateEmployeeRegistrationData::validateAndCreate()`
  - [ ] Call `handle()` method
  - [ ] Return Employee model
- [ ] Implement `jsonResponse(Employee $employee): Fractal`
  - [ ] Use EmployeeRegistrationTransformer
  - [ ] Parse includes: 'user'
  - [ ] Add meta data: success message

**Reference:**
- Existing action: `Modules/Employee/app/Actions/Employee/UpdateEmployee.php`
- Laravel Actions docs: https://laravelactions.com/

---

### 4. Transformer Layer (API Response)

#### 4.1 Create Registration Response Transformer
**File:** `Modules/Employee/app/Transformers/EmployeeRegistrationTransformer.php`

- [ ] Extend `League\Fractal\TransformerAbstract`
- [ ] Define `$defaultIncludes = ['user']`
- [ ] Implement `transform()` method returning:
  - employee_id (or uuid if using UUID)
  - role, highest_qualification, desired_salary, note
  - confirmation_email_sent_at
  - created_at
- [ ] Implement `includeUser()` to include User data (first_name, last_name, name, email)

**Reference:**
- Existing transformer: `Modules/Employee/app/Transformers/EmployeeTransformer.php`

---

### 5. Email Layer

#### 5.1 Create Confirmation Email Mailable
**File:** `Modules/Employee/app/Emails/EmployeeRegistrationConfirmationEmail.php`

- [ ] Extend `Illuminate\Mail\Mailable`
- [ ] Accept Employee model in constructor
- [ ] Implement `build()` method:
  - [ ] Set subject: "Welcome to [Company Name] - Registration Confirmed"
  - [ ] Set view: `employee::emails.registration-confirmation`
  - [ ] Pass employee and user data to view

#### 5.2 Create Email Template
**File:** `Modules/Employee/resources/views/emails/registration-confirmation.blade.php`

- [ ] Extend base email layout if exists: `base::email.layout`
- [ ] Include employee information:
  - Name, email
  - Welcome message
  - Next steps information
- [ ] Use responsive email design

**Reference:**
- Existing email: `Modules/Employee/resources/views/emails/feedback.blade.php`

---

### 6. Observer Layer (Side Effects)

#### 6.1 Update Employee Observer
**File:** `Modules/Employee/app/Observers/EmployeeObserver.php` (already exists)

- [ ] Add `created()` event listener
- [ ] Check if employee was created via registration (maybe add a flag or check if confirmation_email_sent_at is null)
- [ ] Dispatch `SendEmployeeRegistrationConfirmationEmail` job
- [ ] Alternative: Create new observer specifically for registration

#### 6.2 Create Job for Sending Email
**File:** `Modules/Employee/app/Jobs/SendEmployeeRegistrationConfirmationEmail.php`

- [ ] Implement `ShouldQueue` interface
- [ ] Accept Employee model in constructor
- [ ] Implement `handle()` method:
  - [ ] Send EmployeeRegistrationConfirmationEmail
  - [ ] Update `confirmation_email_sent_at` timestamp on success
  - [ ] Handle failures gracefully
- [ ] Set queue name and retries appropriately

**Reference:**
- Laravel Queues: https://laravel.com/docs/queues

---

### 7. Route Layer

#### 7.1 Create Public API Route
**File:** `Modules/Employee/routes/EmployeeRegistration.v1.public.php`

- [ ] Create route file following naming convention
- [ ] Define POST route:
```php
Route::post('/employee-registration', CreateEmployeeRegistration::class);
```
- [ ] This will auto-generate route: `POST /v1/employee-registration`
- [ ] Route name will be: `EmployeeRegistration_v1`

**Route Naming Convention Reminder:**
- Format: `{ActionName}.{version}.{visibility}.php`
- `public` visibility = JsonResponseMiddleware applied automatically
- No auth:sanctum middleware for public routes

---

### 8. Exception Handling

#### 8.1 Create Custom Exception
**File:** `Modules/Employee/app/Exceptions/EmployeeRegistrationException.php`

- [ ] Extend `Modules\Base\Exceptions\ApplicationException` or `Exception`
- [ ] Handle specific registration errors:
  - Invalid registration code
  - Duplicate email
  - User creation failure
- [ ] Return appropriate HTTP status codes (400, 422, 500)

**Reference:**
- Existing exception: `Modules/Employee/app/Exceptions/EmployeeException.php`

---

### 9. Service Provider Updates

#### 9.1 Register Observer
**File:** `Modules/Employee/app/Providers/EventServiceProvider.php` (already exists)

- [ ] Register EmployeeObserver if not already registered
- [ ] Ensure observer is bound to Employee model

---

### 10. Testing (Optional but Recommended)

#### 10.1 Feature Tests
**File:** `Modules/Employee/tests/Feature/EmployeeRegistrationTest.php`

- [ ] Test successful registration with valid code
- [ ] Test registration fails with invalid code
- [ ] Test duplicate email validation
- [ ] Test required field validations
- [ ] Test email is queued
- [ ] Test confirmation_email_sent_at is updated

#### 10.2 Run Tests
```bash
php artisan test --filter EmployeeRegistration
```

---

## Frontend Implementation Checklist

### 11. Vue/Nuxt Setup

#### 11.1 Initialize Nuxt Project (if not exists)
- [ ] Create `nuxt-app/` or `frontend/` directory in project root
- [ ] Initialize Nuxt 3: `npx nuxi@latest init employee-registration`
- [ ] Install Nuxt UI: `npm install @nuxt/ui`
- [ ] Configure `nuxt.config.ts` to include Nuxt UI module

#### 11.2 Configure API Connection
**File:** `nuxt-app/nuxt.config.ts`

- [ ] Set up `runtimeConfig` with API base URL
- [ ] Configure axios/fetch for API calls
- [ ] Handle CORS if needed (Laravel side: config/cors.php)

---

### 12. Registration Form Component

#### 12.1 Create Registration Page
**File:** `nuxt-app/pages/employee-registration.vue`

- [ ] Create form with fields:
  - Registration Code (text input, placeholder: "Enter code (ACME)")
  - First Name (text input, required)
  - Last Name (text input, required)
  - Email (email input, required, validated)
  - Role (text input or select, required)
  - Highest Qualification (text input or select, required)
  - Desired Salary (number input, optional)
  - Note (textarea, optional)
- [ ] Use Nuxt UI components (UInput, UButton, UFormGroup, UTextarea)
- [ ] Implement client-side validation
- [ ] Show loading state during submission
- [ ] Handle success/error messages

#### 12.2 Create Composable for API Calls
**File:** `nuxt-app/composables/useEmployeeRegistration.ts`

- [ ] Create composable function
- [ ] Implement `submitRegistration(data)` method
- [ ] Handle API response/errors
- [ ] Return reactive state (loading, error, success)

---

### 13. UX/UI Enhancements

#### 13.1 Success State
- [ ] Show success message after registration
- [ ] Display confirmation that email has been sent
- [ ] Provide next steps or redirect

#### 13.2 Error Handling
- [ ] Display validation errors from API
- [ ] Show user-friendly error messages
- [ ] Handle network errors gracefully

#### 13.3 Form Validation
- [ ] Real-time field validation
- [ ] Show error messages below fields
- [ ] Disable submit button while invalid
- [ ] Password strength indicator (if showing password)

---

## Laravel Configuration

### 14. Queue & Email Configuration

#### 14.1 Queue Configuration
**File:** `.env`

- [ ] Set `QUEUE_CONNECTION=database` or `redis`
- [ ] Run migrations for queue: `php artisan queue:table` then `php artisan migrate`
- [ ] Start queue worker: `php artisan queue:work`

#### 14.2 Email Configuration
**File:** `.env`

- [ ] Configure mail driver (mailtrap, smtp, mailhog for testing)
- [ ] Set MAIL_FROM_ADDRESS and MAIL_FROM_NAME
- [ ] Test email sending: `php artisan tinker` then `Mail::raw('test', fn($m) => $m->to('test@test.com'))`

#### 14.3 CORS Configuration (for Vue frontend)
**File:** `config/cors.php`

- [ ] Add frontend URL to `allowed_origins`
- [ ] Or set `'allowed_origins' => ['*']` for development

---

## Git Workflow

### 15. Branch & Commit Strategy

- [ ] Create branch: `your_name/employee-registration`
- [ ] Make atomic commits with clear messages:
  - "feat: add employee registration migration"
  - "feat: create EmployeeRegistrationData DTO"
  - "feat: implement CreateEmployeeRegistration action"
  - "feat: add registration confirmation email"
  - "feat: implement registration observer and job"
  - "feat: add employee registration route"
  - "feat: create Vue registration form"
  - "test: add employee registration feature tests"

---

## Testing Workflow

### 16. Manual Testing

#### 16.1 Backend API Testing
```bash
# Using curl or Postman
POST http://localhost:8000/v1/employee-registration
Content-Type: application/json

{
  "registration_code": "ACME",
  "first_name": "John",
  "last_name": "Doe",
  "email": "john.doe@example.com",
  "role": "Software Engineer",
  "highest_qualification": "Bachelor's Degree",
  "desired_salary": 75000,
  "note": "Excited to join the team!"
}
```

#### 16.2 Expected Response
```json
{
  "data": {
    "employee_id": 1,
    "role": "Software Engineer",
    "highest_qualification": "Bachelor's Degree",
    "desired_salary": 75000,
    "note": "Excited to join the team!",
    "confirmation_email_sent_at": null,
    "created_at": "2025-01-15T10:30:00.000000Z",
    "user": {
      "first_name": "John",
      "last_name": "Doe",
      "name": "John Doe",
      "email": "john.doe@example.com"
    }
  }
}
```

#### 16.3 Verify Database
```bash
php artisan tinker
Employee::with('user')->latest()->first()
```

#### 16.4 Verify Email Queue
```bash
# Check jobs table
DB::table('jobs')->get();

# Process queue
php artisan queue:work --once
```

#### 16.5 Frontend Testing
- [ ] Open browser to `http://localhost:3000/employee-registration`
- [ ] Test form validation
- [ ] Submit valid registration
- [ ] Verify success message
- [ ] Check database for new employee

---

## Pull Request Checklist

### 17. Before Creating PR

- [ ] All migrations run successfully
- [ ] All tests pass (if written)
- [ ] Code follows existing patterns (Actions, DTOs, Routes)
- [ ] No hardcoded values (use config or env)
- [ ] Email template is professional and tested
- [ ] Queue job processes successfully
- [ ] Frontend form is responsive and user-friendly
- [ ] No console errors in browser
- [ ] API returns proper error messages for invalid input

### 18. PR Description Template

```markdown
## [CANDIDATE] Your Name - Employee Registration Challenge

### Implementation Approach

**Backend:**
- Created modular Employee registration following Laravel Actions pattern
- Implemented DTO validation using Spatie Laravel Data
- Used Observer pattern for email notifications
- Queued email sending for performance
- Followed existing route naming convention

**Frontend:**
- Built with Nuxt 3 + Nuxt UI
- Responsive registration form with real-time validation
- Proper error handling and user feedback

### Technical Decisions

1. **Auto-generated passwords:** Used `Str::random(16)` for security
2. **Email queueing:** Prevents blocking API response
3. **Observer pattern:** Decouples email logic from main action
4. **DTO validation:** Centralized validation rules

### Testing

- Tested successful registration flow
- Validated error handling (invalid code, duplicate email)
- Verified email queue processing
- Tested frontend form validation

### Screenshots
[Add screenshots of frontend form]

### Database Changes
- Added `confirmation_email_sent_at` column to employees table

### Notes
- Email configured for development using [Mailtrap/Mailhog/etc]
- Queue worker must be running: `php artisan queue:work`
```

---

## Quick Reference Commands

```bash
# Backend Development
php artisan serve                    # Start server
php artisan queue:work              # Process queue jobs
php artisan migrate                 # Run migrations
php artisan tinker                  # Test in console
php artisan test                    # Run tests

# Frontend Development (Nuxt)
cd nuxt-app
npm run dev                         # Start dev server
npm run build                       # Production build

# Database
php artisan migrate:fresh --seed    # Reset database
php artisan db:seed                 # Run seeders only

# Debugging
php artisan pail                    # Watch logs
tail -f storage/logs/laravel.log   # View log file
```

---

## File Structure Summary

```
Modules/Employee/
├── app/
│   ├── Actions/
│   │   └── Employee/
│   │       └── CreateEmployeeRegistration.php          ✓ NEW
│   ├── Data/
│   │   └── Employee/
│   │       └── CreateEmployeeRegistrationData.php      ✓ NEW
│   ├── Emails/
│   │   └── EmployeeRegistrationConfirmationEmail.php   ✓ NEW
│   ├── Exceptions/
│   │   └── EmployeeRegistrationException.php           ✓ NEW
│   ├── Jobs/
│   │   └── SendEmployeeRegistrationConfirmationEmail.php ✓ NEW
│   ├── Observers/
│   │   └── EmployeeObserver.php                        ✓ UPDATE
│   └── Transformers/
│       └── EmployeeRegistrationTransformer.php         ✓ NEW
├── database/
│   └── migrations/
│       └── YYYY_MM_DD_add_employee_registration_fields.php ✓ NEW
├── resources/
│   └── views/
│       └── emails/
│           └── registration-confirmation.blade.php     ✓ NEW
└── routes/
    └── EmployeeRegistration.v1.public.php              ✓ NEW

nuxt-app/ (or frontend/)
├── pages/
│   └── employee-registration.vue                       ✓ NEW
├── composables/
│   └── useEmployeeRegistration.ts                      ✓ NEW
└── nuxt.config.ts                                      ✓ UPDATE
```

---

## Skill Level Alignment

### Essential (Must Have)
- ✓ Database migration for new columns
- ✓ Laravel validation in DTO
- ✓ Data storage in database

### Intermediate
- ✓ Error handling with HTTP status codes
- ✓ Fractal transformer for API responses

### Proficient
- ✓ Observer pattern for email
- ✓ Queue job for email processing
- ✓ DTO with Laravel Data
- ✓ Custom exception handling

### Advanced
- ✓ Laravel Modules structure
- ✓ Laravel Actions for business logic
- ✓ Nuxt + Nuxt UI for frontend

---

## Common Pitfalls to Avoid

1. ❌ Don't create controllers - use Actions directly in routes
2. ❌ Don't use FormRequest - use Spatie Data DTOs
3. ❌ Don't put business logic in Observer - dispatch jobs instead
4. ❌ Don't forget to hash passwords before storing
5. ❌ Don't send emails synchronously - use queues
6. ❌ Don't expose auto-generated password in API response
7. ❌ Don't forget to update confirmation_email_sent_at after sending email
8. ❌ Don't skip the registration code validation (must be "ACME")
9. ❌ Don't forget to run queue worker during testing

---

## Success Criteria

✅ API endpoint `/v1/employee-registration` accepts POST requests
✅ Registration code validation (must be "ACME")
✅ User record created with auto-generated password
✅ Employee record created and linked to user
✅ Email queued and sent successfully
✅ `confirmation_email_sent_at` timestamp updated
✅ Proper error handling with meaningful messages
✅ Frontend form validates and submits successfully
✅ Code follows existing architectural patterns
✅ All requirements from challenge document met

---

## Timeline Estimate

- Database & Migrations: 30 min
- DTO & Action: 1-2 hours
- Email & Queue Job: 1 hour
- Observer & Exception Handling: 30 min
- Route & Testing: 30 min
- Frontend (Nuxt setup + form): 2-3 hours
- Testing & Refinement: 1 hour

**Total: 6-8 hours**

---

*This PRD serves as a comprehensive guide to implement the Employee Registration feature. Each checkbox can be marked as you complete tasks, allowing you to resume work at any time.*

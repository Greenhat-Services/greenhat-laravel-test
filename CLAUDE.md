# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel-based candidate assessment application used for evaluating developer skills through practical hands-on tasks. The codebase uses a modular architecture pattern and integrates several specialized Laravel libraries that define the development approach.

## Development Commands

### Setup
```bash
composer install
npm install  # or yarn install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

### Development Server
```bash
composer dev  # Runs server, queue, logs (pail), and vite concurrently
# Or run individually:
php artisan serve
php artisan queue:listen --tries=1
php artisan pail --timeout=0
npm run dev
```

### Testing
```bash
php artisan test           # Run all tests using Pest
php artisan test --filter TestName  # Run specific test
vendor/bin/pest            # Direct Pest command
vendor/bin/pest tests/Feature/ExampleTest.php  # Run single test file
```

### Code Quality
```bash
php artisan pint           # Format code (Laravel Pint)
php artisan ide-helper:generate  # Generate IDE helper files
```

### Build
```bash
npm run build  # Production build with Vite
```

### Database
```bash
php artisan migrate        # Run migrations
php artisan migrate:fresh --seed  # Fresh migration with seeders
php artisan db:seed        # Run seeders only
```

## Architecture Overview

### Modular Structure
The application uses **nwidart/laravel-modules** for modular architecture. Code is organized into self-contained modules in the `Modules/` directory rather than traditional Laravel structure. Each module contains:

- `app/` - Actions, Models, Policies, Observers, Transformers, DTOs
- `routes/` - Module-specific routes using custom naming convention
- `database/` - Migrations and seeders
- `resources/` - Views and emails
- `config/` - Module configuration
- `module.json` - Module metadata and service providers

**Current Modules:**
- **Auth** - Authentication system (Sanctum-based)
- **Base** - Foundation utilities, traits, middleware, settings
- **Employee** - Employee management and feedback
- **User** - User management
- **Media** - Media handling (Spatie Media Library)

### Route Organization
Routes use a unique file-based naming convention: `{ActionName}.{version}.{visibility}.php`

**Visibility types:**
- `public` - Unauthenticated API endpoints (applies JsonResponseMiddleware)
- `private` - Authenticated endpoints (applies `auth:sanctum` middleware)
- `html` - Web routes without middleware
- `webhook` - Webhook endpoints
- `signed` - Signed URL routes
- `deprecated` - Ignored by router
- `draft` - Ignored by router

**Examples:**
- `EmployeeUpdate.v1.private.php` → `/v1/employee/{id}` (authenticated)
- `EmployeeAuthenticate.v1.public.php` → `/v1/employee/authenticate` (public API)

Route names are auto-generated as `{ActionName}_{version}`.

### Actions Pattern
Business logic is implemented using **Laravel Actions** (lorisleiva/laravel-actions). Actions are single-purpose classes that:
- Contain `handle()` method for core logic
- Can act as controllers via `asController()` method
- Include `authorize()` for authorization
- Define `jsonResponse()` for API responses using Fractal transformers
- Use Data Transfer Objects (DTOs) from Spatie Laravel Data for validation

**Example structure:**
```php
class UpdateEmployee {
    use AsAction;

    public function handle(UpdateEmployeeData $data): Employee { /* ... */ }
    public function authorize(ActionRequest $request): bool { /* ... */ }
    public function asController(ActionRequest $request): Employee { /* ... */ }
    public function jsonResponse(Employee $data): Fractal { /* ... */ }
}
```

Actions are directly registered as route handlers in route files.

### Data Transfer Objects (DTOs)
Uses **Spatie Laravel Data** for type-safe data transfer between layers. DTOs:
- Validate input using `validateAndCreate()`
- Provide type safety throughout the application
- Located in `{Module}/app/Data/` directories

### API Responses
API transformations use **Spatie Laravel Fractal** for consistent response formatting:
- Transformers located in `{Module}/app/Transformers/`
- Support includes for relationships (e.g., `parseIncludes('user')`)
- Standard JSON structure for all API endpoints

### Authorization
- **Bouncer** (silber/bouncer) for role-based permissions
- Policies define authorization logic per model
- Check permissions via `$user->can('ability', $model)` in actions

### Admin Panel
**FilamentPHP** powers the admin interface:
- Resources in `app/Filament/Resources/`
- Custom components in `app/Filament/FilamentCustom/`
- Pages, actions, filters, and widgets extend base Filament classes
- Settings managed via `Modules/Base/app/Settings/GeneralSettings.php`

### Common Traits
Located in `Modules/Base/app/Traits/`:
- `ModuleRoutes` - Handles module route registration
- `HasUUID` - UUID primary keys
- `HasSlugAttribute` - Automatic slug generation (uses spatie/laravel-sluggable)
- `CamelCasing` - Convert attributes to camelCase for API responses
- `LaravelDataHelper` - Helper methods for Data DTOs
- `SlackNotifiable` - Slack notifications

### Middleware
Custom middleware in `Modules/Base/app/Http/Middleware/`:
- `JsonResponseMiddleware` - Ensures JSON responses for API endpoints
- `ProfileJsonHttpResponse` - Performance profiling for JSON responses

### Observers
Model observers handle side effects:
- `EmployeeObserver` - Employee model events
- `EmployeeFeedbackObserver` - Feedback notifications
- Registered in module EventServiceProvider

## Key Technologies

- **Laravel 12** with PHP 8.2+
- **Testing:** Pest PHP (not PHPUnit)
- **Frontend:** Vite + Tailwind CSS 4
- **Auth:** Laravel Sanctum (token-based)
- **Admin:** FilamentPHP 3
- **Node:** Version 22.11.0 (managed via Volta)
- **Package Manager:** Yarn 4.5.1

## Challenge System

Assessment challenges are in `.challenges/` directory. Candidates should:
1. Create branch: `candidate_name/challenge_name`
2. Implement solution following module patterns
3. Create PR: `[CANDIDATE] Name - Challenge Name`

## Important Notes

- Dependencies are not installed (no `vendor/` directory) - run `composer install` first
- All migrations are in module directories, not root `database/migrations/`
- Module service providers must be registered in `module.json`
- Use Actions for business logic, not fat controllers
- Always use DTOs for data validation and transfer
- Follow the route naming convention for automatic middleware application
- Filament resources should extend custom base classes in `app/Filament/FilamentCustom/`

# GoodWing Group - Complete Modernization Guide
**Target:** Laravel 13 + Vue 3 + Inertia.js SPA with professional code standards

---

## 📋 TABLE OF CONTENTS
1. [Installation & Setup](#installation--setup)
2. [Code Standards & Naming](#code-standards--naming)
3. [Architecture Patterns](#architecture-patterns)
4. [File Structure Changes](#file-structure-changes)
5. [Migration Steps](#migration-steps)

---

## 🚀 INSTALLATION & SETUP

### Phase 1: Upgrade to Laravel 13

**First, upgrade Laravel to v13 (if not already):**
```bash
composer require laravel/framework:^13.0
composer update
php artisan migrate:refresh  # After backing up data!
```

**Key Laravel 13 Breaking Changes to be aware of:**
- Stricter type hints required
- Model fillable/guarded must be explicit
- Collection methods require strict typing
- Authentication scaffolding updated
- Database serialization changes
- Middleware signatures updated

### Phase 2: Install Dependencies

**NPM Packages to Add:**
```bash
npm install @inertiajs/vue3@latest @inertiajs/core@latest vue@3 axios
npm install --save-dev @vitejs/plugin-vue @vitejs/plugin-legacy vite@latest
```

**Composer Packages to Add:**
```bash
composer require inertiajs/inertia-laravel:^0.8
composer require laravel/pint:^1.14 (for PSR-12 formatting)
composer require symfony/var-dumper:^7.0 (dev-only)
```

**Update package.json scripts:**
```json
{
    "scripts": {
        "dev": "vite",
        "build": "vite build",
        "lint": "pint",
        "lint:fix": "pint --fix"
    }
}
```

**Files to Create/Update:**
- [ ] Publish Inertia config: `php artisan inertia:install`
- [ ] Update `vite.config.js` to include Inertia + Vue plugin
- [ ] Create `resources/js/app.js` (Inertia entry point)
- [ ] Create `resources/views/app.blade.php` (root template)
- [ ] Add Inertia middleware to `app/Http/Middleware/HandleInertiaRequests.php`
- [ ] Update `app/Http/Kernel.php` middleware stack
- [ ] Create `pint.json` for code formatting rules

### Phase 3: Update Laravel 13 Configuration

**Update `.env`:**
```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=goodwing
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=cookie
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

SANCTUM_STATEFUL_DOMAINS=localhost:3000,localhost:8000
```

**Update `config/app.php` for Laravel 13:**
```php
'timezone' => 'UTC',
'locale' => 'en',
'fallback_locale' => 'en',
'faker_locale' => 'en_US',
```

**Create `pint.json`:**
```json
{
    "preset": "laravel",
    "rules": {
        "fully_qualified_strict_types": true,
        "strict_types": true
    }
}
```

---

## ⚡ LARAVEL 13 SPECIFIC REQUIREMENTS

### Strict Type Declarations
All PHP files MUST include strict types:
```php
<?php

declare(strict_types=1);

namespace App\Models;
```

### Middleware Changes
Laravel 13 updated middleware signature. Update all middleware:
```php
// OLD
public function handle($request, Closure $next) { }

// NEW - Laravel 13
public function handle(Request $request, Closure $next): Response
{
    return $next($request);
}
```

### Model Attribute Casting
Use dedicated cast classes instead of strings:
```php
// OLD
'created_at' => 'datetime'

// NEW - Laravel 13
'created_at' => 'datetime:Y-m-d H:i:s',
// Or use cast classes
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
'preferences' => AsArrayObject::class,
```

### Database Serialization
Laravel 13 defaults to strict JSON serialization. Ensure models are compatible:
```php
protected function serializeDate(DateTimeInterface $date): string
{
    return $date->format('Y-m-d H:i:s');
}
```

### Service Container Improvements
Laravel 13 has improved DI. Use automatic resolution:
```php
// Instead of binding in provider, let Laravel auto-resolve:
public function __construct(
    private ExercisePlanService $exercisePlanService,
    private AIWorkoutService $workoutService,
) {}
```

### Validation Changes
Use Validator rules as objects (new in Laravel 13):
```php
// NEW - Laravel 13 validates rules object
use Illuminate\Validation\Rules\In;
use Illuminate\Validation\Rules\Exists;

public function rules(): array
{
    return [
        'goal' => ['required', new In(['gain', 'lose', 'maintain'])],
        'fitness_level' => ['required', new In(ExercisePlan::FITNESS_LEVELS)],
    ];
}
```

---

## 🏛️ ARCHITECTURE OVERVIEW

```
Current (Amateur)          →    Professional
─────────────────────────────────────────────
Controllers with views      →    Controllers return JSON/Inertia
Direct API calls in Controller → Services layer
No validation               →    Form Requests + Validators
Direct DB queries          →    Repositories + Models
No error handling          →    Exception handling
Blade templates            →    Vue 3 Components
Snake_case everywhere      →    PascalCase/camelCase
Inconsistent naming        →    PSR-12 standards
```

---

## 📝 NAMING CONVENTIONS - MUST CHANGE

### Models (App/Models/)

| Current | Change To | Issue |
|---------|-----------|-------|
| `exercise.php` | `Exercise.php` | PSR-12: PascalCase |
| `exercise_plan.php` | `ExercisePlan.php` | PSR-12: PascalCase |
| `meal.php` | `Meal.php` | PSR-12: PascalCase |
| `nutrition_plan.php` | `NutritionPlan.php` | PSR-12: PascalCase |

### Controllers (App/Http/Controllers/)

| Current | Change To | Issue |
|---------|-----------|-------|
| `dashboard_plans.php` | `DashboardController.php` | PSR-12 + SOLID |
| `ExercisePlanController` | Keep but refactor internals | Method names wrong |
| `NutritionPlanController` | Keep but refactor internals | Method names wrong |
| `ProfileController` | Keep but refactor internals | OK naming |

### Methods (Controllers)

| Current | Change To | Reason |
|---------|-----------|--------|
| `gym_form()` | `create()` | RESTful standard |
| `store_gym()` | `store()` | RESTful standard |
| `calorie_form()` | `create()` | RESTful standard |
| `meal_store()` | `store()` | RESTful standard |
| `gym()` | `show()` | RESTful standard |
| `meal()` | `index()` | RESTful standard |

### Variables & Properties

| Current | Change To | Reason |
|---------|-----------|--------|
| `$goal` | `$goal` | OK (noun) |
| `$plan_duration_weeks` | `$planDurationWeeks` | camelCase |
| `$days_per_week` | `$daysPerWeek` | camelCase |
| `$health_conditions` | `$healthConditions` | camelCase |

---

## 🏗️ ARCHITECTURE PATTERNS TO IMPLEMENT

### 1. **Service Layer** (NEW)
```
app/Services/
├── ExercisePlanService.php      (Business logic for exercise plans)
├── NutritionPlanService.php     (Business logic for nutrition)
├── UserProfileService.php       (User-related operations)
└── External/
    ├── AIWorkoutService.php     (External API calls)
    └── NutritionAPIService.php  (External API calls)
```

**Example (Laravel 13):**
```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\WorkoutPlanDTO;
use App\Models\ExercisePlan;
use App\Models\User;
use Illuminate\Support\Collection;

class AIWorkoutService
{
    public function __construct(private readonly HttpClient $httpClient)
    {
    }

    public function generateWorkoutPlan(
        string $goal,
        string $fitnessLevel,
        array $preferences,
        int $daysPerWeek,
        int $sessionDuration,
    ): WorkoutPlanDTO {
        // External API call with error handling
        try {
            $response = $this->httpClient->post('https://api...', [
                'goal' => $goal,
                'fitness_level' => $fitnessLevel,
                'preferences' => $preferences,
            ]);

            return $this->mapResponseToDTO($response->json());
        } catch (Exception $e) {
            throw new ExternalServiceException('Failed to generate workout plan', 0, $e);
        }
    }

    private function mapResponseToDTO(array $data): WorkoutPlanDTO
    {
        return new WorkoutPlanDTO(
            id: (string) $data['id'],
            goal: $data['goal'],
            fitnessLevel: $data['fitness_level'],
            exercises: $data['exercises'],
            schedule: $data['schedule'],
            durationWeeks: (int) $data['duration_weeks'],
            createdAt: now(),
        );
    }
}
```

### 2. **Form Requests** (NEW)
```
app/Http/Requests/
├── ExercisePlan/
│   ├── StoreExercisePlanRequest.php
│   └── UpdateExercisePlanRequest.php
├── NutritionPlan/
│   ├── StoreNutritionPlanRequest.php
│   └── UpdateNutritionPlanRequest.php
├── User/
│   └── UpdateProfileRequest.php
└── Recipe/
    ├── StoreMealRequest.php
    └── UpdateMealRequest.php
```

**Example (Laravel 13):**
```php
<?php

declare(strict_types=1);

namespace App\Http\Requests\ExercisePlan;

use App\Models\ExercisePlan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\In;

class StoreExercisePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'goal' => ['required', 'string', new In(ExercisePlan::GOALS)],
            'fitnessLevel' => ['required', new In(ExercisePlan::FITNESS_LEVELS)],
            'daysPerWeek' => ['required', 'integer', 'min:1', 'max:7'],
            'sessionDuration' => ['required', 'integer', 'min:15', 'max:180'],
            'healthConditions' => ['nullable', 'array'],
            'healthConditions.*' => ['string'],
            'preferences' => ['nullable', 'array'],
            'preferences.*' => ['string'],
        ];
    }

    public function messages(): array
    {
        return [
            'goal.required' => 'Please select your fitness goal',
            'fitnessLevel.required' => 'Please select your fitness level',
            'daysPerWeek.min' => 'You must train at least 1 day per week',
        ];
    }
}
```

### 3. **Data Transfer Objects (DTOs)** (NEW)
```
app/DTOs/
├── ExercisePlanDTO.php
├── NutritionPlanDTO.php
├── WorkoutPlanDTO.php
├── MealPlanDTO.php
└── UserProfileDTO.php
```

**Example (Laravel 13):**
```php
<?php

declare(strict_types=1);

namespace App\DTOs;

use Carbon\Carbon;

final class WorkoutPlanDTO
{
    public function __construct(
        public readonly string $id,
        public readonly string $goal,
        public readonly string $fitnessLevel,
        public readonly array $exercises,
        public readonly array $schedule,
        public readonly int $durationWeeks,
        public readonly Carbon $createdAt,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) $data['id'],
            goal: $data['goal'],
            fitnessLevel: $data['fitness_level'],
            exercises: $data['exercises'],
            schedule: $data['schedule'],
            durationWeeks: (int) $data['duration_weeks'],
            createdAt: Carbon::parse($data['created_at']),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'goal' => $this->goal,
            'fitnessLevel' => $this->fitnessLevel,
            'exercises' => $this->exercises,
            'schedule' => $this->schedule,
            'durationWeeks' => $this->durationWeeks,
            'createdAt' => $this->createdAt->toIso8601String(),
        ];
    }
}
```

### 4. **API Resources** (NEW)
```
app/Http/Resources/
├── ExercisePlanResource.php
├── NutritionPlanResource.php
├── MealResource.php
├── ExerciseResource.php
└── UserResource.php
```

**Example (Laravel 13):**
```php
<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExercisePlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'goal' => $this->goal,
            'fitnessLevel' => $this->fitness_level,
            'daysPerWeek' => $this->days_per_week,
            'sessionDuration' => $this->session_duration,
            'planDurationWeeks' => $this->plan_duration_weeks,
            'exercises' => ExerciseResource::collection($this->exercises),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }

    public function with(Request $request): array
    {
        return [
            'meta' => [
                'timestamp' => now()->toIso8601String(),
            ],
        ];
    }
}
```

### 5. **Repositories** (OPTIONAL but RECOMMENDED)
```
app/Repositories/
├── BaseRepository.php
├── ExercisePlanRepository.php
├── NutritionPlanRepository.php
└── MealRepository.php
```

**Example (Laravel 13):**
```php
<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ExercisePlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ExercisePlanRepository
{
    public function __construct(private readonly ExercisePlan $model)
    {
    }

    public function getByUser(User $user): Collection
    {
        return $this->model->where('user_id', $user->id)->get();
    }

    public function findById(int $id): ?ExercisePlan
    {
        return $this->model->find($id);
    }

    public function create(array $data): ExercisePlan
    {
        return $this->model->create($data);
    }

    public function update(ExercisePlan $plan, array $data): ExercisePlan
    {
        $plan->update($data);
        return $plan->fresh();
    }

    public function delete(ExercisePlan $plan): bool
    {
        return $plan->delete();
    }
}
```

### 6. **Exceptions** (NEW)
```
app/Exceptions/
├── Handler.php (update existing)
├── InvalidWorkoutPlanException.php
├── NutritionPlanFailedException.php
└── ExternalServiceException.php
```

### 7. **Traits** (NEW - Reusable functionality)
```
app/Traits/
├── ApiResponse.php       (Consistent response format)
├── Loggable.php         (Automatic logging)
└── TimestampAware.php   (Timestamp handling)
```

---

## 📁 FILE STRUCTURE CHANGES

### Current vs. New Structure

```
CURRENT:
resources/views/
├── dashboard.blade.php
├── edit_workout.blade.php
├── show_meal.blade.php
├── show_plan.blade.php
├── auth/
├── calorie/
├── components/
├── gym/
├── layouts/
├── partials/
└── profile/

↓ CONVERTS TO ↓

NEW (Vue Components):
resources/js/Pages/
├── Dashboard.vue
├── Exercise/
│   ├── EditWorkout.vue
│   ├── CreateWorkout.vue
│   └── ShowPlan.vue
├── Nutrition/
│   ├── ShowMeal.vue
│   ├── CreateRecipe.vue
│   └── MealPlans.vue
├── Profile/
│   ├── Edit.vue
│   └── Show.vue
├── Auth/
│   ├── Login.vue
│   ├── Register.vue
│   └── ForgotPassword.vue

resources/js/Components/
├── FormInputs/
│   ├── TextInput.vue
│   ├── SelectInput.vue
│   └── ArrayInput.vue
├── Layouts/
│   ├── AppLayout.vue
│   ├── AuthLayout.vue
│   └── GuestLayout.vue
├── Shared/
│   ├── Navigation.vue
│   ├── Sidebar.vue
│   └── Footer.vue
└── Cards/
    ├── ExerciseCard.vue
    ├── MealCard.vue
    └── PlanCard.vue

resources/js/
├── app.js (Inertia entry)
├── Composables/
│   ├── useExercisePlans.js
│   ├── useNutritionPlans.js
│   └── useAuth.js
├── Services/
│   ├── api.js (Axios instance)
│   ├── exerciseService.js
│   └── nutritionService.js
└── Stores/
    ├── authStore.js (if using Pinia)
    └── plansStore.js
```

---

## ⚙️ CONTROLLER REFACTORING - BEFORE & AFTER

### Current (Bad) - ExercisePlanController.php

```php
class ExercisePlanController extends Controller {
    public function gym(Request $request, exercise_plan $routine, User $user){
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://ai-workout...",
            // Raw curl in controller ❌
        ]);
        // No validation ❌
        // No error handling ❌
        return view('show_plan'); // Returns Blade view ❌
    }
}
```

### Professional (Good) - ExercisePlanController.php

```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTOs\WorkoutPlanDTO;
use App\Exceptions\ExternalServiceException;
use App\Http\Requests\ExercisePlan\StoreExercisePlanRequest;
use App\Http\Requests\ExercisePlan\UpdateExercisePlanRequest;
use App\Http\Resources\ExercisePlanResource;
use App\Models\ExercisePlan;
use App\Services\AIWorkoutService;
use App\Services\ExercisePlanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class ExercisePlanController extends Controller
{
    public function __construct(
        private readonly ExercisePlanService $exercisePlanService,
        private readonly AIWorkoutService $workoutService,
    ) {
    }

    public function index(): Response
    {
        $plans = auth()->user()->exercisePlans()->latest()->paginate(10);

        return Inertia::render('Exercise/Index', [
            'plans' => ExercisePlanResource::collection($plans),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Exercise/CreateWorkout', [
            'fitnessLevels' => ExercisePlan::FITNESS_LEVELS,
            'goals' => ExercisePlan::GOALS,
        ]);
    }

    public function store(StoreExercisePlanRequest $request): RedirectResponse
    {
        try {
            $plan = $this->exercisePlanService->createPlanForUser(
                auth()->user(),
                $request->validated(),
            );

            Log::info('Exercise plan created', ['plan_id' => $plan->id]);

            return redirect()
                ->route('exercise-plans.show', $plan)
                ->with('success', 'Workout plan created successfully!');
        } catch (ExternalServiceException $e) {
            Log::error('Failed to create exercise plan', ['error' => $e->getMessage()]);

            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to generate plan. Please try again.']);
        }
    }

    public function show(ExercisePlan $plan): Response
    {
        $this->authorize('view', $plan);

        return Inertia::render('Exercise/ShowPlan', [
            'plan' => new ExercisePlanResource($plan),
        ]);
    }

    public function edit(ExercisePlan $plan): Response
    {
        $this->authorize('update', $plan);

        return Inertia::render('Exercise/EditWorkout', [
            'plan' => new ExercisePlanResource($plan),
            'fitnessLevels' => ExercisePlan::FITNESS_LEVELS,
            'goals' => ExercisePlan::GOALS,
        ]);
    }

    public function update(UpdateExercisePlanRequest $request, ExercisePlan $plan): RedirectResponse
    {
        $this->authorize('update', $plan);

        try {
            $plan = $this->exercisePlanService->updatePlan($plan, $request->validated());

            Log::info('Exercise plan updated', ['plan_id' => $plan->id]);

            return redirect()
                ->route('exercise-plans.show', $plan)
                ->with('success', 'Plan updated successfully!');
        } catch (ExternalServiceException $e) {
            Log::error('Failed to update exercise plan', ['error' => $e->getMessage()]);

            return redirect()->back()->withErrors(['error' => 'Failed to update plan']);
        }
    }

    public function destroy(ExercisePlan $plan): RedirectResponse
    {
        $this->authorize('delete', $plan);

        try {
            $this->exercisePlanService->deletePlan($plan);

            Log::info('Exercise plan deleted', ['plan_id' => $plan->id]);

            return redirect()
                ->route('dashboard')
                ->with('success', 'Plan deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Failed to delete exercise plan', ['error' => $e->getMessage()]);

            return redirect()->back()->withErrors(['error' => 'Failed to delete plan']);
        }
    }
}
```

---

## 🗂️ ROUTES - BEFORE & AFTER

### Current (Bad) - routes/web.php

```php
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/lift', [ExercisePlanController::class, 'gym_form'])->name('lift');
    Route::post('/gym', [ExercisePlanController::class, 'store_gym'])->name('gym');
    Route::get('/routine', [ExercisePlanController::class, 'gym'])->name('routine');
    Route::get('/recipes', [NutritionPlanController::class, 'meal'])->name('recipe');
    Route::post('/meal_plan', [NutritionPlanController::class, 'meal_store'])->name('meal');
    Route::resource('dashboard', dashboard_plans::class);
    // No API routes ❌
    // No clear RESTful pattern ❌
    // Inconsistent naming ❌
});
```

### Professional (Good) - routes/web.php

```php
// Web routes (return Inertia pages)
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Exercise Plans
    Route::resource('exercise-plans', ExercisePlanController::class);

    // Nutrition Plans
    Route::resource('nutrition-plans', NutritionPlanController::class);

    // Meals
    Route::resource('meals', MealController::class);

    // Profile
    Route::resource('profile', ProfileController::class)->only(['edit', 'update', 'destroy']);
});

require __DIR__.'/auth.php';
```

### API Routes (NEW) - routes/api.php

```php
Route::middleware(['auth:sanctum'])->group(function () {
    // Exercise Plans API
    Route::apiResource('exercise-plans', Api\ExercisePlanController::class);
    Route::post('exercise-plans/{plan}/generate', [Api\ExercisePlanController::class, 'generate']);

    // Nutrition Plans API
    Route::apiResource('nutrition-plans', Api\NutritionPlanController::class);
    Route::post('nutrition-plans/{plan}/generate', [Api\NutritionPlanController::class, 'generate']);

    // Meals API
    Route::apiResource('meals', Api\MealController::class);

    // User Profile API
    Route::get('me', [Api\UserController::class, 'show']);
    Route::patch('me', [Api\UserController::class, 'update']);
});
```

---

## 📦 MODELS - IMPROVEMENTS

### Current (Bad)

```php
class exercise_plan extends Model {
    // No constants ❌
    // No relationships defined ❌
    // No type hints ❌
    // No casts ❌
}
```

### Professional (Good) - Laravel 13 Standard

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExercisePlan extends Model
{
    use HasFactory;

    // Laravel 13: Explicit fillable is REQUIRED
    protected $fillable = [
        'user_id',
        'goal',
        'fitness_level',
        'days_per_week',
        'session_duration',
        'plan_duration_weeks',
        'preferences',
        'health_conditions',
    ];

    // Laravel 13: Type all casts
    protected $casts = [
        'preferences' => AsArrayObject::class,
        'health_conditions' => AsArrayObject::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Laravel 13: Explicit primary key type
    protected $keyType = 'int';
    public $incrementing = true;

    // Constants for validation
    final public const FITNESS_LEVELS = ['beginner', 'intermediate', 'advanced'];
    final public const GOALS = ['gain', 'lose', 'maintain'];

    // Type-hinted relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class);
    }

    // Scopes with return types
    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // Methods with return types
    public function isOwner(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public function isExpired(): bool
    {
        return $this->created_at->addWeeks($this->plan_duration_weeks)->isPast();
    }
}
```

---

## 🔐 POLICIES - AUTHORIZATION

### Current: No Policies Properly Used
```php
// app/Policies/ExercisePlanPolicy.php exists but not utilized
```

### Professional: Enforce Authorization (Laravel 13)

```php
<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\ExercisePlan;
use App\Models\User;

class ExercisePlanPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ExercisePlan $plan): bool
    {
        return $user->id === $plan->user_id;
    }

    public function create(User $user): bool
    {
        // Add subscription check if needed
        return true;
    }

    public function update(User $user, ExercisePlan $plan): bool
    {
        return $user->id === $plan->user_id;
    }

    public function delete(User $user, ExercisePlan $plan): bool
    {
        return $user->id === $plan->user_id;
    }

    public function restore(User $user, ExercisePlan $plan): bool
    {
        return $user->id === $plan->user_id;
    }

    public function forceDelete(User $user, ExercisePlan $plan): bool
    {
        return $user->id === $plan->user_id;
    }
}
```

### Usage in Controllers
```php
public function show(ExercisePlan $plan): Response
{
    $this->authorize('view', $plan); // ✅ Enforced
    return Inertia::render('Exercise/ShowPlan', [...]);
}
```

---

## 🎨 VUE COMPONENTS - EXAMPLES

### Example 1: Exercise Form Component

```vue
<!-- resources/js/Pages/Exercise/CreateWorkout.vue -->
<template>
  <AppLayout>
    <div class="max-w-2xl mx-auto">
      <h1 class="text-3xl font-bold mb-6">Create Workout Plan</h1>

      <form @submit.prevent="handleSubmit">
        <div class="mb-4">
          <label class="block text-sm font-medium">Goal</label>
          <select v-model="form.goal" class="w-full border rounded px-3 py-2">
            <option value="">Select goal...</option>
            <option value="gain">Gain Muscle</option>
            <option value="lose">Lose Weight</option>
            <option value="maintain">Maintain</option>
          </select>
          <InputError :message="form.errors.goal" />
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium">Fitness Level</label>
          <select v-model="form.fitnessLevel" class="w-full border rounded px-3 py-2">
            <option value="">Select level...</option>
            <option value="beginner">Beginner</option>
            <option value="intermediate">Intermediate</option>
            <option value="advanced">Advanced</option>
          </select>
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium">Days Per Week</label>
          <input
            v-model.number="form.daysPerWeek"
            type="number"
            min="1"
            max="7"
            class="w-full border rounded px-3 py-2"
          />
        </div>

        <button
          :disabled="form.processing"
          class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 disabled:opacity-50"
        >
          {{ form.processing ? 'Creating...' : 'Create Plan' }}
        </button>
      </form>
    </div>
  </AppLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';

const form = useForm({
  goal: '',
  fitnessLevel: '',
  daysPerWeek: 3,
  sessionDuration: 60,
  healthConditions: [],
  preferences: [],
});

const handleSubmit = () => {
  form.post(route('exercise-plans.store'));
};
</script>
```

### Example 2: Reusable Input Component

```vue
<!-- resources/js/Components/FormInputs/TextInput.vue -->
<template>
  <div class="mb-4">
    <label v-if="label" class="block text-sm font-medium text-gray-700 mb-1">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <input
      :value="modelValue"
      :type="type"
      :placeholder="placeholder"
      :required="required"
      :disabled="disabled"
      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
      @input="$emit('update:modelValue', $event.target.value)"
    />
    <InputError v-if="error" :message="error" />
  </div>
</template>

<script setup>
defineProps({
  modelValue: [String, Number],
  type: { type: String, default: 'text' },
  label: String,
  placeholder: String,
  required: Boolean,
  disabled: Boolean,
  error: String,
});

defineEmits(['update:modelValue']);
</script>
```

---

## � LARAVEL 13 + INERTIA INTEGRATION

### HandleInertiaRequests Middleware (NEW)

```php
<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
            ],
            'errors' => $this->props($request)['errors'] ?? [],
        ]);
    }
}
```

### Root Blade Template - resources/views/app.blade.php

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
        @inertiaHead
    </head>
    <body class="antialiased">
        @inertia
    </body>
</html>
```

### Vite Config - vite.config.js (Updated)

```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
});
```

### Inertia App Entry - resources/js/app.js

```js
import './bootstrap';
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import Layout from '@/Layouts/AppLayout.vue';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ).then((module) => {
            module.default.layout = module.default.layout || Layout;
            return module;
        }),
    setup({ el, App, props }) {
        createApp({ render: () => h(App, props) })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
```

---

## 🎯 BEST PRACTICES FOR LARAVEL 13

### 1. Use Readonly Properties
```php
public function __construct(
    private readonly ExercisePlanService $exercisePlanService,
    private readonly AIWorkoutService $workoutService,
) {}
```

### 2. Use Enums for Constants
```php
<?php

declare(strict_types=1);

namespace App\Enums;

enum FitnessLevel: string
{
    case BEGINNER = 'beginner';
    case INTERMEDIATE = 'intermediate';
    case ADVANCED = 'advanced';

    public function label(): string
    {
        return match($this) {
            self::BEGINNER => 'Beginner',
            self::INTERMEDIATE => 'Intermediate',
            self::ADVANCED => 'Advanced',
        };
    }
}
```

### 3. Use Validation Rules Objects
```php
use Illuminate\Validation\Rules\Enum;
use App\Enums\FitnessLevel;

public function rules(): array
{
    return [
        'fitness_level' => ['required', new Enum(FitnessLevel::class)],
    ];
}
```

### 4. Lazy Load Relations
```php
// In Model
public function exercises(): HasMany
{
    return $this->hasMany(Exercise::class)->lazy();
}
```

### 5. Use Attributes instead of Accessors
```php
<?php

use Illuminate\Database\Eloquent\Casts\Attribute;

protected function displayName(): Attribute
{
    return Attribute::make(
        get: fn () => "{$this->goal} - {$this->fitness_level}",
    );
}
```

---

## �🔄 MIGRATION STEPS (Execution Order)

### Step 1: Install & Configure (1-2 hours)
- [ ] Install Inertia packages
- [ ] Install Tailwind CSS (if not already)
- [ ] Publish Inertia config
- [ ] Create root template `resources/views/app.blade.php`
- [ ] Update Middleware stack

### Step 2: Setup Vue Infrastructure (1-2 hours)
- [ ] Create `resources/js/app.js` Inertia app
- [ ] Create `resources/js/Layouts/AppLayout.vue`
- [ ] Create base components (Buttons, Forms, etc.)
- [ ] Setup Vue Router (or use Inertia links)

### Step 3: Fix Naming (2-3 hours)
- [ ] Rename models (snake_case → PascalCase)
- [ ] Rename controllers (snake_case → PascalCase)
- [ ] Rename controller methods (custom → RESTful)
- [ ] Update all imports & references
- [ ] Update migrations if needed

### Step 4: Refactor Architecture (4-6 hours)
- [ ] Create Form Request classes
- [ ] Create Service classes
- [ ] Create DTO classes
- [ ] Create API Resource classes
- [ ] Create/Update Policies
- [ ] Setup Exception handling

### Step 5: Refactor Controllers (3-4 hours)
- [ ] ExercisePlanController
- [ ] NutritionPlanController
- [ ] ProfileController
- [ ] Create DashboardController
- [ ] Add authorization checks

### Step 6: Update Routes (1-2 hours)
- [ ] Create web routes (resource routes)
- [ ] Create API routes (if needed)
- [ ] Update route names
- [ ] Remove old custom routes

### Step 7: Build Vue Components (6-8 hours)
- [ ] Convert Blade templates → Vue
- [ ] Dashboard.vue
- [ ] Exercise Plan pages
- [ ] Nutrition Plan pages
- [ ] Profile pages
- [ ] Auth pages (may already exist)

### Step 8: Frontend Integration (3-4 hours)
- [ ] Setup API services
- [ ] Add Composables
- [ ] Add form handling
- [ ] Add validation feedback
- [ ] Setup loading states

### Step 10: Testing & Debugging (2-3 hours)
- [ ] Test all routes load correctly without errors
- [ ] Test authorization (Policies) on all CRUD operations
- [ ] Test form submissions and validation errors
- [ ] Test error handling and exception messages
- [ ] Check browser console for Vue/Inertia errors
- [ ] Test pagination on list pages
- [ ] Test file uploads (if applicable)
- [ ] Load test with multiple concurrent users

### Step 11: Polish & Deploy (1-2 hours)
- [ ] Update .env for production settings
- [ ] Run `npm run build` to minify assets
- [ ] Setup error pages: `resources/views/errors/`
- [ ] Configure logging in `config/logging.php`
- [ ] Test cache busting with assets
- [ ] Deploy to staging environment
- [ ] Run final smoke tests on staging

---

## 🎯 ESTIMATED TIMELINE
- **Total Effort:** 30-40 developer hours (includes Laravel 13 upgrade)
- **Beginner:** 40-60 hours
- **Experienced:** 25-35 hours
- **With Laravel 13 Upgrade:** Add 2-4 hours for migration and breaking change fixes

---

## ✅ QUALITY CHECKLIST

Before considering complete:
- [ ] Laravel upgraded to v13 successfully
- [ ] All models follow PascalCase naming (PSR-12)
- [ ] All models have explicit $fillable arrays
- [ ] All properties have proper type hints and return types
- [ ] All controllers use RESTful methods (index, create, store, show, edit, update, destroy)
- [ ] All files include `declare(strict_types=1);`
- [ ] All form inputs validated via Form Requests
- [ ] All external API calls in Services
- [ ] All database queries use proper models/relationships
- [ ] Authorization enforced in all controllers via Policies
- [ ] All views converted to Vue components
- [ ] Inertia.js integrated with HandleInertiaRequests middleware
- [ ] Error handling with try/catch and proper exception messages
- [ ] No raw SQL queries (use Eloquent)
- [ ] No direct CURL/HTTP calls (use Service layer)
- [ ] Consistent response format (via Resources)
- [ ] Proper logging for debugging actions
- [ ] Type hints on all methods (including return types)
- [ ] No @var comments (use type hints)
- [ ] PSR-12 coding standards applied (run `./vendor/bin/pint`)
- [ ] DRY principle applied (no code duplication)
- [ ] Laravel 13 breaking changes addressed
- [ ] Enums used for constants instead of class constants
- [ ] Readonly properties used for immutable dependencies
- [ ] Lazy-loading relationships where appropriate

---

## 📚 RESOURCES
- [Laravel 13 Upgrade Guide](https://laravel.com/docs/13.x/upgrade)
- [Laravel 13 Documentation](https://laravel.com/docs/13.x)
- [Laravel Best Practices](https://github.com/alexeymezenin/laravel-best-practices)
- [Inertia.js Documentation](https://inertiajs.com/)
- [Inertia + Vue 3 Guide](https://inertiajs.com/vue3)
- [Vue 3 Guide](https://vuejs.org/guide/)
- [PSR-12 Standard](https://www.php-fig.org/psr/psr-12/)
- [SOLID Principles](https://en.wikipedia.org/wiki/SOLID)
- [PHP Attributes (for decorators)](https://www.php.net/manual/en/language.attributes.php)
- [Laravel Enums](https://laravel.com/docs/13.x/enums)

# EPS Backend Web - Transfer of Training (TOT) Planning

## 2-Day Intensive Training Course

**Project:** EPS Backend Web System  
**Duration:** 2 Days (16 hours total)  
**Target Audience:** Backend Developers, API Developers, System Architects  
**Framework:** Laravel 10 | PHP 8.1+  
**Date:** January 7-8, 2026  
**Course Level:** Intermediate to Advanced

---

## 📑 Table of Contents

1. [Course Overview](#course-overview)
2. [Learning Objectives](#learning-objectives)
3. [Pre-requisites](#pre-requisites)
4. [Day 1: Foundation & Architecture](#day-1-foundation--architecture)
5. [Day 2: Advanced Patterns & Real-World Implementation](#day-2-advanced-patterns--real-world-implementation)
6. [Hands-On Labs](#hands-on-labs)
7. [Assessment & Evaluation](#assessment--evaluation)
8. [Resource Materials](#resource-materials)

---

## Course Overview

This intensive 2-day Transfer of Training (TOT) course is designed to equip developers with practical knowledge of the EPS Backend Web system - a complex Laravel 10 application handling 10+ business modules with 300+ models, 500+ API routes, and comprehensive audit/permission management.

### Course Highlights

✅ **Modular Architecture** - Understanding 10+ interconnected business modules  
✅ **Complex Relationships** - Working with 300+ Eloquent models  
✅ **API Design Patterns** - Building RESTful APIs with 500+ routes  
✅ **Advanced Security** - JWT, Keycloak SSO, Role-Based Access Control  
✅ **Performance Optimization** - Query optimization, caching, eager loading  
✅ **Real-World Scenarios** - Complex business logic implementation  
✅ **Hands-On Labs** - 8 practical exercises with working code

### Course Statistics

| Metric           | Value |
| ---------------- | ----- |
| Total Hours      | 16    |
| Practical Labs   | 8     |
| Code Examples    | 50+   |
| Models Covered   | 15+   |
| Key Patterns     | 12    |
| Workshops        | 4     |
| Assessment Tasks | 6     |

---

## Learning Objectives

### By the end of this course, participants will be able to:

#### **Day 1 - Foundation**

-   [ ] Understand the complete project architecture and folder structure
-   [ ] Set up and configure the development environment correctly
-   [ ] Work with 300+ Eloquent models and their relationships
-   [ ] Design and implement complex database relationships
-   [ ] Create and optimize database queries with eager loading
-   [ ] Implement API endpoints following REST conventions
-   [ ] Transform API responses using Resource classes
-   [ ] Validate input with Form Requests effectively

#### **Day 2 - Advanced**

-   [ ] Implement authentication (JWT and Keycloak SSO)
-   [ ] Design and enforce authorization with Spatie Permission
-   [ ] Implement complex business logic using Service Layer pattern
-   [ ] Handle file uploads with Spatie Media Library
-   [ ] Export data to Excel and PDF formats
-   [ ] Implement database transactions and audit logging
-   [ ] Optimize performance and avoid N+1 problems
-   [ ] Debug complex issues using appropriate tools
-   [ ] Implement event-driven architecture
-   [ ] Create async jobs for background processing

---

## Pre-requisites

### Required Knowledge

-   PHP 8.1+ fundamentals
-   Laravel 9 or 10 basics
-   Relational database concepts (SQL, normalization)
-   REST API fundamentals
-   Object-oriented programming
-   Command-line/Terminal usage

### Required Software

```bash
✓ PHP 8.1 or higher
✓ Composer
✓ MySQL/MariaDB 5.7+
✓ Visual Studio Code or IDE
✓ Git
✓ Postman/Insomnia (API testing)
✓ Redis (optional, for caching)
```

### Pre-Course Setup (To be completed before Day 1)

```bash
# 1. Clone project
git clone <repository-url>
cd eps-be-web

# 2. Install dependencies
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Configure database
# Edit .env with your database credentials
DB_DATABASE=eps_be_web
DB_USERNAME=root
DB_PASSWORD=

# 5. Run migrations
php artisan migrate

# 6. Verify setup
php artisan serve
# Test: http://localhost:8000 should be accessible
```

---

## Day 1: Foundation & Architecture

### ⏰ Duration: 8 Hours (9:00 AM - 5:00 PM with breaks)

**Focus:** Understanding the system, setting up, and building basic functionality

---

### **Session 1.1: Project Overview & Architecture (1.5 hours)**

**Time:** 9:00 AM - 10:30 AM

#### Topics Covered

1. **EPS Backend System Overview** (20 min)

    - 10 business modules
    - 300+ models ecosystem
    - 500+ API endpoints
    - Core philosophy & design patterns

2. **Layered Architecture** (20 min)

    - Routes → Controllers → Services → Models → Database
    - Separation of concerns
    - Dependency injection
    - Service container

3. **Project Folder Structure** (20 min)

    - `app/Models/` - 300+ models categorized
    - `app/Http/Controllers/Api/` - Organized by module
    - `app/Services/` - Business logic
    - `routes/api.php` - 3000+ lines of routes
    - `database/migrations/` - Schema definitions

4. **Module Overview** (30 min)
    - **Course Management** (70+ models)
    - **Exam Management** (120+ models)
    - **Facility Management** (30+ models)
    - **Inspectorate** (70+ models)
    - **User & Agency** (80+ models)
    - **System Configuration** (40+ models)

#### Learning Activities

**Activity 1.1.1: Explore Project Structure** (30 min)

```bash
# Navigate project
cd c:\Users\User\Documents\laragon\www\eps-be-web

# List key directories
ls app/Models/ | head -30        # View first 30 models
ls app/Http/Controllers/Api/    # View controllers
ls app/Services/                # View services

# Count total models
ls app/Models/ | wc -l

# View routes file size
wc -l routes/api.php
```

**Activity 1.1.2: Understand Architecture Diagram**

-   Review layered architecture diagram
-   Identify responsibilities at each layer
-   Discuss design patterns used

#### Deliverable

-   Documented project structure map
-   Architecture understanding checklist

---

### **Session 1.2: Database & Eloquent Models Deep Dive (1.5 hours)**

**Time:** 10:45 AM - 12:15 PM

#### Topics Covered

1. **Model Conventions** (20 min)

    - Table naming (plural, snake_case)
    - Primary keys (id, auto-increment)
    - Foreign keys (`{singular}_id`)
    - Timestamps (`created_at`, `updated_at`)
    - Soft deletes (`deleted_at`)

2. **Eloquent Relationships** (30 min)

    - **One-to-Many** - Course → Sessions
    - **Many-to-One** - Inverse relationships
    - **Many-to-Many** - Through pivot tables
    - **Polymorphic** - Audit logs across models
    - **Has-Many-Through** - Complex chains

3. **JSON Casting & Attributes** (20 min)

    - Storing arrays as JSON
    - Auto-encode/decode
    - Querying JSON columns
    - Type casting

4. **Model Traits & Interfaces** (15 min)
    - `HasFactory` - Testing support
    - `SoftDeletes` - Soft deletion
    - `Auditable` - Change tracking
    - `InteractsWithMedia` - File handling
    - `HasPermissions` - Permission checking

#### Learning Activities

**Activity 1.2.1: Analyze Course Model** (45 min)

```php
// File: app/Models/Course.php

// Examine:
// 1. Fillable properties
// 2. Casted attributes (especially JSON)
// 3. Relationships (BelongsTo, HasMany)
// 4. Business logic methods (getEligibility)
// 5. Auditable interface implementation
// 6. Media management

// Tasks:
// - Draw relationship diagram for Course
// - Identify all JSON columns and their usage
// - List all business logic methods
// - Understand eligibility checking logic
```

**Activity 1.2.2: Model Relationship Mapping** (45 min)

```
Create a relationship map showing:

Course
├── BelongsTo: CourseSubCategory
├── BelongsTo: User (createdBy)
├── BelongsTo: User (updatedBy)
├── HasMany: CourseSession
├── HasMany: CourseAgenda
├── HasMany: CourseNote
└── HasMany: CourseCalendar
    ├── HasMany: CourseCalendarParticipant
    │   └── BelongsTo: User
    └── HasMany: CourseCalendarSession

Draw similar maps for 3 other models
```

**Lab 1.1: Model Creation & Relationships**

**Objective:** Create a new model with relationships

```php
// Task: Create a CoursePrerequisite model
// 1. Create migration:
php artisan make:model CoursePrerequisite -m

// 2. Define table structure:
Schema::create('course_prerequisites', function (Blueprint $table) {
    $table->id();
    $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
    $table->foreignId('prerequisite_course_id')->constrained('courses')->onDelete('cascade');
    $table->integer('sequence_order')->default(1);
    $table->text('description')->nullable();
    $table->timestamps();
    $table->unique(['course_id', 'prerequisite_course_id']);
});

// 3. Create model with relationships:
php artisan make:model CoursePrerequisite

// 4. Add to model:
class CoursePrerequisite extends Model {
    public function course(): BelongsTo {
        return $this->belongsTo(Course::class);
    }

    public function prerequisiteCourse(): BelongsTo {
        return $this->belongsTo(Course::class, 'prerequisite_course_id');
    }
}

// 5. Add inverse relationship to Course:
public function prerequisites(): HasMany {
    return $this->hasMany(CoursePrerequisite::class);
}

// 6. Test relationships in Tinker:
php artisan tinker
$course = Course::find(1);
$course->prerequisites()->first(); // Test relationship
```

**Expected Output:**

-   Working model with bi-directional relationships
-   Migration file ready for execution
-   Understanding of relationship implementation

---

### **Session 1.3: Building API Endpoints & Validation (2 hours)**

**Time:** 1:00 PM - 3:00 PM (including lunch break)

#### Topics Covered

1. **REST API Design Principles** (20 min)

    - Resource-oriented design
    - HTTP methods (GET, POST, PUT, DELETE)
    - HTTP status codes
    - Request/response format consistency

2. **Route Organization** (20 min)

    - Prefix grouping by module
    - Resource controllers (apiResource)
    - Custom routes
    - Route caching

3. **Form Request Validation** (30 min)

    - Validation rules
    - Custom validation rules
    - Closure rules
    - Conditional validation
    - Custom messages

4. **API Resources & Collections** (20 min)
    - Resource transformation
    - Nested relationships
    - Conditional loading
    - Collection handling

#### Learning Activities

**Activity 1.3.1: Understand Route Organization** (30 min)

```php
// Analyze routes/api.php structure:

// Pattern 1: Simple resource
Route::apiResource('courses', CourseController::class);
// Creates: GET /courses, POST /courses, PUT /courses/{id}, DELETE /courses/{id}

// Pattern 2: Nested routes
Route::prefix('course')->group(function () {
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('calendars', CourseCalendarController::class);
    Route::post('calendars/{id}/approve', [CourseCalendarController::class, 'approve']);
});

// Pattern 3: Module-based grouping
Route::middleware('auth:api')->group(function () {
    Route::prefix('exam')->group(function () {
        Route::apiResource('calendars', ExamCalendarController::class);
        Route::apiResource('candidates', ExamCandidateController::class);
    });
});

// Tasks:
// 1. Identify all route groups
// 2. List custom routes vs resource routes
// 3. Understand middleware application
// 4. Map routes to modules
```

**Activity 1.3.2: Validation Rules Analysis** (30 min)

```php
// Analyze Form Request validation:

class StoreCourseRequest extends FormRequest {
    public function rules(): array {
        return [
            // Basic validation
            'course_name' => 'required|string|max:255',

            // Unique with conditions
            'code' => Rule::unique('courses', 'code')
                ->where('course_sub_category_id', $this->course_sub_category_id)
                ->ignore($this->course_id),

            // Foreign key constraint
            'course_sub_category_id' => 'required|exists:course_sub_categories,id',

            // Array validation
            'open_to' => 'array|min:1',
            'open_to.*' => 'integer|exists:participant_types,id',

            // Conditional validation
            'payment_closed_fee' => $this->getPaymentValidation('closed'),
        ];
    }

    private function getPaymentValidation($type) {
        return [
            'sometimes',
            'numeric',
            function ($attribute, $value, $fail) use ($type) {
                if ($this->input("payment_{$type}_requirement") && !$value) {
                    $fail("Fee required");
                }
            },
        ];
    }
}

// Tasks:
// 1. Identify simple vs complex validations
// 2. Understand conditional rules
// 3. Create own validation rules
```

**Lab 1.2: Create Complete API Endpoint**

**Objective:** Build a full CRUD endpoint from scratch

```bash
# Task: Create CourseCategory CRUD endpoint

# Step 1: Create model, migration, controller, request, resource
php artisan make:model CourseCategory -m
php artisan make:controller Api/Course/CourseCategoryController --api
php artisan make:request StoreCategoryRequest
php artisan make:resource CourseCategoryResource

# Step 2: Define migration
Schema::create('course_categories', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique();
    $table->text('description')->nullable();
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->foreignId('created_by')->constrained('users');
    $table->timestamps();
    $table->softDeletes();
});

# Step 3: Create model
class CourseCategory extends Model {
    use SoftDeletes, HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = ['name', 'description', 'status', 'created_by'];

    public function createdBy(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}

# Step 4: Create validation
class StoreCategoryRequest extends FormRequest {
    public function rules(): array {
        return [
            'name' => ['required', 'string', 'max:255',
                Rule::unique('course_categories')->ignore($this->id)],
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ];
    }
}

# Step 5: Create resource
class CourseCategoryResource extends JsonResource {
    public function toArray($request): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'created_at' => $this->created_at,
        ];
    }
}

# Step 6: Create controller
class CourseCategoryController extends Controller {
    public function __construct() {
        $this->middleware('auth:api');
        $this->middleware('permission:create-course-category')->only(['store']);
        $this->middleware('permission:edit-course-category')->only(['update']);
    }

    public function index() {
        $categories = CourseCategory::with('createdBy')
            ->when(request('search'), fn($q) =>
                $q->where('name', 'like', '%'.request('search').'%'))
            ->paginate(15);
        return CourseCategoryResource::collection($categories);
    }

    public function store(StoreCategoryRequest $request) {
        $category = CourseCategory::create([
            ...$request->validated(),
            'created_by' => auth()->id(),
        ]);
        return new CourseCategoryResource($category);
    }

    public function show(CourseCategory $category) {
        return new CourseCategoryResource($category->load('createdBy'));
    }

    public function update(StoreCategoryRequest $request, CourseCategory $category) {
        $category->update($request->validated());
        return new CourseCategoryResource($category);
    }

    public function destroy(CourseCategory $category) {
        $category->delete();
        return response()->noContent();
    }
}

# Step 7: Register routes in routes/api.php
Route::middleware('auth:api')->group(function () {
    Route::apiResource('course-categories', CourseCategoryController::class);
});

# Step 8: Test endpoints
php artisan tinker
// Test in Tinker or Postman
```

**Expected Output:**

-   Working CRUD API endpoint
-   All validation working
-   Proper resource transformation
-   Audit logging enabled

---

### **Session 1.4: Query Optimization & Relationships (1.5 hours)**

**Time:** 3:15 PM - 4:45 PM

#### Topics Covered

1. **The N+1 Problem** (20 min)

    - What causes N+1 queries
    - Performance impact
    - Detection methods

2. **Eager Loading** (20 min)

    - `with()` - Basic eager loading
    - Nested relationships
    - `withCount()` - Counting relationships
    - `withSum()`, `withAvg()` - Aggregates

3. **Query Constraints** (15 min)

    - Constraining eager loaded data
    - Filtering relationships
    - Ordering relationships

4. **Query Scopes** (15 min)
    - Local scopes for reusability
    - Scope parameters
    - Chaining scopes

#### Learning Activities

**Activity 1.4.1: Identify N+1 Problems** (30 min)

```php
// INEFFICIENT - N+1 Query Problem
// 1 query for courses + 100 queries for relationships = 101 queries!
$courses = Course::all(); // Query 1
foreach ($courses as $course) {
    echo $course->subCategory->name;      // Query 2-101 (100 queries)
    echo $course->createdBy->name;        // Query 102-201 (100 queries)
}

// EFFICIENT - Eager Loading
// 3 queries total regardless of number of courses
$courses = Course::with([
    'subCategory',
    'createdBy',
    'sessions',
    'courseCalendars.participants'
])->get();

// Tasks:
// 1. Identify N+1 issues in project
// 2. Measure query count difference
// 3. Understand performance impact
```

**Lab 1.3: Optimize Queries**

**Objective:** Convert inefficient queries to optimized versions

```php
// BEFORE: Inefficient
public function getCourseReport() {
    $courses = Course::all();
    $report = [];

    foreach ($courses as $course) {
        // This causes N+1 problem for each relationship
        $report[] = [
            'name' => $course->course_name,
            'category' => $course->subCategory->name,           // N queries
            'creator' => $course->createdBy->name,             // N queries
            'session_count' => $course->sessions()->count(),   // N queries
            'participant_count' => $course->courseCalendars()
                ->with('participants')
                ->get()
                ->sum(fn($c) => $c->participants()->count()),  // N+N queries
        ];
    }

    return $report;
}

// AFTER: Optimized with eager loading
public function getCourseReport() {
    $courses = Course::with([
        'subCategory',
        'createdBy',
        'sessions',
        'courseCalendars.participants'
    ])
    ->withCount('sessions')
    ->get();

    return $courses->map(fn($course) => [
        'name' => $course->course_name,
        'category' => $course->subCategory->name,
        'creator' => $course->createdBy->name,
        'session_count' => $course->sessions_count,
        'participant_count' => $course->courseCalendars->sum(
            fn($cal) => $cal->participants()->count()
        ),
    ]);
}

// Even better - use database aggregation
public function getCourseReportOptimized() {
    return Course::with(['subCategory', 'createdBy'])
        ->leftJoin('course_sessions', 'courses.id', '=', 'course_sessions.course_id')
        ->leftJoin('course_calendars', 'courses.id', '=', 'course_calendars.course_id')
        ->leftJoin('course_calendar_participants',
            'course_calendars.id', '=', 'course_calendar_participants.course_calendar_id')
        ->selectRaw('courses.*')
        ->selectRaw('COUNT(DISTINCT course_sessions.id) as session_count')
        ->selectRaw('COUNT(DISTINCT course_calendar_participants.id) as participant_count')
        ->groupBy('courses.id')
        ->get()
        ->map(fn($course) => [
            'name' => $course->course_name,
            'category' => $course->subCategory->name,
            'creator' => $course->createdBy->name,
            'session_count' => $course->session_count,
            'participant_count' => $course->participant_count,
        ]);
}

// Task: Benchmark all three approaches
```

**Expected Output:**

-   Query count reduced from 300+ to 3-5 queries
-   Performance significantly improved
-   Understanding of eager loading strategies

---

### **Session 1.5: Wrap-up & Q&A (1 hour)**

**Time:** 4:45 PM - 5:45 PM

-   Recap Day 1 learning
-   Q&A session
-   Assignment preview for Day 2
-   Hands-on lab review

---

## Day 2: Advanced Patterns & Real-World Implementation

### ⏰ Duration: 8 Hours (9:00 AM - 5:00 PM with breaks)

**Focus:** Complex business logic, security, performance, and real-world scenarios

---

### **Session 2.1: Authentication & Authorization (1.5 hours)**

**Time:** 9:00 AM - 10:30 AM

#### Topics Covered

1. **JWT Authentication** (30 min)

    - Token generation and validation
    - Token refresh strategy
    - Claims and payload
    - Expiration handling

2. **Keycloak SSO Integration** (20 min)

    - SSO configuration
    - User synchronization
    - Token validation

3. **Role-Based Access Control (RBAC)** (30 min)

    - Spatie Permission package
    - Roles and permissions
    - Permission checking
    - Policy classes

4. **Authorization in Controllers** (20 min)
    - Middleware-based authorization
    - Policy-based authorization
    - Custom authorization logic

#### Learning Activities

**Activity 2.1.1: JWT Flow Analysis** (30 min)

```php
// Understand JWT authentication flow:

// Step 1: User Login
POST /api/auth/login
{
    "email": "user@example.com",
    "password": "password"
}

// Response:
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "Bearer",
    "expires_in": 3600
}

// Step 2: Use token in requests
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGc...

// Step 3: Token refresh
POST /api/auth/refresh
Authorization: Bearer <old_token>

// Step 4: Logout
POST /api/auth/logout

// Tasks:
// 1. Understand token structure
// 2. Review JWT configuration
// 3. Test token lifecycle
```

**Activity 2.1.2: Permission System Mapping** (30 min)

```php
// Map permission structure:

User -> Roles -> Permissions -> Actions

// Example structure:
User: John (ID: 1)
  ├── Role: Course Manager
  │   ├── Permission: create-course
  │   ├── Permission: edit-course
  │   ├── Permission: view-course
  │   └── Permission: delete-course
  │
  └── Role: Finance Manager
      ├── Permission: create-payment-claim
      ├── Permission: approve-payment-claim
      └── Permission: view-payment-report

// How it's used:
if (auth()->user()->can('create-course')) {
    // Allow course creation
}

// Tasks:
// 1. List all roles in system
// 2. List all permissions per role
// 3. Understand permission hierarchy
```

**Lab 2.1: Implement Role-Based Access Control**

**Objective:** Create a permission-protected endpoint

```php
// Task: Protect course approval with specific permission

// Step 1: Define permission in system
// In seeder or migration:
Permission::findOrCreate('approve-course');
Permission::findOrCreate('reject-course');

// Step 2: Assign to role
$role = Role::firstOrCreate(['name' => 'course-approver']);
$role->givePermissionTo(['approve-course', 'reject-course']);

// Step 3: Assign role to user
$user->assignRole('course-approver');

// Step 4: Create controller with authorization
class CourseApprovalController extends Controller {
    public function approve(Request $request, Course $course) {
        // Method 1: Check permission directly
        if (!auth()->user()->can('approve-course')) {
            return response()->json(
                ['message' => 'Not authorized'],
                403
            );
        }

        // Method 2: Using middleware
        $this->middleware('permission:approve-course');

        // Method 3: Using policy
        $this->authorize('approve', $course);
    }
}

// Step 5: Create policy
class CoursePolicy {
    public function approve(User $user, Course $course): bool {
        return $user->can('approve-course')
            && in_array($course->status, ['submitted', 'pending']);
    }
}

// Step 6: Apply in routes
Route::middleware('auth:api')
    ->middleware('permission:approve-course')
    ->post('/courses/{id}/approve', [CourseApprovalController::class, 'approve']);

// Step 7: Test with Postman
// Test with user having permission - should work
// Test with user without permission - should get 403
```

**Expected Output:**

-   Working authentication system
-   Permission checking implemented
-   Authorization policies in place

---

### **Session 2.2: Service Layer & Complex Business Logic (1.5 hours)**

**Time:** 10:45 AM - 12:15 PM

#### Topics Covered

1. **Service Layer Pattern** (25 min)

    - Separation of concerns
    - Business logic encapsulation
    - Reusability across endpoints

2. **Database Transactions** (20 min)

    - Multi-step operations
    - Atomicity and rollback
    - Error handling in transactions

3. **Complex Validation Logic** (20 min)

    - Custom validation methods
    - Business rule validation
    - State-based validation

4. **Event-Driven Architecture** (20 min)
    - Events and listeners
    - Decoupling side effects
    - Async event handling

#### Learning Activities

**Activity 2.2.1: Service Layer Analysis** (30 min)

```php
// Analyze CourseParticipantService complex operation:

public function registerParticipant($userId, $courseCalendarId) {
    return DB::transaction(function () use ($userId, $courseCalendarId) {
        // Step 1: Validate eligibility
        $this->validateEligibility($userId, $courseCalendarId);

        // Step 2: Create participant record
        $participant = CourseCalendarParticipant::create([...]);

        // Step 3: Initialize attendance records
        $this->initializeAttendance($participant);

        // Step 4: Send notification
        $this->notifyParticipantRegistered($participant);

        // Step 5: Clear cache
        cache()->forget('course_participants_' . $courseCalendarId);

        return $participant;
    });
}

// Multi-step process benefits:
// 1. Validation before creation
// 2. Atomic operations (all or nothing)
// 3. Related data initialization
// 4. Side effects (notifications, cache)
// 5. Clear error handling

// Tasks:
// 1. Identify similar multi-step processes
// 2. Understand transaction benefits
// 3. Review error handling patterns
```

**Activity 2.2.2: Event Flow Analysis** (30 min)

```php
// Understand event-driven architecture:

// 1. Model dispatches event when created
class CourseCalendarParticipant extends Model {
    protected $dispatchesEvents = [
        'created' => CourseRegistrationCompleted::class,
    ];
}

// 2. Event is defined
class CourseRegistrationCompleted {
    public function __construct(public CourseCalendarParticipant $participant) {}
}

// 3. Listener handles event
class SendCourseRegistrationNotification {
    public function handle(CourseRegistrationCompleted $event) {
        // Send email
        Mail::to($event->participant->user->email)
            ->send(new CourseRegistrationMail($event->participant));

        // Log activity
        activity()->log('Registered for course');
    }
}

// Benefits:
// - Model doesn't know about notifications
// - Easy to add new listeners
// - Can run async
// - Testable independently

// Tasks:
// 1. Identify events in system
// 2. Understand listener responsibilities
// 3. Trace event flow
```

**Lab 2.2: Create Complex Business Service**

**Objective:** Implement multi-step business operation with validation

```php
// Task: Implement Course Calendar Completion Service
// Requirements:
// 1. Validate all participants have attendance records
// 2. Calculate final grades
// 3. Generate certificates
// 4. Send notifications
// 5. Update statistics
// 6. All atomic (succeed or fail together)

// Create service
namespace App\Services;

class CourseCompletionService {
    public function completeCourse(CourseCalendar $calendar): CourseCalendar {
        return DB::transaction(function () use ($calendar) {
            // Step 1: Validate completion prerequisites
            if (!$this->canComplete($calendar)) {
                throw new \Exception("Cannot complete course yet");
            }

            // Step 2: Process all participants
            $calendar->participants->each(function ($participant) use ($calendar) {
                $this->processParticipant($participant, $calendar);
            });

            // Step 3: Update calendar status
            $calendar->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            // Step 4: Dispatch completion event
            event(new CourseCompleted($calendar));

            // Step 5: Clear related caches
            cache()->tags(['course-'.$calendar->course_id])->flush();

            return $calendar;
        });
    }

    private function canComplete(CourseCalendar $calendar): bool {
        // All sessions completed
        if ($calendar->course->sessions()->where('status', '!=', 'completed')->exists()) {
            return false;
        }

        // All participants have attendance records
        $participantCount = $calendar->participants()->count();
        $attendanceCount = $calendar->participants()
            ->whereHas('attendances')
            ->count();

        return $participantCount === $attendanceCount;
    }

    private function processParticipant(CourseCalendarParticipant $participant,
                                       CourseCalendar $calendar): void {
        // Calculate grade based on attendance
        $attendancePercentage = $participant->attendances()
            ->where('status', 'present')
            ->count() / $participant->attendances()->count() * 100;

        // Determine if passed
        $passed = $attendancePercentage >= $calendar->course->attendance_percentage;

        // Update participant status
        $participant->update([
            'status' => $passed ? 'completed' : 'failed',
            'attendance_percentage' => $attendancePercentage,
            'completed_at' => now(),
        ]);

        // Generate certificate if passed
        if ($passed) {
            $this->generateCertificate($participant);
        }

        // Send notification
        Mail::to($participant->user->email)
            ->send(new CourseCompletionMail($participant, $passed));
    }

    private function generateCertificate(CourseCalendarParticipant $participant): void {
        // Generate PDF certificate
        // Store in media library
        // Send to participant
    }
}

// Use service in controller
class CourseCompletionController extends Controller {
    public function __construct(private CourseCompletionService $service) {}

    public function complete(CourseCalendar $calendar) {
        try {
            $completed = $this->service->completeCourse($calendar);
            return new CourseCalendarResource($completed);
        } catch (\Exception $e) {
            return response()->json(
                ['message' => $e->getMessage()],
                422
            );
        }
    }
}

// Test service
php artisan tinker
$calendar = CourseCalendar::find(1);
app(CourseCompletionService::class)->completeCourse($calendar);
```

**Expected Output:**

-   Multi-step service handling complete workflow
-   Transaction ensuring atomicity
-   Validation before operations
-   Proper error handling

---

### **Session 2.3: File Management & Data Export (1.5 hours)**

**Time:** 1:00 PM - 2:30 PM (including lunch break)

#### Topics Covered

1. **Spatie Media Library** (25 min)

    - Media collections
    - File validation
    - URL generation
    - Responsive images

2. **Excel Export with Maatwebsite** (20 min)

    - FromQuery interface
    - Data mapping
    - Formatting and styling
    - Performance optimization

3. **PDF Generation** (20 min)

    - DomPDF integration
    - Template rendering
    - Watermarking
    - Custom formatting

4. **Streaming Large Datasets** (20 min)
    - Memory-efficient exports
    - Chunking data
    - Progress tracking

#### Learning Activities

**Activity 2.3.1: File Upload Flow** (30 min)

```php
// Understand Spatie Media Library workflow:

// 1. Model implements HasMedia
class Course extends Model implements HasMedia {
    use InteractsWithMedia;

    public function registerMediaCollections(): void {
        // Define collection for thumbnails
        $this->addMediaCollection('thumbnail')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->onlyKeepLatest(1);

        // Define collection for course materials
        $this->addMediaCollection('course-materials')
            ->acceptsMimeTypes([
                'application/pdf',
                'application/msword',
                'application/vnd.ms-excel'
            ])
            ->useFallbackUrl('/images/default.png');
    }
}

// 2. Upload file
$course->addMedia($request->file('thumbnail'))
    ->toMediaCollection('thumbnail');

// 3. Retrieve files
$url = $course->getFirstMediaUrl('thumbnail');
$files = $course->getMedia('course-materials');

// 4. File properties
foreach ($files as $media) {
    echo $media->name;              // Original filename
    echo $media->file_name;         // Stored filename
    echo $media->size;              // Size in bytes
    echo $media->getMimeType();     // MIME type
    echo $media->getUrl();          // Full URL
}

// Tasks:
// 1. Review media collections in Course model
// 2. Understand collection registration
// 3. Trace upload/download process
```

**Lab 2.3: Create Excel Export with Custom Formatting**

**Objective:** Export course data to Excel with calculations

```php
// Task: Export all courses with session count, participant count,
// and completion percentage

// Step 1: Create export class
namespace App\Exports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CoursesExport implements FromQuery, WithHeadings, WithMapping,
                                WithColumnFormatting, ShouldAutoSize {

    public function query() {
        return Course::query()
            ->with(['subCategory', 'createdBy', 'sessions', 'courseCalendars'])
            ->where('status', 'active');
    }

    public function headings(): array {
        return [
            'ID',
            'Code',
            'Course Name',
            'Category',
            'Creator',
            'Sessions',
            'Participants',
            'Completion %',
            'Created Date',
        ];
    }

    public function map($course): array {
        $participants = $course->courseCalendars()
            ->with('participants')
            ->get()
            ->sum(fn($cal) => $cal->participants()->count());

        $completed = $course->courseCalendars()
            ->with('participants')
            ->get()
            ->sum(fn($cal) => $cal->participants()
                ->where('status', 'completed')
                ->count());

        $completionPercentage = $participants > 0
            ? ($completed / $participants) * 100
            : 0;

        return [
            $course->id,
            $course->code,
            $course->course_name,
            $course->subCategory->name,
            $course->createdBy->name,
            $course->sessions()->count(),
            $participants,
            round($completionPercentage, 2) . '%',
            $course->created_at->format('Y-m-d'),
        ];
    }

    public function columnFormats(): array {
        return [
            'H' => '0.00"%"',  // Format completion % as percentage
            'I' => 'yyyy-mm-dd', // Format date
        ];
    }
}

// Step 2: Create controller method
class ReportController extends Controller {
    public function exportCourses() {
        return Excel::download(
            new CoursesExport(),
            'courses_' . now()->format('Y-m-d_H-i-s') . '.xlsx'
        );
    }
}

// Step 3: Add route
Route::get('/reports/courses/export', [ReportController::class, 'exportCourses']);

// Step 4: Test
// Visit: http://localhost:8000/api/reports/courses/export
```

**Expected Output:**

-   Excel file with formatted data
-   Custom calculations included
-   Proper column formatting

---

### **Session 2.4: Performance Optimization & Debugging (1.5 hours)**

**Time:** 2:45 PM - 4:15 PM

#### Topics Covered

1. **Query Analysis & Debugging** (20 min)

    - Query logging
    - Query analysis tools
    - Execution plans

2. **Caching Strategies** (20 min)

    - Cache tags
    - Cache invalidation
    - Cache-aside pattern

3. **Database Indexing** (15 min)

    - Index types
    - Index selection
    - Performance impact

4. **Monitoring & Profiling** (20 min)
    - Tools and techniques
    - Common bottlenecks
    - Optimization strategies

#### Learning Activities

**Activity 2.4.1: Query Debugging Session** (30 min)

```php
// Enable query logging

// Method 1: In controller
DB::listen(function ($query) {
    Log::debug($query->sql);
    Log::debug($query->bindings);
    Log::debug('Execution time: ' . $query->time . 'ms');
});

// Method 2: In config/app.php
'debug' => true

// Method 3: Using Laravel Debugbar (dev dependency)
composer require barryvdh/laravel-debugbar --dev

// View queries in browser
// http://localhost:8000 -> Bottom bar shows all queries

// Method 4: Using tinker
php artisan tinker
DB::enableQueryLog();
$courses = Course::with('subCategory')->get();
dd(DB::getQueryLog());

// Analyze output
// Count total queries - should minimize
// Check execution time - avoid slow queries
// Look for N+1 patterns - use eager loading
```

**Lab 2.4: Implement Caching Strategy**

**Objective:** Add caching to frequently accessed data

```php
// Task: Cache course categories and invalidate on update

// Step 1: Get with caching
public function getCategories() {
    return cache()->remember(
        'course_categories_all',
        now()->addHours(24),
        fn() => CourseCategory::with('createdBy')->get()
    );
}

// Step 2: Invalidate on update
public function updateCategory(CourseCategory $category, Request $request) {
    $category->update($request->validated());

    // Invalidate cache
    cache()->forget('course_categories_all');
    cache()->tags(['course-categories'])->flush();

    return new CourseCategoryResource($category);
}

// Step 3: Use cache tags for granular control
public function getCoursesByCategory($categoryId) {
    return cache()->tags(['course-categories', "category-{$categoryId}"])
        ->remember(
            "courses_category_{$categoryId}",
            now()->addHours(12),
            fn() => CourseCategory::find($categoryId)
                ->courses()
                ->with('createdBy')
                ->get()
        );
}

// Step 4: Flush category-specific cache
public function updateCourse(Course $course, Request $request) {
    $oldCategory = $course->course_sub_category_id;

    $course->update($request->validated());

    // Flush caches for old and new category
    cache()->tags(['course-categories', "category-{$oldCategory}"])->flush();
    cache()->tags(['course-categories', "category-{$course->course_sub_category_id}"])->flush();
}

// Benefits:
// - Reduced database queries
// - Faster response times
// - Smart invalidation strategies
```

**Expected Output:**

-   Query count monitoring
-   Caching implementation
-   Performance metrics improvement

---

### **Session 2.5: Advanced Patterns & Best Practices (1.5 hours)**

**Time:** 4:30 PM - 5:45 PM

#### Topics Covered

1. **Polymorphic Relationships** (20 min)

    - MorphTo/MorphMany
    - Audit logging pattern
    - Comments/ratings on multiple models

2. **Repository Pattern** (20 min)

    - Query encapsulation
    - Reusable queries
    - Testability

3. **Observers for Side Effects** (15 min)

    - Model observers
    - Lifecycle hooks
    - Decoupling logic

4. **Testing Strategies** (20 min)
    - Unit testing models
    - Feature testing APIs
    - Mocking and factories

#### Learning Activities

**Activity 2.5.1: Audit Logging with Polymorphic** (30 min)

```php
// Understand how audit logging uses polymorphic relationships:

// 1. Single audit table for ALL models
Schema::create('audit_logs', function (Blueprint $table) {
    $table->id();
    $table->string('auditable_type');  // Model class name
    $table->unsignedBigInteger('auditable_id');  // Model ID
    $table->foreignId('user_id')->nullable();  // Who made change
    $table->string('event');  // created, updated, deleted
    $table->json('old_values')->nullable();  // Before values
    $table->json('new_values')->nullable();  // After values
    $table->timestamps();

    $table->index(['auditable_type', 'auditable_id']);
});

// 2. Model implements Auditable
class Course extends Model implements Auditable {
    use \OwenIt\Auditing\Auditable;
}

// 3. Every change is logged
$course = Course::find(1);
$course->update(['course_name' => 'New Name']);
// Automatically creates audit log entry

// 4. Query audit history
$audits = $course->audits()->get();
foreach ($audits as $audit) {
    echo $audit->user->name;  // Who made change
    echo $audit->event;       // created/updated/deleted
    echo $audit->old_values;  // Previous data
    echo $audit->new_values;  // New data
}

// 5. Get all audits across models
AuditLog::where('auditable_type', 'App\\Models\\Course')
    ->where('auditable_id', 1)
    ->get();

// Benefits:
// - Complete change tracking
// - Compliance & audit trail
// - Single table for all models
// - Polymorphic elegance
```

**Lab 2.5: Create Observer for Automatic Actions**

**Objective:** Implement observer for automatic notifications

```php
// Task: Create observer to send notification when course is created

// Step 1: Create observer
php artisan make:observer CourseObserver --model=Course

// Step 2: Implement observer
namespace App\Observers;

use App\Models\Course;
use App\Mail\CourseCreatedMail;
use Illuminate\Support\Facades\Mail;

class CourseObserver {
    public function created(Course $course): void {
        // Send notification to admins
        Mail::to(config('mail.admin_address'))
            ->send(new CourseCreatedMail($course));

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($course)
            ->log('Course created');

        // Clear category cache
        cache()->tags(['course-categories',
            "category-{$course->course_sub_category_id}"])->flush();
    }

    public function updated(Course $course): void {
        // Log changes
        activity()
            ->causedBy(auth()->user())
            ->performedOn($course)
            ->withProperties($course->getChanges())
            ->log('Course updated');

        // Clear caches
        cache()->forget('course_'.$course->id);
    }

    public function deleted(Course $course): void {
        // Clean up related data
        $course->sessions()->delete();
        $course->agendas()->delete();

        // Log deletion
        activity()
            ->causedBy(auth()->user())
            ->performedOn($course)
            ->log('Course deleted');
    }
}

// Step 3: Register observer in AppServiceProvider
namespace App\Providers;

use App\Models\Course;
use App\Observers\CourseObserver;

class AppServiceProvider extends ServiceProvider {
    public function boot(): void {
        Course::observe(CourseObserver::class);
    }
}

// Step 4: Test observer
php artisan tinker
$course = Course::create([...]);
// Observer should automatically send email, log activity, clear cache
```

**Expected Output:**

-   Automatic notifications sent
-   Activity logging working
-   Cache invalidation automatic

---

## Hands-On Labs Summary

### Lab Completion Checklist

| Lab | Title                          | Duration | Status |
| --- | ------------------------------ | -------- | ------ |
| 1.1 | Model Creation & Relationships | 30 min   |        |
| 1.2 | Complete API Endpoint Creation | 45 min   |        |
| 1.3 | Query Optimization             | 45 min   |        |
| 2.1 | Role-Based Access Control      | 45 min   |        |
| 2.2 | Complex Business Service       | 45 min   |        |
| 2.3 | Excel Export with Formatting   | 45 min   |        |
| 2.4 | Caching Strategy               | 45 min   |        |
| 2.5 | Observer Implementation        | 45 min   |        |

---

## Assessment & Evaluation

### Daily Assessments

**Day 1 Assessment:**

-   [ ] Model relationship diagram created correctly
-   [ ] CRUD endpoint fully functional
-   [ ] Query optimization implemented and tested
-   [ ] All validation working as expected

**Day 2 Assessment:**

-   [ ] Authentication/authorization correctly implemented
-   [ ] Multi-step service working atomically
-   [ ] Excel export with formatting complete
-   [ ] Observer automatically handling side effects

### Final Project Assignment

**Project:** Build Complete Module with All Learned Concepts

**Requirements:**

```
Create a "CourseApproval" module with:

1. Database
   - Create migration for approval workflow
   - Define relationships with Course model
   - Add soft deletes and timestamps

2. Models
   - Create CourseApproval model
   - Implement Auditable interface
   - Define relationships

3. API
   - Create complete CRUD endpoints
   - Implement permission-based access control
   - Add custom approval/rejection actions
   - Return data via resources

4. Business Logic
   - Implement multi-step approval process
   - Create service with transactions
   - Handle all validation rules

5. Notifications
   - Send email on approval
   - Send email on rejection
   - Log all activities

6. Testing
   - Create unit tests
   - Create API feature tests
   - Test authorization policies

7. Performance
   - Optimize all queries
   - Implement caching
   - Use eager loading

Expected completion time: 3-4 hours
```

### Evaluation Criteria

| Criterion                | Points  |
| ------------------------ | ------- |
| Code Quality & Standards | 20      |
| Functionality            | 25      |
| Documentation            | 15      |
| Performance Optimization | 15      |
| Security Implementation  | 15      |
| Testing Coverage         | 10      |
| **Total**                | **100** |

---

## Resource Materials

### Provided Documents

1. **HANDS_ON_TRAINING_GUIDE.md**

    - Comprehensive reference guide
    - 50+ code examples
    - Best practices
    - Common issues & solutions

2. **This TOT Planning Document**
    - Course structure
    - Learning objectives
    - Labs with solutions
    - Assessment criteria

### External Resources

#### Official Documentation

-   [Laravel 10 Documentation](https://laravel.com/docs/10.x)
-   [Eloquent ORM](https://laravel.com/docs/10.x/eloquent)
-   [API Design](https://laravel.com/docs/10.x/routing)
-   [Authentication](https://laravel.com/docs/10.x/authentication)

#### Package Documentation

-   [Spatie Permission](https://spatie.be/docs/laravel-permission/v5)
-   [Owen-it Auditing](https://github.com/owen-it/laravel-auditing)
-   [Spatie Media Library](https://spatie.be/docs/laravel-medialibrary/v10)
-   [Maatwebsite Excel](https://docs.laravel-excel.com/)
-   [JWT Auth](https://github.com/tymondesigns/jwt-auth)

#### Tools & Utilities

-   **Postman** - API testing
-   **Laravel Debugbar** - Query debugging
-   **Laravel Tinker** - Interactive shell
-   **PHPStan** - Static analysis
-   **Laravel Pint** - Code formatting

### Sample Project Files

The following files should be reviewed:

-   `HANDS_ON_TRAINING_GUIDE.md` - Complete reference
-   `app/Models/Course.php` - Complex model example
-   `routes/api.php` - Route organization
-   `app/Http/Requests/` - Validation examples
-   `app/Http/Resources/` - Resource transformation
-   `app/Services/` - Service layer examples
-   `database/migrations/` - Database schema

---

## Pre-Course Preparation Checklist

**One Week Before:**

-   [ ] Ensure all systems are set up
-   [ ] Clone and configure project
-   [ ] Run migrations successfully
-   [ ] Test API with Postman
-   [ ] Review HANDS_ON_TRAINING_GUIDE.md

**One Day Before:**

-   [ ] Verify development environment
-   [ ] Test Tinker functionality
-   [ ] Prepare IDE (VS Code settings)
-   [ ] Download sample API collection for Postman
-   [ ] Test database connectivity

**Morning of Day 1:**

-   [ ] Start development server
-   [ ] Verify API is responding
-   [ ] Have Postman open and ready
-   [ ] Have documentation accessible
-   [ ] Test authentication

---

## Post-Course Followup

### Week 1 After Training

-   Review all labs and assignments
-   Start working on final project
-   Ask questions in designated channel
-   Share code examples

### Week 2-3 After Training

-   Complete final project assignment
-   Conduct peer code reviews
-   Document learnings
-   Share best practices with team

### Week 4+ After Training

-   Mentor new team members
-   Contribute to project improvements
-   Document advanced patterns
-   Share performance optimizations

---

## Course Materials Checklist

**Before Training Starts:**

-   [ ] HANDS_ON_TRAINING_GUIDE.md (Reference document)
-   [ ] TOT_PLANNING.md (This document)
-   [ ] Sample project code
-   [ ] Postman API collection
-   [ ] Database backup (for reset if needed)
-   [ ] Access to development server

**During Training:**

-   [ ] Code editor/IDE
-   [ ] Terminal for running commands
-   [ ] Postman for API testing
-   [ ] MySQL/Database client
-   [ ] Documentation browser

**After Training:**

-   [ ] Session recordings (if available)
-   [ ] All lab solutions
-   [ ] Final project starter template
-   [ ] Troubleshooting guide

---

## Trainer/Facilitator Notes

### Session Management Tips

1. **Pacing**

    - Allow 5 min buffer between sessions
    - Adjust timing based on class progress
    - Don't rush through labs

2. **Labs**

    - Code along with participants
    - Pause for questions
    - Show common mistakes
    - Demonstrate debugging

3. **Engagement**

    - Encourage hands-on practice
    - Ask questions to check understanding
    - Use real project examples
    - Share personal experiences

4. **Troubleshooting**
    - Be prepared for setup issues
    - Have working examples ready
    - Document issues for improvement
    - Provide alternative solutions

### Common Questions & Answers

**Q: Can I skip certain sections?**
A: Not recommended. Each section builds on previous knowledge. However, if time is limited, prioritize Day 1.

**Q: What if I don't have a development environment?**
A: Cloud-based IDEs (Gitpod, Replit) can be used as temporary solution.

**Q: How long will it take to master this?**
A: 2 days covers fundamentals and advanced patterns. Real mastery requires 4-6 weeks of practice.

**Q: Can I apply this to other Laravel projects?**
A: Yes! These patterns and practices apply to most Laravel applications.

---

## Success Metrics

### Training Success Indicators

-   ✅ All 8 labs completed successfully
-   ✅ Participants can explain architecture
-   ✅ Code follows best practices
-   ✅ Performance optimization concepts understood
-   ✅ Security practices correctly applied
-   ✅ Final project meets requirements
-   ✅ 80%+ quiz score
-   ✅ Positive feedback survey

### Post-Training Success Indicators

-   ✅ Participants can work independently on similar tasks
-   ✅ Code quality improved in pull requests
-   ✅ Fewer database performance issues
-   ✅ Better API design implementation
-   ✅ Proper security implementation
-   ✅ Mentoring new team members

---

## Appendix: Quick Reference

### Essential Artisan Commands

```bash
php artisan make:model ModelName -m           # Create model with migration
php artisan make:controller Api/NameController --api  # API controller
php artisan make:request StoreNameRequest     # Form request validation
php artisan make:resource NameResource        # API resource
php artisan make:job JobName                  # Background job
php artisan make:observer ModelObserver --model=Model

php artisan migrate                           # Run migrations
php artisan migrate:rollback                  # Rollback migrations
php artisan db:seed                          # Run seeders

php artisan cache:clear                      # Clear cache
php artisan config:cache                     # Cache config
php artisan route:cache                      # Cache routes

php artisan tinker                          # Interactive shell
php artisan test                           # Run tests
php artisan pint                           # Format code
```

### Essential Concepts Checklist

-   [ ] Eloquent model relationships
-   [ ] Query eager loading
-   [ ] Form request validation
-   [ ] API resources
-   [ ] Service layer pattern
-   [ ] Database transactions
-   [ ] JWT authentication
-   [ ] Role-based access control
-   [ ] File uploads
-   [ ] Data export (Excel, PDF)
-   [ ] Caching strategies
-   [ ] Query optimization
-   [ ] Event observers
-   [ ] Unit testing
-   [ ] API testing

---

**Course Version:** 1.0  
**Last Updated:** January 7, 2026  
**Duration:** 2 Days | 16 Hours | 8 Labs | 50+ Examples  
**Target:** Backend Developers & System Architects

---

## Document History

| Version | Date        | Changes         |
| ------- | ----------- | --------------- |
| 1.0     | Jan 7, 2026 | Initial release |

---

**For Questions or Feedback:** Refer to course facilitator or project documentation.

This comprehensive TOT planning provides a structured 2-day training path covering all critical and complex operations of the EPS Backend Web system, with hands-on labs, real-world examples, and assessment criteria.

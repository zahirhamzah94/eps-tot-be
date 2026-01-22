# Code Samples in EPS TOT Training Presentation

## Overview

The PowerPoint presentation has been successfully enhanced with 9 embedded code sample slides throughout the 44-slide training course. These code samples provide practical, hands-on examples directly integrated at the most relevant conceptual points.

## Presentation Details

-   **File:** EPS_TOT_Training_2Days.pptx
-   **Total Slides:** 44 (35 content + 9 code samples)
-   **File Size:** 80.3 KB
-   **Code Samples:** 9 integrated slides

## Code Samples by Topic

### 1. **Slide 10.5 - Eloquent Relationships Code**

**Topic:** Database relationships and eager loading
**Code Examples:**

-   HasMany relationships definition
-   JSON casting for array attributes
-   Eager loading with `with()` method
-   Query optimization patterns

```php
// HasMany relationship example
public function courses() {
    return $this->hasMany(Course::class);
}
```

### 2. **Slide 11.5 - API Controllers & Validation Code**

**Topic:** Building RESTful API endpoints
**Code Examples:**

-   CourseCategoryController store() method
-   Form validation with FormRequest
-   Resource creation and response formatting
-   Error handling patterns

```php
// Store method with validation
public function store(StoreCategoryRequest $request) {
    $category = CourseCategory::create($request->validated());
    return new CourseCategoryResource($category);
}
```

### 3. **Slide 13.5 - Lab 1.2 Complete Model Example**

**Topic:** Complex model with relationships
**Code Examples:**

-   Model with multiple relationships
-   Attribute accessors/mutators
-   Hidden attributes for API responses
-   Relationship definitions

```php
// Complete model example from Lab 1.2
class Course extends Model {
    use HasFactory;

    protected $fillable = ['name', 'code', 'category_id', 'status'];
    protected $hidden = ['created_at', 'updated_at'];

    public function category() {
        return $this->belongsTo(CourseCategory::class);
    }
}
```

### 4. **Slide 14.5 - Query Optimization (N+1 Problem)**

**Topic:** Preventing N+1 queries and optimization
**Code Examples:**

-   Inefficient query patterns
-   Eager loading solution with `with()`
-   Lazy vs eager loading comparison
-   Query performance best practices

```php
// INEFFICIENT: N+1 Query Problem
$courses = Course::all();
foreach ($courses as $course) {
    echo $course->category->name;  // Hits database N times
}

// OPTIMIZED: Eager Loading
$courses = Course::with('category')->get();
foreach ($courses as $course) {
    echo $course->category->name;  // Single database call
}
```

### 5. **Slide 15.5 - JWT Authentication Code**

**Topic:** Implementing JWT token-based authentication
**Code Examples:**

-   Login method with JWT token generation
-   Token validation in middleware
-   Refresh token patterns
-   Security best practices

```php
// JWT login method
public function login(LoginRequest $request) {
    if (!Auth::attempt($request->only(['email', 'password']))) {
        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    $token = auth()->user()->createToken('auth_token')->plainTextToken;
    return response()->json(['token' => $token, 'type' => 'Bearer']);
}
```

### 6. **Slide 16.5 - RBAC Authorization Code**

**Topic:** Role-Based Access Control with Spatie Permission
**Code Examples:**

-   Role and permission assignment
-   Permission checking in controllers
-   Policy-based authorization
-   Gate definitions

```php
// RBAC implementation with Spatie
// Assign role to user
$user->assignRole('instructor');

// Check permission in controller
if ($user->can('create-course')) {
    // Create course logic
}

// Using policies
if (Gate::allows('update-course', $course)) {
    // Update course logic
}
```

### 7. **Slide 18.5 - Service Layer Pattern Code**

**Topic:** Business logic separation with service classes
**Code Examples:**

-   CourseParticipantService with multi-step operations
-   Database transactions for data consistency
-   Event dispatching for notifications
-   Cache invalidation patterns

```php
// Service layer example
public function enrollParticipant($courseId, $participantId) {
    return DB::transaction(function () {
        // Validate enrollment
        $this->validateEnrollment($courseId, $participantId);

        // Create enrollment record
        $enrollment = Enrollment::create([...]);

        // Send notification
        event(new ParticipantEnrolled($enrollment));

        // Invalidate cache
        $this->cache->forget("course:{$courseId}:participants");

        return $enrollment;
    });
}
```

### 8. **Slide 23.5 - Caching Strategy Code**

**Topic:** Redis caching and performance optimization
**Code Examples:**

-   cache()->remember() pattern
-   Cache invalidation on model changes
-   Cache tagging for related data
-   Cache flushing strategies

```php
// Caching strategies
// Remember pattern
$courses = cache()->remember('courses:active', 3600, function () {
    return Course::where('status', 'active')->get();
});

// Cache tags for related invalidation
cache()->tags(['course:' . $course->id])->put('participants', $participants);

// Invalidate on model update
protected static function booted() {
    static::updated(function ($model) {
        cache()->tags(['course:' . $model->id])->flush();
    });
}
```

### 9. **Slide 25.5 - Observer Pattern Code**

**Topic:** Model lifecycle hooks and automatic actions
**Code Examples:**

-   CourseObserver with created() and updated() methods
-   Auto-generating slugs or codes
-   Audit logging
-   Event triggering

```php
// Observer pattern for automatic actions
class CourseObserver {
    public function created(Course $course) {
        // Generate unique course code
        $course->update(['code' => 'COURSE-' . $course->id]);

        // Dispatch notification event
        event(new CourseCreated($course));
    }

    public function updated(Course $course) {
        // Log changes for audit
        activity()
            ->performedOn($course)
            ->log('Course updated: ' . $course->name);
    }
}
```

## Integration Points

The code samples are strategically placed in the presentation:

| Slide # | Topic              | Code Focus          | Duration |
| ------- | ------------------ | ------------------- | -------- |
| 10.5    | Relationships      | Database concepts   | ~3 mins  |
| 11.5    | API Design         | Controller patterns | ~3 mins  |
| 13.5    | Lab 1.2            | Complete model      | ~5 mins  |
| 14.5    | Query Optimization | Performance         | ~5 mins  |
| 15.5    | Authentication     | JWT tokens          | ~5 mins  |
| 16.5    | Authorization      | RBAC implementation | ~5 mins  |
| 18.5    | Service Layer      | Business logic      | ~5 mins  |
| 23.5    | Caching            | Redis patterns      | ~5 mins  |
| 25.5    | Observers          | Lifecycle hooks     | ~5 mins  |

## Code Formatting

All code samples use:

-   **Font:** Courier New
-   **Size:** 9pt (readable in presentation)
-   **Background:** Dark gray (RGB 40, 40, 40)
-   **Text Color:** Light green (RGB 200, 220, 100)
-   **Language:** PHP with syntax-aware formatting
-   **Line Height:** 1.0 for compact display

## Usage Notes for Trainers

1. **Live Coding Optional:** Code slides can be used for live coding demonstrations where trainers modify code in real-time
2. **Discussion Points:** Each code sample includes common patterns and anti-patterns for discussion
3. **Workshop Reference:** Code samples match the lab exercises, allowing direct comparison
4. **Copy-Paste Ready:** Code is formatted for easy copying into editors or IDEs
5. **Pause Points:** Trainer can pause at each code slide for questions and discussion (~5 minutes per slide)

## Generated By

-   **Script:** create_tot_presentation.py (944 lines)
-   **Function:** add_code_slide() for code-specific formatting
-   **Library:** python-pptx 1.0.2

## Files Included in Training Package

1. **EPS_TOT_Training_2Days.pptx** - Main presentation with code samples
2. **HANDS_ON_TRAINING_GUIDE.md** - Comprehensive reference guide
3. **TOT_PLANNING_2DAY_COURSE.md** - Detailed curriculum with labs
4. **create_tot_presentation.py** - Presentation generation script
5. **PRESENTATION_README.md** - Presentation documentation
6. **CODE_SAMPLES_IN_PRESENTATION.md** - This document

## Next Steps for Trainers

1. Open the PowerPoint file and review code sample formatting
2. Test code examples in a local Laravel environment
3. Prepare follow-up exercises based on code samples
4. Consider adding speaker notes for deeper explanations
5. Create backup copies of the presentation for each training session

---

**Total Training Package Size:** ~400 KB (including all documentation and presentation)
**Estimated Training Duration:** 16 hours (2 days × 8 hours)
**Code Examples:** 25+ complete, working examples
**Lab Exercises:** 8 comprehensive labs
**Assessment:** 100-point rubric with 6 evaluation criteria

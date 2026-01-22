# EPS Backend Web - Hands-On Training Guide

**Project Type:** Laravel 10 REST API Backend  
**PHP Version:** ^8.1  
**Framework:** Laravel 10  
**Current Date:** January 7, 2026

---

## 📋 Table of Contents

1. [Project Overview](#project-overview)
2. [Setup Instructions](#setup-instructions)
3. [Project Architecture](#project-architecture)
4. [Key Technologies & Libraries](#key-technologies--libraries)
5. [File Structure Guide](#file-structure-guide)
6. [Complex Code Examples & Patterns](#complex-code-examples--patterns)
7. [Database & Models](#database--models)
8. [API Routes & Controllers](#api-routes--controllers)
9. [Authentication & Authorization](#authentication--authorization)
10. [Common Development Tasks](#common-development-tasks)
11. [Testing & Debugging](#testing--debugging)
12. [Important Notes & Best Practices](#important-notes--best-practices)

---

## Project Overview

**EPS Backend Web** is a comprehensive Laravel-based backend system handling multiple modules:

-   **Course Management** - Course planning, calendar, sessions, agendas, assessments
-   **Exam Management** - Exam planning, candidates, scoring, results, grades
-   **Facility Management** - Room booking, equipment, accommodations, complaints
-   **Inspectorate** - Inspection planning, checklists, reports, compliance tracking
-   **Security & Authentication** - 2FA, Keycloak integration, JWT tokens
-   **User Management** - Roles, permissions, designations, profiles
-   **Payment & Billing** - Payment claims, gateway integration
-   **Consultation Services** - Consultation calendar, approvals, validations
-   **Digital Safety Instructions** - AKD compliance and tracking
-   **Audit Logging** - Complete audit trail for data changes

### Core Philosophy

-   **RESTful API Design** - All endpoints follow REST conventions
-   **Eloquent ORM** - Database interactions through Laravel models
-   **Service Layer** - Business logic separated from controllers
-   **Auditable Models** - Track all data changes with Owen-it Auditing
-   **Media Management** - File handling with Spatie Media Library
-   **Permission-based Access** - Spatie Permission for fine-grained control

---

## Setup Instructions

### Prerequisites

```bash
# Required
- PHP 8.1 or higher
- Composer
- MySQL/MariaDB
- Node.js & npm
- Git

# Optional (for enhanced functionality)
- Redis (for caching & queues)
- Docker (for Keycloak SSO - recommended)
- S3/AWS SDK (for file storage)
- ClamAV (for virus scanning)
- Keycloak (for SSO authentication - see setup below)
```

### Initial Setup

```bash
# 1. Clone and navigate to project
cd c:\Users\User\Documents\laragon\www\eps-be-web

# 2. Install dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eps_be_web
DB_USERNAME=root
DB_PASSWORD=

# 6. Run migrations
php artisan migrate

# 7. Run seeders (if available)
php artisan db:seed

# 8. Clear cache
php artisan config:cache
php artisan route:cache

# 9. Generate JWT secret (if using JWT Auth)
php artisan jwt:secret

# 10. Setup Keycloak (optional but recommended for SSO)
# See "Keycloak SSO Installation & Setup" section below
# Or refer to KEYCLOAK_SETUP_GUIDE.md for detailed instructions

# 11. Start development server
php artisan serve --port=8000
```

### Environment Configuration

**Key .env variables:**

```env
APP_NAME="EPS Backend"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eps_be_web
DB_USERNAME=root
DB_PASSWORD=

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=cookie
QUEUE_CONNECTION=redis

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=

# Keycloak (SSO) - See "Keycloak SSO Installation & Setup" section
KEYCLOAK_BASE_URL=http://localhost:8080
KEYCLOAK_REALM=eps
KEYCLOAK_REALM_PUBLIC_KEY=<copy_from_keycloak_realm_settings>
KEYCLOAK_CLIENT_ID=eps_backend
KEYCLOAK_CLIENT_SECRET=<copy_from_keycloak_client_credentials>
KEYCLOAK_CACHE_OPEN_ID_CONFIG=false

# JWT Authentication
JWT_SECRET=generated_by_jwt:secret_command
JWT_ALGORITHM=HS256
JWT_ALGORITHM=HS256

# AWS S3 (optional)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=
```

---

## Project Architecture

### Layered Architecture

```
┌─────────────────────────────────────┐
│        API Routes (routes/api.php)  │
├─────────────────────────────────────┤
│      Controllers (Http/Controllers) │
│  • Validate Requests                │
│  • Call Services                    │
│  • Format Responses                 │
├─────────────────────────────────────┤
│     Services (App/Services)         │
│  • Business Logic                   │
│  • Data Processing                  │
│  • External Integrations            │
├─────────────────────────────────────┤
│       Models (App/Models)           │
│  • Database Queries                 │
│  • Relationships                    │
│  • Scopes & Accessors              │
├─────────────────────────────────────┤
│      Database (Migrations)          │
│  • Table Structure                  │
│  • Indexes & Constraints            │
└─────────────────────────────────────┘
```

### Key Folders

| Folder                      | Purpose                             |
| --------------------------- | ----------------------------------- |
| `app/Models/`               | Eloquent models (300+ models)       |
| `app/Http/Controllers/Api/` | API controllers organized by module |
| `app/Http/Requests/`        | Form request validation             |
| `app/Http/Resources/`       | API resource transformers           |
| `app/Services/`             | Business logic & external services  |
| `app/Traits/`               | Reusable model traits               |
| `database/migrations/`      | Database schema definitions         |
| `database/seeders/`         | Database seeders for testing        |
| `routes/api.php`            | API route definitions (3000+ lines) |
| `config/`                   | Application configuration           |

---

## Key Technologies & Libraries

### Core Framework & ORM

```json
"laravel/framework": "^10.0",
"laravel/sanctum": "^3.2",
"laravel/tinker": "^2.8"
```

### Authentication & Security

```json
"tymon/jwt-auth": "^2.1",
"robsontenorio/laravel-keycloak-guard": "^1.5",
"pragmarx/google2fa": "^8.0",
"pragmarx/google2fa-laravel": "^2.2"
```

### Authorization & Auditing

```json
"spatie/laravel-permission": "^5.10",
"owen-it/laravel-auditing": "^13.5"
```

### File & Media Management

```json
"spatie/laravel-medialibrary": "^10.0.0",
"league/flysystem": "~3.15",
"league/flysystem-aws-s3-v3": "^3.0"
```

### Data Export & Processing

```json
"maatwebsite/excel": "*",
"barryvdh/laravel-dompdf": "^2.0",
"filippo-toso/pdf-watermarker": "^1.0"
```

### Additional Tools

```json
"guzzlehttp/guzzle": "^7.2",
"jenssegers/agent": "^2.6",
"kalnoy/nestedset": "^6.0",
"nesbot/carbon": "^2.72",
"yajra/laravel-datatables": "10.0"
```

---

## File Structure Guide

### Models Categorization

**Course Management Models** (70+ models)

```
Course.php, CourseSession.php, CourseAgenda.php
CourseCalendar.php, CourseCalendarParticipant.php
CourseAssessment*, CoursePayment*, CourseLecturer*
```

**Exam Management Models** (120+ models)

```
Exam.php, ExamCalendar.php, ExamCandidate.php
ExamCenter.php, ExamGrade.php, ExamResult.php
ExamQuestion*, ExamSchema*, ExamScoring*
```

**Facility Management Models** (30+ models)

```
Facility*.php - Rooms, bookings, accommodations
FacilityCode*, FacilityEquipment*, FacilityService*
```

**Inspectorate Models** (70+ models)

```
Inspectorate*.php - Calendar, checklist, assessment
InspectorateReport*, InspectorateTeam*
```

**User & Agency Models** (80+ models)

```
User.php, UserProfile*.php, UserSetting*.php
Agency*.php, AgencyType*, AgencyLevel*
```

**System & Configuration Models** (40+ models)

```
SystemConfig*.php, SystemMenu*.php, System*.php
```

---

## Complex Code Examples & Patterns

### Example 1: Advanced Model with Relationships

**File:** [app/Models/Course.php](app/Models/Course.php)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Course extends Model implements Auditable, HasMedia
{
    // Auditing trait - logs all changes
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'course_sub_category_id',
        'code',
        'course_name',
        'course_note',
        'open_to',
        'special_to',
        'application_requirement',
        'attendance_percentage',
        // Payment-related fields
        'payment_closed_requirement',
        'payment_internal_requirement',
        'payment_government_requirement',
        'payment_private_requirement',
        'created_by',
        'updated_by',
        'status',
    ];

    /**
     * JSON casting - automatically encode/decode arrays
     * When retrieved from DB: automatically decoded to array
     * When saved to DB: automatically encoded to JSON
     */
    protected $casts = [
        'open_to' => 'array',
        'special_to' => 'array',
        'application_requirement' => 'array',
    ];

    // RELATIONSHIPS

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(CourseSubCategory::class, 'course_sub_category_id');
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(CourseSession::class, 'course_id')
            ->orderBy('days')
            ->orderBy('start');
    }

    public function agendas(): HasMany
    {
        return $this->hasMany(CourseAgenda::class, 'course_id')
            ->orderBy('days')
            ->orderBy('start');
    }

    public function notes(): HasMany
    {
        return $this->hasMany(CourseNote::class, 'course_id');
    }

    public function courseCalendars(): HasMany
    {
        return $this->hasMany(CourseCalendar::class);
    }

    // SCOPES & QUERIES

    public function courseCalendarLatestApproved()
    {
        return $this->courseCalendars()
            ->where('status', '4');
    }

    // BUSINESS LOGIC

    /**
     * Check if current user is eligible to attend this course
     * Complex eligibility logic:
     * 1. Must pass course prerequisites
     * 2. Must meet open_to requirements
     * 3. Must meet special_to requirements
     */
    public function getEligibility()
    {
        $user = Auth::user();

        // Check course requirement eligibility
        if (!$this->checkCourseRequirement($user)) {
            return false;
        }

        // Check open_to eligibility
        return $this->checkOpenToRequirement($user);
    }

    /**
     * Verify user has completed all required prerequisite courses
     */
    private function checkCourseRequirement($user)
    {
        // No requirements = automatic pass
        if (empty($this->application_requirement)) {
            return true;
        }

        $requiredCourseIds = $this->application_requirement;

        // Handle JSON string case
        if (is_string($requiredCourseIds)) {
            $requiredCourseIds = json_decode($requiredCourseIds, true) ?? [];
        }

        if (empty($requiredCourseIds)) {
            return true;
        }

        // Get distinct courses user completed with valid certificate
        $completedCourseIds = CourseCalendarParticipant::where('user_id', $user->id)
            ->whereHas('courseCalendar', function ($query) use ($requiredCourseIds) {
                $query->whereIn('course_id', $requiredCourseIds);
            })
            ->get()
            ->filter(function ($participant) {
                // Certificate status: 0 = Completed, 2 = Passed
                return in_array($participant->getCertificateStatus(), [0, 2]);
            })
            ->pluck('courseCalendar.course_id')
            ->unique()
            ->values();

        // Must have completed ALL required courses
        return $completedCourseIds->count() === count($requiredCourseIds)
            && $completedCourseIds->diff($requiredCourseIds)->isEmpty();
    }

    /**
     * Verify user meets open_to category requirements
     */
    private function checkOpenToRequirement($user)
    {
        if (empty($this->open_to)) {
            return true;
        }

        $openToArray = is_string($this->open_to)
            ? json_decode($this->open_to, true)
            : $this->open_to;

        if (empty($openToArray)) {
            return true;
        }

        // Check if user's category is in open_to list
        return in_array($user->participant_type_id, $openToArray);
    }

    // MEDIA HANDLING

    /**
     * Spatie Media Library integration
     * Handles image uploads for course
     */
    public function thumbnail()
    {
        return $this->getMedia('thumbnail');
    }

    /**
     * Called after image upload to register collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')
            ->singleFile();
    }
}
```

**Key Concepts:**

-   **Auditable Interface** - Tracks all changes
-   **HasMedia Interface** - Manages file uploads
-   **JSON Casting** - Auto-convert arrays to JSON
-   **Eloquent Relationships** - BelongsTo, HasMany
-   **Query Chains** - Complex nested queries with whereHas
-   **Business Logic** - Eligibility checking logic

---

### Example 2: Service Layer for Complex Operations

**Pattern Used:** Service Layer Pattern

```php
<?php

namespace App\Services;

use App\Models\CourseCalendarParticipant;
use App\Models\CourseCalendar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseParticipantService
{
    /**
     * Complex multi-step process:
     * 1. Validate participant eligibility
     * 2. Register in calendar
     * 3. Record attendance
     * 4. Log transaction
     * 5. Send notifications
     * 6. Update cache
     */
    public function registerParticipant($userId, $courseCalendarId, $data = [])
    {
        try {
            return DB::transaction(function () use ($userId, $courseCalendarId, $data) {
                // Step 1: Validate eligibility
                $this->validateEligibility($userId, $courseCalendarId);

                // Step 2: Create participant record
                $participant = CourseCalendarParticipant::create([
                    'user_id' => $userId,
                    'course_calendar_id' => $courseCalendarId,
                    'participant_type_id' => $data['participant_type_id'] ?? null,
                    'registration_date' => now(),
                    'status' => 'registered',
                ]);

                // Step 3: Initialize attendance
                $this->initializeAttendance($participant);

                // Step 4: Send notification
                $this->notifyParticipantRegistered($participant);

                // Step 5: Clear cache
                cache()->forget('course_participants_' . $courseCalendarId);

                Log::info("Participant registered", [
                    'user_id' => $userId,
                    'calendar_id' => $courseCalendarId
                ]);

                return $participant;
            });
        } catch (\Exception $e) {
            Log::error("Registration failed: " . $e->getMessage());
            throw $e;
        }
    }

    private function validateEligibility($userId, $courseCalendarId)
    {
        $calendar = CourseCalendar::findOrFail($courseCalendarId);

        // Check capacity
        if ($calendar->participants()->count() >= $calendar->max_participants) {
            throw new \Exception("Course calendar is full");
        }

        // Check if already registered
        if ($calendar->participants()->where('user_id', $userId)->exists()) {
            throw new \Exception("User already registered");
        }

        // Check course eligibility
        if (!$calendar->course->getEligibility()) {
            throw new \Exception("User not eligible for this course");
        }
    }

    private function initializeAttendance($participant)
    {
        $sessions = $participant->courseCalendar->course->sessions;

        foreach ($sessions as $session) {
            $participant->attendances()->create([
                'course_session_id' => $session->id,
                'status' => 'pending',
            ]);
        }
    }

    private function notifyParticipantRegistered($participant)
    {
        // Send email/notification
        // This could trigger jobs or event listeners
    }
}
```

**Key Concepts:**

-   **DB::transaction()** - Ensure data consistency
-   **Multiple Steps** - Complex business processes
-   **Error Handling** - Try-catch with logging
-   **Cache Management** - Clear relevant caches
-   **Notifications** - Trigger side effects
-   **Private Methods** - Break logic into steps

---

### Example 3: Polymorphic Relationships

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class AuditLog extends Model
{
    /**
     * Polymorphic relationship - can audit ANY model
     * Single audit table tracks changes across all models
     *
     * Examples:
     * - Course audit logs
     * - Exam audit logs
     * - User audit logs
     * - Facility audit logs
     */
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    // Usage:
    // $log = AuditLog::find(1);
    // $log->auditable // Returns the Course/Exam/User/etc that was audited
}

class Course extends Model
{
    /**
     * Inverse polymorphic - get all audit logs for this course
     */
    public function auditLogs(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}
```

**Key Concepts:**

-   **MorphTo/MorphMany** - One-to-many across multiple models
-   **Single Table** - Audit table for all models
-   **Flexible Schema** - Handle different model types

---

### Example 4: Query Optimization with Eager Loading

```php
<?php

// INEFFICIENT - N+1 Problem (300 queries for 100 records)
$courses = Course::all();
foreach ($courses as $course) {
    echo $course->subCategory->name; // Additional query for each!
    echo $course->createdBy->name; // Additional query for each!
}

// EFFICIENT - Eager Loading (2 queries total)
$courses = Course::with([
    'subCategory',
    'createdBy',
    'updatedBy',
    'sessions.agendas',
    'courseCalendars.participants'
])->get();

// COMPLEX Eager Loading with Constraints
$courses = Course::with([
    'sessions' => function ($query) {
        $query->where('status', 'active')
              ->orderBy('days')
              ->limit(5);
    },
    'courseCalendars' => function ($query) {
        $query->where('status', '4') // Approved only
              ->latest('created_at')
              ->limit(1);
    }
])->paginate(20);

// Counting related records
$courses = Course::withCount(['sessions', 'participants'])
    ->where('status', 'active')
    ->get();

foreach ($courses as $course) {
    echo "{$course->course_name}: {$course->sessions_count} sessions";
}

// Conditional Eager Loading
$courses = Course::when(
    request()->has('include_sessions'),
    fn ($query) => $query->with('sessions')
)->get();
```

**Key Concepts:**

-   **with()** - Eager load relationships
-   **Nested Loading** - Load relationships of relationships
-   **Constraints** - Filter eager loaded data
-   **withCount()** - Count relationships efficiently
-   **when()** - Conditional query building

---

### Example 5: JSON Query Operations

```php
<?php

// Models with JSON columns use JSON casting
// In Course model: protected $casts = ['open_to' => 'array'];

// Storing array as JSON
$course = Course::create([
    'course_name' => 'Security Training',
    'open_to' => [1, 2, 3], // Auto-converted to JSON in DB
    'special_to' => ['dept_a', 'dept_b'], // Can store string arrays
]);

// Retrieving automatically converts back to array
$openTo = $course->open_to; // Array, not JSON string

// Querying JSON columns
$courses = Course::whereJsonContains('open_to', 1)->get();
$courses = Course::whereJsonLength('open_to', '>', 2)->get();

// Updating JSON values
$course->update([
    'open_to' => array_merge($course->open_to, [4, 5])
]);
```

**Key Concepts:**

-   **JSON Casting** - Automatic encode/decode
-   **whereJsonContains** - Check if array contains value
-   **whereJsonLength** - Check array size

---

### Example 6: Custom Accessors & Mutators (Old Laravel 7 style)

```php
<?php

namespace App\Models;

class User extends Model
{
    /**
     * Accessor - compute property on-the-fly
     * Accessed as: $user->full_name
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Mutator - transform before saving
     * Used when: $user->email = 'TEST@EMAIL.COM'
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    /**
     * Cast with custom class
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime:Y-m-d H:i:s',
    ];
}

// Usage:
$user = User::find(1);
echo $user->full_name; // "John Doe" - computed
$user->email = "TEST@EMAIL.COM";
$user->save(); // Saves as "test@email.com"
```

---

### Example 7: Query Scopes

```php
<?php

namespace App\Models;

class Course extends Model
{
    /**
     * Local Scope - filter active courses
     * Usage: Course::active()->get()
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Local Scope with parameters
     * Usage: Course::byCategory(5)->get()
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('course_sub_category_id', $categoryId);
    }

    /**
     * Chaining scopes
     */
    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now());
    }

    /**
     * Complex scope
     */
    public function scopeAvailable($query)
    {
        return $query->active()
            ->published()
            ->whereDate('end_date', '>=', today())
            ->where('capacity', '>', 0);
    }
}

// Usage:
Course::active()->byCategory(3)->get(); // Chain multiple scopes
Course::available()->paginate(); // Complex filtering
```

**Key Concepts:**

-   **Local Scopes** - Reusable query filters
-   **scope prefix** - Method starts with 'scope'
-   **Chainable** - Can chain multiple scopes
-   **Reduce Duplication** - Use instead of repeating where clauses

---

### Example 8: Event-Driven Architecture

```php
<?php

// In Model
namespace App\Models;

use App\Events\CourseRegistrationCompleted;

class CourseCalendarParticipant extends Model
{
    protected $dispatchesEvents = [
        'created' => CourseRegistrationCompleted::class,
    ];
}

// Event Definition
namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CourseRegistrationCompleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public CourseCalendarParticipant $participant
    ) {}
}

// Listener
namespace App\Listeners;

class SendCourseRegistrationNotification
{
    public function handle(CourseRegistrationCompleted $event)
    {
        // Send email
        Mail::to($event->participant->user->email)
            ->send(new CourseRegistrationMail($event->participant));

        // Update cache
        cache()->forget('user_registrations_' . $event->participant->user_id);

        // Log activity
        activity()
            ->causedBy($event->participant->user)
            ->performedOn($event->participant)
            ->log('Registered for course');
    }
}

// Register in EventServiceProvider
protected $listen = [
    CourseRegistrationCompleted::class => [
        SendCourseRegistrationNotification::class,
    ],
];
```

**Key Concepts:**

-   **Events** - Trigger application-wide actions
-   **Listeners** - Handle event side effects
-   **Decoupling** - Business logic separate from models
-   **Queueable** - Can run listeners asynchronously

---

### Example 9: Custom Validation Rules

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'course_name' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                // Unique rule with conditions
                Rule::unique('courses', 'code')
                    ->where('course_sub_category_id', $this->course_sub_category_id)
                    ->ignore($this->course_id),
            ],
            'course_sub_category_id' => 'required|exists:course_sub_categories,id',
            'open_to' => 'array|min:1',
            'open_to.*' => 'integer|exists:participant_types,id',
            'attendance_percentage' => 'required|numeric|between:0,100',
            'payment_closed_fee' => $this->getPaymentValidation('closed'),
            'payment_internal_fee' => $this->getPaymentValidation('internal'),
            'created_by' => 'required|exists:users,id',
        ];
    }

    private function getPaymentValidation($type)
    {
        // Conditional validation based on requirement field
        return [
            'sometimes',
            'numeric',
            'min:0',
            function ($attribute, $value, $fail) use ($type) {
                $requirementField = "payment_{$type}_requirement";

                if ($this->input($requirementField) && !$value) {
                    $fail("Fee is required if {$type} payment is required");
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'code.unique' => 'Course code must be unique within category',
            'open_to.min' => 'At least one category must be selected',
        ];
    }

    public function authorize(): bool
    {
        // Only course creators can update
        return $this->user()->can('create', Course::class);
    }
}
```

**Key Concepts:**

-   **Custom Closure Rules** - Complex validation logic
-   **Conditional Validation** - Different rules based on data
-   **Rule::unique()** - Advanced unique constraints
-   **messages()** - Custom error messages
-   **authorize()** - Check permissions

---

## Database & Models

### Model Conventions

| Property     | Convention                 | Example                       |
| ------------ | -------------------------- | ----------------------------- |
| Table Name   | Plural, snake_case         | `courses`, `course_calendars` |
| Primary Key  | `id`                       | Auto-increment integer        |
| Foreign Keys | `{singular}_id`            | `course_id`, `user_id`        |
| Timestamps   | `created_at`, `updated_at` | Auto-managed by Laravel       |
| Soft Deletes | `deleted_at`               | Optional timestamps           |

### Important Traits

```php
use HasFactory;              // Factory support for testing
use SoftDeletes;             // Soft delete functionality
use \OwenIt\Auditing\Auditable; // Audit logging
use InteractsWithMedia;      // File management
use HasPermissions;          // Spatie Permission
```

### Common Relationships

```php
// One-to-Many
public function sessions(): HasMany {
    return $this->hasMany(CourseSession::class);
}

// Many-to-One (Inverse)
public function course(): BelongsTo {
    return $this->belongsTo(Course::class);
}

// Many-to-Many
public function participants() {
    return $this->belongsToMany(User::class);
}

// Polymorphic
public function auditable(): MorphTo {
    return $this->morphTo();
}

// Has-Many-Through (Advanced)
public function participants() {
    return $this->hasManyThrough(
        CourseCalendarParticipant::class,
        CourseCalendar::class
    );
}
```

---

## API Routes & Controllers

### Route Organization

Routes are organized in [routes/api.php](routes/api.php) (~3000 lines) by module:

```php
// Course Management Routes
Route::prefix('course')->group(function () {
    Route::apiResource('courses', CourseController);
    Route::apiResource('calendars', CourseCalendarController);
    Route::apiResource('participants', CourseCalendarParticipantController);
    Route::post('participants/{id}/attendance', 'CourseCalendarParticipantController@recordAttendance');
});

// Exam Management Routes
Route::prefix('exam')->group(function () {
    Route::apiResource('calendars', ExamCalendarController);
    Route::apiResource('candidates', ExamCandidateController);
    Route::post('scoring', 'ExamScoringController@store');
});

// Facility Management
Route::prefix('facility')->group(function () {
    Route::apiResource('rooms', FacilityController);
    Route::apiResource('bookings', FacilityBookingController);
});
```

### Controller Structure

```php
<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Services\CourseService;

class CourseController extends Controller
{
    public function __construct(private CourseService $courseService)
    {
        // Middleware for auth, permissions
        $this->middleware('auth:api');
        $this->middleware('permission:create-course')->only(['store']);
        $this->middleware('permission:edit-course')->only(['update']);
        $this->middleware('permission:delete-course')->only(['destroy']);
    }

    /**
     * GET /api/courses
     * List all courses with pagination
     */
    public function index()
    {
        $courses = Course::with(['subCategory', 'createdBy'])
            ->when(request('search'), fn ($q) =>
                $q->where('course_name', 'like', '%' . request('search') . '%')
            )
            ->when(request('category_id'), fn ($q) =>
                $q->where('course_sub_category_id', request('category_id'))
            )
            ->when(request('status'), fn ($q) =>
                $q->where('status', request('status'))
            )
            ->paginate(request('per_page', 15));

        return CourseResource::collection($courses);
    }

    /**
     * POST /api/courses
     * Create new course
     */
    public function store(StoreCourseRequest $request)
    {
        $course = $this->courseService->createCourse($request->validated());

        return new CourseResource($course);
    }

    /**
     * GET /api/courses/{id}
     * Show single course with relationships
     */
    public function show(Course $course)
    {
        $course->load([
            'subCategory',
            'createdBy',
            'updatedBy',
            'sessions',
            'agendas',
            'courseCalendars.participants'
        ]);

        return new CourseResource($course);
    }

    /**
     * PUT /api/courses/{id}
     * Update course
     */
    public function update(StoreCourseRequest $request, Course $course)
    {
        $course = $this->courseService->updateCourse($course, $request->validated());

        return new CourseResource($course);
    }

    /**
     * DELETE /api/courses/{id}
     * Delete course
     */
    public function destroy(Course $course)
    {
        $this->courseService->deleteCourse($course);

        return response()->noContent();
    }

    /**
     * GET /api/courses/{id}/check-eligibility
     * Custom action - check if user is eligible
     */
    public function checkEligibility(Course $course)
    {
        return response()->json([
            'eligible' => $course->getEligibility(),
            'message' => $course->getEligibility()
                ? 'You are eligible'
                : 'You do not meet requirements',
        ]);
    }
}
```

### API Resources (Response Transformation)

```php
<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    /**
     * Transform model to API response
     * Automatically called when resource is returned
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'course_name' => $this->course_name,
            'course_note' => $this->course_note,

            // Nested relationships
            'category' => new CourseCategoryResource($this->whenLoaded('subCategory')),
            'created_by' => new UserResource($this->whenLoaded('createdBy')),
            'updated_by' => new UserResource($this->whenLoaded('updatedBy')),

            // Conditional data
            'sessions' => CourseSessionResource::collection(
                $this->whenLoaded('sessions')
            ),
            'agendas' => CourseAgendaResource::collection(
                $this->whenLoaded('agendas')
            ),

            // Computed properties
            'session_count' => $this->sessions_count ?? $this->sessions()->count(),
            'participant_count' => $this->participants()->count(),

            // Timestamps
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
```

---

## Authentication & Authorization

### JWT Authentication

```php
// config/auth.php
'guards' => [
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
],

// In Controllers
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $token = auth('api')->attempt($credentials);

    if (!$token) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    return response()->json([
        'access_token' => $token,
        'token_type' => 'Bearer',
        'expires_in' => auth('api')->factory()->getTTL() * 60, // seconds
    ]);
}

// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::get('/user', fn () => auth()->user());
    Route::post('/logout', fn () => auth()->logout());
    Route::post('/refresh', fn () => response()->json([
        'access_token' => auth()->refresh(),
    ]));
});
```

### Permission & Role Based Access

```php
// Using Spatie Permission

// Assign role
$user->assignRole('course-manager');

// Assign permission
$user->givePermissionTo('create-course');

// Check permission
if (auth()->user()->can('create-course')) {
    // Create course
}

// In middleware
Route::middleware('permission:edit-course')->post('/courses/{id}', ...);
Route::middleware('role:admin')->get('/admin/dashboard', ...);

// In controller
public function edit(Course $course)
{
    $this->authorize('edit', $course);
    // Update logic
}

// In policy
class CoursePolicy
{
    public function edit(User $user, Course $course)
    {
        // User created course or is admin
        return $user->id === $course->created_by || $user->isAdmin();
    }
}
```

### Keycloak SSO Installation & Setup

Keycloak is an open-source Identity and Access Management (IAM) solution providing Single Sign-On (SSO) for the EPS Backend system.

#### Installation Options

**Option 1: Docker (Recommended for Development)**

```powershell
# Quick start with Docker
docker run -d `
  --name keycloak `
  -p 8080:8080 `
  -e KEYCLOAK_ADMIN=admin `
  -e KEYCLOAK_ADMIN_PASSWORD=admin `
  keycloak/keycloak:latest `
  start-dev

# Access: http://localhost:8080
# Admin Console: http://localhost:8080/admin
```

**Option 2: Docker Compose (Better for Team Development)**

Create `docker-compose.yml`:

```yaml
version: "3.8"
services:
    keycloak:
        image: keycloak/keycloak:latest
        container_name: keycloak
        ports:
            - "8080:8080"
        environment:
            KEYCLOAK_ADMIN: admin
            KEYCLOAK_ADMIN_PASSWORD: admin
        command: start-dev
        volumes:
            - keycloak-data:/opt/keycloak/data

volumes:
    keycloak-data:
```

Run: `docker-compose up -d`

**Option 3: Standalone Installation**

```powershell
# Download latest Keycloak
Invoke-WebRequest -Uri "https://github.com/keycloak/keycloak/releases/download/21.1.1/keycloak-21.1.1.zip" `
  -OutFile "keycloak.zip"

# Extract and start
Expand-Archive keycloak.zip -DestinationPath C:\keycloak
cd C:\keycloak
.\bin\kc.bat start-dev --hostname=localhost --http-port=8080
```

#### Realm Configuration

**Step 1: Create Realm**

1. Access Admin Console: http://localhost:8080/admin
2. Login with admin credentials
3. Click "Create Realm"
4. Enter realm details:
    - Name: `eps`
    - Display Name: `EPS Backend Web`
5. Click "Create"

**Step 2: Configure Realm Settings**

```
Realm Settings → Tokens:
- Access Token Lifespan: 60 minutes
- Refresh Token Lifespan: 7 days
- SSO Session Max: 30 days
```

**Step 3: Create Client**

1. Go to Clients → Create
2. Configure:
    ```
    Client ID: eps_backend
    Client Type: OpenID Connect
    Client Authentication: ON
    Authorization: ON
    ```
3. Valid Redirect URIs:
    ```
    http://localhost:8000/*
    http://127.0.0.1:8000/*
    ```
4. Save and copy Client Secret from Credentials tab

**Step 4: Create Test Users**

```
Super Admin:
- Username: admin@eps.local
- Email: admin@eps.local
- Password: Admin@123

Course Manager:
- Username: manager@eps.local
- Email: manager@eps.local
- Password: Manager@123

Instructor:
- Username: instructor@eps.local
- Email: instructor@eps.local
- Password: Instructor@123

Student:
- Username: student@eps.local
- Email: student@eps.local
- Password: Student@123
```

**Step 5: Create Roles**

Go to Realm Roles and create:

```
- super_admin (Full system access)
- course_manager (Manage courses)
- course_instructor (Teach courses)
- course_student (Enroll in courses)
- facility_manager (Manage facilities)
- auditor (View audit logs)
```

**Step 6: Assign Roles to Users**

1. Go to Users → Select User
2. Go to Role Mapping
3. Assign appropriate realm roles

#### Laravel Integration

**Step 1: Install Keycloak Package**

```bash
composer require laravel-keycloak-guard/laravel-keycloak-guard
```

**Step 2: Publish Configuration**

```bash
php artisan vendor:publish --provider="LaravelKeycloakGuard\LaravelKeycloakGuardServiceProvider"
```

**Step 3: Get Public Key**

1. Go to Keycloak: Realm Settings → Keys
2. Find RSA256 algorithm
3. Click "Public key" button
4. Copy the key value

**Step 4: Configure Environment**

```env
# Keycloak Configuration
KEYCLOAK_BASE_URL=http://localhost:8080
KEYCLOAK_REALM=eps
KEYCLOAK_REALM_PUBLIC_KEY=<your_public_key_here>
KEYCLOAK_CLIENT_ID=eps_backend
KEYCLOAK_CLIENT_SECRET=<your_client_secret>
KEYCLOAK_CACHE_OPEN_ID_CONFIG=false
```

**Step 5: Update Auth Configuration**

In `config/auth.php`:

```php
'guards' => [
    'keycloak' => [
        'driver' => 'keycloak',
    ],
],

'providers' => [
    'users' => [
        'driver' => 'keycloak',
    ],
],
```

**Step 6: Protect Routes**

```php
// In routes/api.php
Route::middleware('auth:keycloak')->group(function () {
    Route::get('/protected', function () {
        return auth()->user();
    });

    Route::apiResource('courses', CourseController::class);
});
```

#### Testing Keycloak Integration

**Test 1: Get Access Token**

```powershell
# Using PowerShell
$body = @{
    client_id = "eps_backend"
    client_secret = "your_client_secret"
    grant_type = "password"
    username = "student@eps.local"
    password = "Student@123"
    scope = "openid profile email"
}

$response = Invoke-RestMethod -Uri "http://localhost:8080/realms/eps/protocol/openid-connect/token" `
    -Method Post `
    -Body $body

$token = $response.access_token
Write-Host "Token: $token"
```

**Test 2: Use Token in API Request**

```powershell
# Call protected endpoint
$headers = @{
    Authorization = "Bearer $token"
}

Invoke-RestMethod -Uri "http://localhost:8000/api/protected" `
    -Method Get `
    -Headers $headers
```

**Test 3: Validate Token**

```php
// In Laravel controller
Route::get('/validate-token', function () {
    $user = auth('keycloak')->user();

    return response()->json([
        'authenticated' => auth('keycloak')->check(),
        'user' => $user,
        'roles' => $user->roles ?? [],
    ]);
})->middleware('auth:keycloak');
```

**Test 4: Check User Roles**

```php
Route::get('/check-permissions', function () {
    $user = auth('keycloak')->user();

    return response()->json([
        'is_admin' => in_array('super_admin', $user->roles ?? []),
        'is_manager' => in_array('course_manager', $user->roles ?? []),
        'is_instructor' => in_array('course_instructor', $user->roles ?? []),
        'all_roles' => $user->roles ?? [],
    ]);
})->middleware('auth:keycloak');
```

#### Common Keycloak Issues

**Issue 1: Cannot Connect to Keycloak**

```powershell
# Check if Keycloak is running
docker ps | findstr keycloak

# View logs
docker logs -f keycloak

# Restart if needed
docker restart keycloak
```

**Issue 2: Invalid Token / 401 Errors**

```powershell
# Update public key in .env
# Get fresh key from: Realm Settings → Keys → RSA256 → Public key

# Clear Laravel cache
php artisan cache:clear
php artisan config:clear
```

**Issue 3: CORS Errors**

1. Go to Keycloak: Realm Settings → Security
2. Add to "Web Origins":
    ```
    http://localhost:8000
    http://127.0.0.1:8000
    ```

**Issue 4: Token Expired**

```php
// Implement token refresh in Laravel
Route::post('/refresh-token', function (Request $request) {
    $refreshToken = $request->input('refresh_token');

    $response = Http::asForm()->post(
        config('keycloak.base_url') . '/realms/' . config('keycloak.realm') . '/protocol/openid-connect/token',
        [
            'client_id' => config('keycloak.client_id'),
            'client_secret' => config('keycloak.client_secret'),
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ]
    );

    return $response->json();
});
```

#### Keycloak Best Practices

1. **Security**

    - Change default admin password in production
    - Use HTTPS in production (never HTTP)
    - Implement proper token expiration times
    - Enable MFA for sensitive accounts

2. **Token Management**

    - Store refresh tokens securely
    - Implement token rotation
    - Log all authentication attempts
    - Monitor token usage

3. **User Management**

    - Use email verification
    - Implement strong password policies
    - Regular audit of user accounts
    - Disable unused accounts

4. **Performance**

    - Use Redis for session storage
    - Enable Keycloak clustering for production
    - Monitor Keycloak performance metrics
    - Optimize realm and client settings

5. **Backup & Recovery**

    ```powershell
    # Export realm configuration
    docker exec keycloak /opt/keycloak/bin/kc.sh export `
      --realm eps `
      --file /tmp/eps-realm-backup.json

    # Copy from container
    docker cp keycloak:/tmp/eps-realm-backup.json ./backups/
    ```

#### Advanced Keycloak Features

**Custom User Attributes**

```
Add custom attributes in Keycloak:
Users → User Details → Attributes:
- department: "Course Development"
- employee_id: "EMP001"
- location: "Head Office"
```

**Role Mappers**

```
Create mapper to include roles in token:
Client → Mappers → Create:
- Name: roles-mapper
- Mapper Type: User Realm Role
- Token Claim Name: roles
- Add to ID Token: ON
- Add to Access Token: ON
```

**Service Account**

```
Enable service account for backend-to-backend:
Clients → eps_backend → Service Account Roles
Assign: realm-management, manage-users
```

For complete Keycloak setup documentation, see [KEYCLOAK_SETUP_GUIDE.md](KEYCLOAK_SETUP_GUIDE.md)

---

## Common Development Tasks

### Task 1: Creating a New API Endpoint

**Steps:**

1. **Create Request Validation Class**

```bash
php artisan make:request StoreNewResourceRequest
```

2. **Create Controller**

```bash
php artisan make:controller Api/YourModuleController --api
```

3. **Create Resource (API response)**

```bash
php artisan make:resource YourModuleResource
```

4. **Create Model (if needed)**

```bash
php artisan make:model YourModule -m
```

5. **Define Routes in routes/api.php**

```php
Route::apiResource('your-modules', YourModuleController::class);
```

6. **Implement Controller Logic**

```php
public function store(StoreNewResourceRequest $request)
{
    $resource = YourModule::create($request->validated());
    return new YourModuleResource($resource);
}
```

---

### Task 2: Adding Auditing to a Model

```php
<?php

namespace App\Models;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class YourModel extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    // Your model code
}

// Query audit logs
$model = YourModel::find(1);
$model->audits()->get(); // All changes

// See who changed what
foreach ($model->audits as $audit) {
    echo "{$audit->user->name} {$audit->event} record";
    echo "Old: " . json_encode($audit->old_values);
    echo "New: " . json_encode($audit->new_values);
}
```

---

### Task 3: Implementing File Upload

```php
<?php

// Model must implement HasMedia
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Course extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->onlyKeepLatest(1);

        $this->addMediaCollection('course-materials')
            ->acceptsMimeTypes(['application/pdf', 'application/msword'])
            ->useFallbackUrl('/images/default.png');
    }
}

// In Controller
public function upload(Request $request, Course $course)
{
    $file = $request->file('thumbnail');

    $course->addMedia($file)
        ->toMediaCollection('thumbnail');

    return response()->json(['message' => 'Uploaded']);
}

// Retrieve file
$url = $course->getFirstMediaUrl('thumbnail');
$files = $course->getMedia('course-materials');

foreach ($files as $media) {
    echo $media->original_url;
    echo $media->file_name;
    echo $media->size;
}
```

---

### Task 4: Exporting Data to Excel

```php
<?php

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CoursesExport;

// In Controller
public function export()
{
    return Excel::download(
        new CoursesExport(),
        'courses_' . now()->format('Y-m-d') . '.xlsx'
    );
}

// Create Export class
namespace App\Exports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CoursesExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    public function query()
    {
        return Course::query()
            ->with('subCategory')
            ->where('status', 'active');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Code',
            'Course Name',
            'Category',
            'Created At',
        ];
    }

    public function map($course): array
    {
        return [
            $course->id,
            $course->code,
            $course->course_name,
            $course->subCategory->name,
            $course->created_at->format('Y-m-d'),
        ];
    }
}
```

---

### Task 5: Creating PDF Reports

```php
<?php

use Barryvdh\DomPDF\Facade\Pdf;

public function generateReport($courseId)
{
    $course = Course::with([
        'sessions',
        'participants',
        'agendas'
    ])->findOrFail($courseId);

    $pdf = Pdf::loadView('reports.course', [
        'course' => $course,
    ])
        ->setPaper('a4')
        ->setOrientation('portrait');

    // Add watermark
    $pdf->addMediaPath(public_path('images/watermark.png'));

    return $pdf->download("course_{$courseId}.pdf");
}

// In view (resources/views/reports/course.blade.php)
<h1>{{ $course->course_name }}</h1>
<p>Code: {{ $course->code }}</p>

<h2>Sessions</h2>
@foreach ($course->sessions as $session)
    <p>{{ $session->name }} - {{ $session->start_time }}</p>
@endforeach
```

---

### Task 6: Sending Email Notifications

```php
<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CourseRegistrationMail extends Mailable
{
    use SerializesModels;

    public function __construct(
        public CourseCalendarParticipant $participant
    ) {}

    public function envelope()
    {
        return new Envelope(
            subject: 'Course Registration Confirmation',
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.course-registration',
        );
    }

    public function attachments()
    {
        return [];
    }
}

// In Controller or Service
use Illuminate\Support\Facades\Mail;

Mail::to($user->email)->send(
    new CourseRegistrationMail($participant)
);

// Queue email for async sending
Mail::to($user->email)->queue(
    new CourseRegistrationMail($participant)
);
```

---

### Task 7: Creating Database Migration

```bash
php artisan make:migration create_course_calendars_table --create=course_calendars
```

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_calendars', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('course_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('created_by')
                ->constrained('users')
                ->onDelete('cascade');

            // Data columns
            $table->string('code')->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('max_participants')->default(50);
            $table->enum('status', ['draft', 'approved', 'running', 'completed']);
            $table->longText('notes')->nullable();

            // JSON column
            $table->json('metadata')->nullable();

            // Indexes
            $table->index('course_id');
            $table->index('status');

            // Timestamps
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_calendars');
    }
};
```

---

### Task 8: Creating a Job for Async Processing

```bash
php artisan make:job ProcessCourseCompletion
```

```php
<?php

namespace App\Jobs;

use App\Models\CourseCalendar;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessCourseCompletion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Job timeout (seconds)
    public $timeout = 300;
    public $tries = 3; // Retry 3 times on failure

    public function __construct(
        public CourseCalendar $courseCalendar
    ) {}

    public function handle()
    {
        // Generate certificates
        $this->courseCalendar->participants->each(function ($participant) {
            $this->generateCertificate($participant);
        });

        // Send notifications
        $this->courseCalendar->participants->each(function ($participant) {
            Mail::to($participant->user->email)
                ->send(new CourseCompletionMail($participant));
        });

        // Update statistics
        cache()->forget('course_stats_' . $this->courseCalendar->id);
    }

    public function failed(\Throwable $exception)
    {
        // Handle job failure
        Log::error('Course completion job failed', [
            'course_id' => $this->courseCalendar->id,
            'error' => $exception->getMessage(),
        ]);
    }

    private function generateCertificate($participant)
    {
        // Certificate generation logic
    }
}

// Dispatch job
ProcessCourseCompletion::dispatch($courseCalendar);

// Or dispatch delayed
ProcessCourseCompletion::dispatch($courseCalendar)
    ->delay(now()->addMinutes(30));
```

---

## Testing & Debugging

### Unit Testing

```php
<?php

namespace Tests\Unit;

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_check_course_eligibility()
    {
        $course = Course::factory()->create([
            'application_requirement' => [1, 2, 3],
        ]);
        $user = User::factory()->create();

        // User not eligible initially
        $this->assertFalse($course->getEligibility());

        // Complete required courses
        $this->completeRequiredCourses($user, $course);

        // Now eligible
        $this->assertTrue($course->getEligibility());
    }

    private function completeRequiredCourses($user, $course)
    {
        // Create course completions
    }
}
```

### Feature Testing (API Testing)

```php
<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseApiTest extends TestCase
{
    use RefreshDatabase;

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_list_courses()
    {
        $user = User::factory()->create();
        Course::factory(5)->create();

        $response = $this->actingAs($user)
            ->getJson('/api/courses');

        $response->assertOk()
            ->assertJsonCount(5, 'data');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_can_create_course()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/courses', [
                'course_name' => 'Test Course',
                'code' => 'TEST001',
                'course_sub_category_id' => 1,
                'created_by' => $user->id,
            ]);

        $response->assertCreated();
        $this->assertDatabaseHas('courses', [
            'course_name' => 'Test Course',
        ]);
    }
}
```

---

## Important Notes & Best Practices

### 1. **Code Organization**

-   Keep controllers thin - move logic to services
-   Use repository pattern for complex queries
-   Create trait for shared functionality
-   Group related models in the same folder conceptually

### 2. **Database Best Practices**

-   Always add indexes on frequently queried columns
-   Use foreign key constraints
-   Use soft deletes for audit trail
-   Normalize data - avoid duplication
-   Use migrations for schema changes

### 3. **API Design**

-   Use HTTP status codes correctly (200, 201, 400, 401, 403, 404, 500)
-   Return consistent response format
-   Use resources to transform models
-   Implement pagination for large datasets
-   Version your API (v1, v2, etc.)

### 4. **Security**

-   Always validate input with Form Requests
-   Use policy classes for authorization
-   Hash passwords never store plaintext
-   Escape/sanitize output
-   Use HTTPS in production
-   Implement rate limiting
-   Use CSRF tokens for web routes

### 5. **Performance**

-   Use eager loading to avoid N+1 problems
-   Add database indexes strategically
-   Cache expensive computations
-   Use queue for long-running tasks
-   Implement pagination
-   Use database transactions for multi-step operations

### 6. **Testing**

-   Write tests for critical business logic
-   Use factories for test data
-   Test API endpoints
-   Test authorization/permissions
-   Use RefreshDatabase trait to isolate tests

### 7. **Code Quality**

-   Follow PSR-12 coding standards
-   Use Laravel Pint for formatting
-   Use Laravel Stan for static analysis
-   Keep methods small and focused
-   Use meaningful variable/method names
-   Add type hints to functions

### 8. **Error Handling**

-   Catch exceptions at appropriate levels
-   Log errors with context
-   Return user-friendly error messages
-   Don't expose sensitive information

### 9. **Documentation**

-   Document complex business logic
-   Add comments to non-obvious code
-   Keep README updated
-   Document API endpoints
-   Document environment variables

### 10. **Deployment**

-   Use migrations for schema changes
-   Clear cache after deployment
-   Run seeders if needed
-   Monitor error logs
-   Keep backups
-   Use environment-specific configurations

---

## Useful Artisan Commands

```bash
# Model & Migration
php artisan make:model ModelName -m --controller --request

# Controllers
php artisan make:controller Api/YourController --api

# Requests, Resources, Jobs
php artisan make:request StoreUserRequest
php artisan make:resource UserResource
php artisan make:job ProcessData

# Database
php artisan migrate
php artisan migrate:refresh --seed
php artisan migrate:rollback
php artisan db:seed

# Cache & Queue
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan queue:work

# Debugging
php artisan tinker              # Interactive shell
php artisan db:show             # Database info
php artisan route:list          # All routes
php artisan config:show app     # Show config

# Code Quality
php artisan pint                # Format code
./vendor/bin/phpstan analyse    # Static analysis
php artisan test                # Run tests

# JWT & Auth
php artisan jwt:secret          # Generate JWT secret
php artisan permissions:sync    # Sync permissions

# Logs
php artisan log:tail            # Real-time logs
```

---

## Common Issues & Solutions

### Issue 1: N+1 Query Problem

**Problem:** Loading 100 courses causes 101 queries (1 main + 100 relationship queries)

**Solution:** Use eager loading

```php
Course::with('subCategory', 'createdBy')->get();
```

---

### Issue 2: Validation Errors Not Clear

**Problem:** Form validation fails silently

**Solution:** Return detailed error messages

```php
return response()->json($validator->errors(), 422);
```

---

### Issue 3: Slow Database Queries

**Problem:** Queries taking too long

**Solution:**

-   Add database indexes
-   Use query optimization tools
-   Cache results
-   Use pagination

---

### Issue 4: Soft Delete Conflicts

**Problem:** Deleted records still appearing in queries

**Solution:** Add explicit where clause

```php
Course::where('deleted_at', null)->get();
// Or
Course::whereNull('deleted_at')->get();
```

---

### Issue 5: Cache Not Updating

**Problem:** Old cached data persists

**Solution:** Invalidate relevant caches

```php
cache()->forget('course_' . $course->id);
cache()->tags(['courses'])->flush();
```

---

## Advanced Topics

### Using Database Transactions

```php
DB::transaction(function () {
    // All operations succeed or all rollback
    Course::create([...]);
    CourseCalendar::create([...]);
    // If exception here, both roll back
});
```

### Query Logging

```php
DB::listen(function ($query) {
    Log::debug($query->sql);
    Log::debug($query->bindings);
    Log::debug($query->time . 'ms');
});
```

### Custom Caching Strategy

```php
$courses = cache()->remember(
    'courses_all',
    now()->addHours(24),
    fn () => Course::with('subCategory')->get()
);
```

### Event Broadcasting (Real-time)

```php
// Push event to websocket
broadcast(new CourseCreated($course));

// Listen in frontend
Echo.channel('courses')
    .listen('CourseCreated', (e) => {
        console.log('New course created:', e.course);
    });
```

---

## Resources & Links

-   **Laravel Documentation:** https://laravel.com/docs
-   **Eloquent ORM:** https://laravel.com/docs/eloquent
-   **API Documentation:** https://laravel.com/docs/routing
-   **Testing:** https://laravel.com/docs/testing
-   **Deployment:** https://laravel.com/docs/deployment
-   **Spatie Packages:** https://spatie.be/
-   **Laravel News:** https://laravel-news.com

---

## Project Statistics

-   **Total Models:** 300+
-   **Total API Routes:** 500+
-   **Database Tables:** 200+
-   **Controllers:** 100+
-   **Services:** 30+
-   **Migrations:** Complete

---

**Last Updated:** January 7, 2026  
**Version:** 1.0  
**Status:** Production Ready

For questions or updates, refer to Laravel documentation or project-specific documentation.

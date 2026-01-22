# EPS Backend Web - Project Architecture

**Project Name:** EPS Backend Web  
**Framework:** Laravel 10  
**PHP Version:** 8.1+  
**Architecture Pattern:** MVC + Repository Pattern  
**Last Updated:** January 7, 2026

---

## 📋 Table of Contents

1. [System Overview](#system-overview)
2. [Technology Stack](#technology-stack)
3. [Architecture Layers](#architecture-layers)
4. [Directory Structure](#directory-structure)
5. [Database Architecture](#database-architecture)
6. [Authentication & Authorization](#authentication--authorization)
7. [API Architecture](#api-architecture)
8. [Business Modules](#business-modules)
9. [Design Patterns](#design-patterns)
10. [External Integrations](#external-integrations)
11. [Caching Strategy](#caching-strategy)
12. [File Storage Architecture](#file-storage-architecture)
13. [Queue & Job Processing](#queue--job-processing)
14. [Security Architecture](#security-architecture)
15. [Deployment Architecture](#deployment-architecture)

---

## System Overview

### Purpose

EPS (Electronic Personnel System) Backend Web is a comprehensive REST API backend system designed to manage training courses, examinations, facilities, inspections, and personnel management for government agencies.

### Key Capabilities

-   **Course Management**: Training programs, sessions, participants, evaluations
-   **Examination System**: Exam creation, scheduling, grading, results
-   **Facility Management**: Venue bookings, resources, maintenance
-   **Inspectorate Module**: Audits, inspections, compliance tracking
-   **User Management**: Multi-agency user administration
-   **System Configuration**: Dynamic settings and parameters

### Architecture Type

**Monolithic REST API** with modular business domains, designed for:

-   High scalability
-   Multi-tenancy support
-   Role-based access control
-   Audit logging
-   Real-time notifications

---

## Technology Stack

### Core Framework

```
Laravel 10.x
├── PHP 8.1+
├── Composer 2.x
└── PSR-4 Autoloading
```

### Database Layer

```
MySQL 8.0 / MariaDB 10.x
├── InnoDB Engine
├── UTF8MB4 Charset
└── Full ACID Compliance
```

### Caching Layer

```
Redis 6.x
├── Session Management
├── Query Caching
└── Rate Limiting
```

### Authentication

```
JWT (tymon/jwt-auth v2.1)
├── Token-based Authentication
├── Refresh Token Support
└── Blacklist Management

Keycloak SSO (robsontenorio/laravel-keycloak-guard v1.5)
├── Single Sign-On
├── OAuth 2.0 / OpenID Connect
└── Role Mapping
```

### Key Packages

#### Authorization & Permissions

```php
spatie/laravel-permission ^5.10
├── Role-Based Access Control (RBAC)
├── Permission Management
└── Guard Support
```

#### Audit Logging

```php
owen-it/laravel-auditing ^13.5
├── Model Change Tracking
├── User Activity Logging
└── IP Address Recording
```

#### Media Management

```php
spatie/laravel-medialibrary ^10.0
├── File Upload Handling
├── Image Manipulation
└── Media Collections
```

#### Excel Processing

```php
maatwebsite/laravel-excel ^3.1
├── Excel Import
├── Excel Export
└── CSV Support
```

#### PDF Generation

```php
barryvdh/laravel-dompdf ^2.0
├── HTML to PDF Conversion
├── Report Generation
└── Document Export
```

#### Additional Packages

```
- guzzlehttp/guzzle (HTTP Client)
- intervention/image (Image Processing)
- predis/predis (Redis Client)
- laravel/sanctum (SPA Authentication)
- fruitcake/laravel-cors (CORS Handling)
```

---

## Architecture Layers

### 1. Presentation Layer (API)

```
routes/api.php
    ↓
app/Http/Controllers/
    ├── API/
    │   ├── CourseController
    │   ├── ExamController
    │   ├── FacilityController
    │   └── ...
    └── Auth/
        ├── LoginController
        └── RegisterController
```

**Responsibilities:**

-   Request handling
-   Input validation
-   Response formatting
-   HTTP status codes
-   API versioning

### 2. Business Logic Layer

```
app/Services/
    ├── CourseService
    ├── ExamService
    ├── FacilityService
    └── NotificationService
```

**Responsibilities:**

-   Business rules enforcement
-   Transaction management
-   Complex calculations
-   Workflow orchestration
-   Cross-module operations

### 3. Data Access Layer

```
app/Models/
    ├── Course
    ├── Exam
    ├── Facility
    └── User (300+ models)
```

**Responsibilities:**

-   Database interaction
-   Eloquent ORM operations
-   Relationships management
-   Query scopes
-   Model events

### 4. Infrastructure Layer

```
app/
    ├── Helpers/          # Utility functions
    ├── Traits/           # Reusable traits
    ├── Observers/        # Model observers
    ├── Jobs/             # Queue jobs
    ├── Mail/             # Email templates
    └── Notifications/    # Push notifications
```

---

## Directory Structure

```
eps-be-web/
├── app/
│   ├── Casts/                      # Custom attribute casts
│   ├── Console/                    # Artisan commands
│   │   ├── Commands/
│   │   └── Kernel.php
│   ├── Exceptions/                 # Exception handling
│   │   └── Handler.php
│   ├── Exports/                    # Excel export classes
│   │   ├── CourseExport.php
│   │   └── ParticipantExport.php
│   ├── Guards/                     # Custom authentication guards
│   ├── Helpers/                    # Helper functions
│   │   ├── ResponseHelper.php
│   │   ├── DateHelper.php
│   │   └── FileHelper.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── API/               # API controllers
│   │   │   └── Auth/              # Authentication
│   │   ├── Middleware/
│   │   │   ├── Authenticate.php
│   │   │   ├── CheckPermission.php
│   │   │   └── LogActivity.php
│   │   ├── Requests/              # Form requests
│   │   │   ├── StoreCourseRequest.php
│   │   │   └── UpdateCourseRequest.php
│   │   └── Resources/             # API resources
│   │       ├── CourseResource.php
│   │       └── UserResource.php
│   ├── Imports/                    # Excel import classes
│   ├── Jobs/                       # Queue jobs
│   │   ├── SendEmailJob.php
│   │   └── ProcessReportJob.php
│   ├── Mail/                       # Mailable classes
│   │   ├── CourseInvitation.php
│   │   └── ExamResults.php
│   ├── Models/                     # Eloquent models (300+)
│   │   ├── Course.php
│   │   ├── CourseSession.php
│   │   ├── CourseParticipant.php
│   │   ├── Exam.php
│   │   ├── Facility.php
│   │   └── User.php
│   ├── Notifications/              # Notification classes
│   ├── Observers/                  # Model observers
│   │   ├── CourseObserver.php
│   │   └── UserObserver.php
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   ├── AuthServiceProvider.php
│   │   ├── EventServiceProvider.php
│   │   └── RouteServiceProvider.php
│   ├── Services/                   # Business logic services
│   │   ├── CourseService.php
│   │   ├── ExamService.php
│   │   ├── FacilityService.php
│   │   └── NotificationService.php
│   ├── Traits/                     # Reusable traits
│   │   ├── HasAudit.php
│   │   ├── HasStatus.php
│   │   └── Searchable.php
│   └── View/                       # View composers
├── bootstrap/
│   ├── app.php                     # Application bootstrap
│   └── cache/                      # Compiled files
├── config/                         # Configuration files
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── jwt.php
│   ├── keycloak.php
│   ├── permission.php
│   ├── audit.php
│   └── filesystems.php
├── database/
│   ├── factories/                  # Model factories
│   ├── migrations/                 # Database migrations
│   └── seeders/                    # Database seeders
├── public/
│   ├── index.php                   # Application entry point
│   └── storage/                    # Public storage symlink
├── resources/
│   ├── views/                      # Blade templates
│   │   ├── pdf/                   # PDF templates
│   │   └── emails/                # Email templates
│   ├── css/
│   └── js/
├── routes/
│   ├── api.php                     # API routes (500+)
│   ├── web.php                     # Web routes
│   ├── channels.php                # Broadcast channels
│   └── console.php                 # Console routes
├── storage/
│   ├── app/
│   │   ├── public/                # Public files
│   │   └── private/               # Private files
│   ├── framework/
│   │   ├── cache/
│   │   ├── sessions/
│   │   └── views/
│   └── logs/
│       └── laravel.log
├── tests/
│   ├── Feature/                    # Feature tests
│   └── Unit/                       # Unit tests
└── vendor/                         # Composer dependencies
```

---

## Database Architecture

### Database Design Principles

-   **Normalization**: Third Normal Form (3NF)
-   **Indexing**: Strategic indexes on foreign keys and search fields
-   **Soft Deletes**: Logical deletion for audit trails
-   **Timestamps**: created_at, updated_at on all tables
-   **UUID Support**: Optional UUID primary keys

### Core Tables

#### 1. User Management

```sql
users
├── id (PK)
├── name
├── email (UNIQUE)
├── password
├── agency_id (FK)
├── status
├── created_at
└── updated_at

agencies
├── id (PK)
├── name
├── code (UNIQUE)
├── parent_agency_id (FK)
└── timestamps

roles
├── id (PK)
├── name (UNIQUE)
├── guard_name
└── timestamps

permissions
├── id (PK)
├── name (UNIQUE)
├── guard_name
└── timestamps

model_has_roles (Pivot)
model_has_permissions (Pivot)
role_has_permissions (Pivot)
```

#### 2. Course Management

```sql
courses
├── id (PK)
├── course_name
├── code (UNIQUE)
├── course_sub_category_id (FK)
├── description
├── duration_days
├── max_participants
├── status
├── created_by (FK)
└── timestamps

course_sessions
├── id (PK)
├── course_id (FK)
├── session_name
├── start_date
├── end_date
├── facility_id (FK)
├── status
└── timestamps

course_participants
├── id (PK)
├── course_session_id (FK)
├── user_id (FK)
├── registration_status
├── attendance_status
├── grade
└── timestamps

course_agendas
├── id (PK)
├── course_session_id (FK)
├── date
├── start_time
├── end_time
├── topic
├── instructor_id (FK)
└── timestamps
```

#### 3. Examination System

```sql
exams
├── id (PK)
├── exam_name
├── course_id (FK)
├── exam_type
├── duration_minutes
├── passing_score
├── status
└── timestamps

exam_questions
├── id (PK)
├── exam_id (FK)
├── question_text
├── question_type
├── points
├── order
└── timestamps

exam_attempts
├── id (PK)
├── exam_id (FK)
├── user_id (FK)
├── start_time
├── end_time
├── score
├── status
└── timestamps

exam_answers
├── id (PK)
├── exam_attempt_id (FK)
├── question_id (FK)
├── answer
├── is_correct
└── timestamps
```

#### 4. Facility Management

```sql
facilities
├── id (PK)
├── facility_name
├── facility_type
├── capacity
├── location
├── status
└── timestamps

facility_bookings
├── id (PK)
├── facility_id (FK)
├── booked_by (FK)
├── start_datetime
├── end_datetime
├── purpose
├── status
└── timestamps

facility_resources
├── id (PK)
├── facility_id (FK)
├── resource_name
├── quantity
└── timestamps
```

#### 5. Audit Logging

```sql
audits
├── id (PK)
├── user_id (FK)
├── auditable_type
├── auditable_id
├── event (created, updated, deleted)
├── old_values (JSON)
├── new_values (JSON)
├── url
├── ip_address
├── user_agent
└── created_at

INDEX on (auditable_type, auditable_id)
INDEX on (user_id, created_at)
```

#### 6. Media Library

```sql
media
├── id (PK)
├── model_type
├── model_id
├── collection_name
├── name
├── file_name
├── mime_type
├── disk
├── size
├── custom_properties (JSON)
└── timestamps

INDEX on (model_type, model_id)
```

### Relationships Summary

-   **One-to-Many**: User → Courses, Course → Sessions, Session → Participants
-   **Many-to-Many**: Users ↔ Roles, Roles ↔ Permissions
-   **Polymorphic**: Media (can attach to any model)
-   **Self-Referencing**: Agencies (parent-child hierarchy)

### Indexing Strategy

```sql
-- Foreign keys
INDEX idx_course_sessions_course_id ON course_sessions(course_id)
INDEX idx_course_participants_user_id ON course_participants(user_id)

-- Search fields
INDEX idx_courses_code ON courses(code)
INDEX idx_users_email ON users(email)

-- Status fields (frequently filtered)
INDEX idx_courses_status ON courses(status)
INDEX idx_course_sessions_status ON course_sessions(status)

-- Composite indexes
INDEX idx_course_sessions_dates ON course_sessions(start_date, end_date)
INDEX idx_bookings_facility_dates ON facility_bookings(facility_id, start_datetime, end_datetime)
```

---

## Authentication & Authorization

### Authentication Flow

#### JWT Authentication

```
Client Request
    ↓
POST /api/auth/login
    ↓
Validate Credentials
    ↓
Generate JWT Token (HS256)
    ├── Header: {"alg": "HS256", "typ": "JWT"}
    ├── Payload: {"sub": user_id, "exp": timestamp}
    └── Signature: HMACSHA256(header + payload + secret)
    ↓
Return Token
    ↓
Client stores token
    ↓
Subsequent Requests
    ↓
Authorization: Bearer <token>
    ↓
Middleware validates token
    ↓
Extract user from token
    ↓
Process request
```

#### Keycloak SSO Authentication

```
Client Request
    ↓
Redirect to Keycloak
    ↓
User Login at Keycloak
    ↓
Keycloak validates credentials
    ↓
Keycloak generates access token (RS256)
    ↓
Redirect back with token
    ↓
Laravel validates token signature
    ├── Verify with Keycloak public key
    ├── Check token expiration
    └── Extract user info & roles
    ↓
Create/update local user
    ↓
Sync roles from Keycloak
    ↓
Grant access
```

### Authorization Architecture

#### Role-Based Access Control (RBAC)

```
User
    ├── has many Roles
    │       ├── super_admin
    │       ├── course_manager
    │       ├── course_instructor
    │       ├── course_student
    │       ├── facility_manager
    │       └── auditor
    └── has many Permissions
            ├── view-courses
            ├── create-courses
            ├── edit-courses
            ├── delete-courses
            └── ...
```

#### Permission Checking Flow

```php
// 1. Middleware Level
Route::middleware('permission:edit-courses')->put('/courses/{id}', ...);

// 2. Controller Level
public function update(Request $request, Course $course)
{
    $this->authorize('update', $course);
    // Logic
}

// 3. Policy Level
class CoursePolicy
{
    public function update(User $user, Course $course)
    {
        return $user->hasPermissionTo('edit-courses')
            || $user->id === $course->created_by;
    }
}

// 4. Blade Level
@can('edit-courses')
    <button>Edit Course</button>
@endcan
```

#### Guard Configuration

```php
// config/auth.php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
    'keycloak' => [
        'driver' => 'keycloak',
        'provider' => 'users',
    ],
],
```

---

## API Architecture

### RESTful API Design

#### Endpoint Structure

```
/api/v1/{resource}
```

#### Standard Resource Operations

```http
GET    /api/courses              # List all courses
POST   /api/courses              # Create new course
GET    /api/courses/{id}         # Get single course
PUT    /api/courses/{id}         # Update course
DELETE /api/courses/{id}         # Delete course

# Nested Resources
GET    /api/courses/{id}/sessions
POST   /api/courses/{id}/sessions
GET    /api/courses/{id}/participants
```

### Response Format

#### Success Response

```json
{
    "success": true,
    "message": "Course retrieved successfully",
    "data": {
        "id": 1,
        "course_name": "Laravel Development",
        "code": "LAR-001",
        "status": "active"
    },
    "meta": {
        "timestamp": "2026-01-07T12:00:00Z"
    }
}
```

#### Error Response

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "course_name": ["The course name field is required."],
        "code": ["The code has already been taken."]
    },
    "meta": {
        "timestamp": "2026-01-07T12:00:00Z"
    }
}
```

#### Pagination Response

```json
{
    "success": true,
    "data": [...],
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 10,
        "per_page": 20,
        "to": 20,
        "total": 200
    },
    "links": {
        "first": "/api/courses?page=1",
        "last": "/api/courses?page=10",
        "prev": null,
        "next": "/api/courses?page=2"
    }
}
```

### API Versioning

```php
// routes/api.php
Route::prefix('v1')->group(function () {
    Route::apiResource('courses', CourseController::class);
});

Route::prefix('v2')->group(function () {
    Route::apiResource('courses', V2\CourseController::class);
});
```

### Rate Limiting

```php
// app/Http/Kernel.php
protected $middlewareGroups = [
    'api' => [
        'throttle:60,1', // 60 requests per minute
    ],
];

// Custom rate limits
Route::middleware('throttle:10,1')->group(function () {
    // Limited routes
});
```

---

## Business Modules

### 1. Course Management Module

```
app/Http/Controllers/API/Course/
    ├── CourseController          # CRUD operations
    ├── CourseSessionController   # Session management
    ├── CourseParticipantController
    ├── CourseAgendaController
    ├── CourseEvaluationController
    └── CourseReportController

app/Models/
    ├── Course
    ├── CourseCategory
    ├── CourseSubCategory
    ├── CourseSession
    ├── CourseParticipant
    ├── CourseAgenda
    └── CourseEvaluation
```

**Features:**

-   Course catalog management
-   Session scheduling
-   Participant enrollment
-   Attendance tracking
-   Evaluation and feedback
-   Certificate generation
-   Reporting and analytics

### 2. Examination Module

```
app/Http/Controllers/API/Exam/
    ├── ExamController
    ├── ExamQuestionController
    ├── ExamAttemptController
    └── ExamResultController

app/Models/
    ├── Exam
    ├── ExamQuestion
    ├── ExamAnswer
    ├── ExamAttempt
    └── ExamResult
```

**Features:**

-   Exam creation and management
-   Question bank
-   Multiple question types (MCQ, Essay, etc.)
-   Timed exams
-   Auto-grading
-   Result analysis
-   Score reports

### 3. Facility Management Module

```
app/Http/Controllers/API/Facility/
    ├── FacilityController
    ├── FacilityBookingController
    ├── FacilityResourceController
    └── FacilityMaintenanceController

app/Models/
    ├── Facility
    ├── FacilityType
    ├── FacilityBooking
    ├── FacilityResource
    └── FacilityMaintenance
```

**Features:**

-   Venue management
-   Booking system
-   Resource allocation
-   Maintenance tracking
-   Capacity management
-   Conflict detection

### 4. Inspectorate Module

```
app/Http/Controllers/API/Inspectorate/
    ├── InspectionController
    ├── AuditController
    ├── ComplianceController
    └── InspectionReportController

app/Models/
    ├── Inspection
    ├── InspectionChecklist
    ├── InspectionFinding
    └── InspectionReport
```

**Features:**

-   Inspection scheduling
-   Checklist management
-   Finding recording
-   Follow-up tracking
-   Compliance reporting

### 5. User & Agency Management

```
app/Http/Controllers/API/User/
    ├── UserController
    ├── AgencyController
    ├── RoleController
    └── PermissionController

app/Models/
    ├── User
    ├── Agency
    ├── Role (Spatie)
    └── Permission (Spatie)
```

**Features:**

-   User CRUD operations
-   Agency hierarchy
-   Role assignment
-   Permission management
-   User profile
-   Activity tracking

### 6. System Configuration

```
app/Http/Controllers/API/Config/
    ├── SystemConfigController
    ├── ParameterController
    └── LookupController

app/Models/
    ├── SystemConfig
    ├── Parameter
    └── Lookup
```

**Features:**

-   Dynamic configuration
-   System parameters
-   Lookup tables
-   Feature toggles
-   Environment settings

---

## Design Patterns

### 1. Repository Pattern

```php
// app/Repositories/CourseRepository.php
class CourseRepository
{
    public function findById($id)
    {
        return Course::with('sessions', 'participants')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Course::create($data);
    }
}

// Usage in Controller
public function __construct(CourseRepository $courseRepository)
{
    $this->courseRepository = $courseRepository;
}
```

### 2. Service Layer Pattern

```php
// app/Services/CourseService.php
class CourseService
{
    public function createCourseWithSessions(array $courseData, array $sessions)
    {
        DB::transaction(function () use ($courseData, $sessions) {
            $course = Course::create($courseData);

            foreach ($sessions as $sessionData) {
                $course->sessions()->create($sessionData);
            }

            event(new CourseCreated($course));

            return $course;
        });
    }
}
```

### 3. Observer Pattern

```php
// app/Observers/CourseObserver.php
class CourseObserver
{
    public function created(Course $course)
    {
        activity()
            ->performedOn($course)
            ->log('Course created');
    }

    public function updated(Course $course)
    {
        if ($course->isDirty('status')) {
            event(new CourseStatusChanged($course));
        }
    }
}

// Register in AppServiceProvider
Course::observe(CourseObserver::class);
```

### 4. Factory Pattern

```php
// database/factories/CourseFactory.php
class CourseFactory extends Factory
{
    public function definition()
    {
        return [
            'course_name' => $this->faker->sentence(3),
            'code' => $this->faker->unique()->bothify('CRS-####'),
            'status' => 'active',
        ];
    }
}

// Usage
Course::factory()->count(50)->create();
```

### 5. Strategy Pattern

```php
// app/Services/Notification/
interface NotificationChannel
{
    public function send($recipient, $message);
}

class EmailNotification implements NotificationChannel
{
    public function send($recipient, $message)
    {
        Mail::to($recipient)->send(new GenericMail($message));
    }
}

class SMSNotification implements NotificationChannel
{
    public function send($recipient, $message)
    {
        // SMS logic
    }
}

// Usage
$notifier = new NotificationService($channel);
$notifier->send($user, $message);
```

---

## External Integrations

### 1. Keycloak SSO

```
Purpose: Single Sign-On authentication
Protocol: OAuth 2.0 / OpenID Connect
Integration: robsontenorio/laravel-keycloak-guard
Flow: Authorization Code Flow

Configuration:
- Realm: eps
- Client: eps_backend
- Token Format: JWT (RS256)
- Token Lifespan: 60 minutes
```

### 2. S3 Storage (AWS)

```
Purpose: File storage
Service: Amazon S3
Integration: league/flysystem-aws-s3-v3

Configuration:
- Bucket: eps-backend-storage
- Region: us-east-1
- Access: IAM User with S3 permissions
```

### 3. Redis

```
Purpose: Caching, sessions, queues
Version: 6.x
Integration: predis/predis

Usage:
- Cache: Application data caching
- Sessions: User session storage
- Queues: Job queue backend
- Rate Limiting: API throttling
```

### 4. SMTP Mail Service

```
Purpose: Email notifications
Integration: Native Laravel Mail
Supported: Gmail, Mailgun, Mailtrap, AWS SES

Configuration:
- Driver: SMTP
- Port: 587 (TLS) or 465 (SSL)
- Authentication: Required
```

### 5. ClamAV (Optional)

```
Purpose: Virus scanning
Integration: Custom service
Usage: Scan uploaded files before storage
```

---

## Caching Strategy

### Cache Layers

#### 1. Application Cache (Redis)

```php
// Cache expensive queries
$courses = cache()->remember('active_courses', 3600, function () {
    return Course::where('status', 'active')
        ->with('category')
        ->get();
});

// Cache computed values
$statistics = cache()->remember("course_stats_{$courseId}", 1800, function () use ($courseId) {
    return [
        'total_participants' => CourseParticipant::where('course_id', $courseId)->count(),
        'completion_rate' => /* calculation */,
    ];
});
```

#### 2. Query Result Cache

```php
// Use query builder cache
$courses = Course::where('status', 'active')
    ->remember(3600)
    ->get();
```

#### 3. Route Cache

```bash
# Production optimization
php artisan route:cache
php artisan config:cache
php artisan view:cache
```

#### 4. OPcache (PHP)

```ini
; php.ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=10000
opcache.revalidate_freq=0
```

### Cache Tags

```php
// Grouped caching
cache()->tags(['courses', 'active'])->put('key', $value, 3600);
cache()->tags(['courses'])->flush(); // Clear all course caches
```

### Cache Invalidation

```php
// Clear specific cache
cache()->forget('active_courses');

// Clear by tags
cache()->tags(['courses'])->flush();

// Clear all
cache()->flush();
php artisan cache:clear
```

---

## File Storage Architecture

### Storage Disks

```php
// config/filesystems.php
'disks' => [
    'local' => [
        'driver' => 'local',
        'root' => storage_path('app'),
    ],

    'public' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL').'/storage',
        'visibility' => 'public',
    ],

    's3' => [
        'driver' => 's3',
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
        'bucket' => env('AWS_BUCKET'),
    ],
],
```

### Media Collections (Spatie)

```php
class Course extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png']);

        $this->addMediaCollection('materials')
            ->acceptsMimeTypes(['application/pdf', 'application/msword']);
    }
}
```

### File Upload Flow

```
Client Upload
    ↓
Validate File (size, type, virus scan)
    ↓
Generate Unique Filename
    ↓
Store in Disk (local/S3)
    ↓
Create Media Record
    ↓
Generate Thumbnail (if image)
    ↓
Return File URL
```

---

## Queue & Job Processing

### Queue Architecture

```
Job Dispatched
    ↓
Queued in Redis
    ↓
Worker Process Picks Job
    ↓
Execute Job Logic
    ↓
Success → Mark Complete
Fail → Retry (max 3 attempts)
    ↓
If max retries → Move to failed_jobs
```

### Job Types

```php
// app/Jobs/
SendEmailJob          # Email notifications
ProcessReportJob      # Generate reports
ExportDataJob         # Excel exports
ImportDataJob         # Excel imports
CleanupFilesJob       # File maintenance
```

### Queue Configuration

```php
// config/queue.php
'connections' => [
    'sync' => [
        'driver' => 'sync', // Development
    ],

    'redis' => [
        'driver' => 'redis', // Production
        'connection' => 'default',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
    ],
],
```

### Job Dispatching

```php
// Dispatch immediately
ProcessReportJob::dispatch($reportId);

// Dispatch with delay
SendEmailJob::dispatch($user)->delay(now()->addMinutes(5));

// Dispatch to specific queue
ExportDataJob::dispatch($data)->onQueue('exports');

// Chain jobs
ProcessReportJob::withChain([
    new SendEmailJob($user),
    new CleanupFilesJob(),
])->dispatch();
```

---

## Security Architecture

### Security Layers

#### 1. Authentication Security

-   **JWT Token**: HS256 algorithm, secure secret
-   **Token Expiration**: 60 minutes access, 7 days refresh
-   **Token Blacklist**: Revoked tokens
-   **Password Hashing**: Bcrypt (cost 10)
-   **2FA Support**: Google Authenticator

#### 2. Authorization Security

-   **RBAC**: Role-based access control
-   **Policies**: Resource-level authorization
-   **Gates**: Custom authorization logic
-   **Middleware**: Route protection

#### 3. Input Validation

```php
// Form Request Validation
class StoreCourseRequest extends FormRequest
{
    public function rules()
    {
        return [
            'course_name' => 'required|string|max:255',
            'code' => 'required|unique:courses|regex:/^[A-Z0-9-]+$/',
            'email' => 'required|email',
        ];
    }
}
```

#### 4. SQL Injection Prevention

-   **Eloquent ORM**: Parameterized queries
-   **Query Builder**: Automatic escaping
-   **Raw Queries**: Use bindings

```php
// Safe
Course::where('code', $code)->first();
DB::table('courses')->where('code', $code)->first();
DB::select('SELECT * FROM courses WHERE code = ?', [$code]);

// Unsafe - NEVER DO THIS
DB::select("SELECT * FROM courses WHERE code = '$code'");
```

#### 4. XSS Prevention

-   **Blade Escaping**: Automatic `{{ $variable }}`
-   **Raw Output**: Only use `{!! $html !!}` for trusted content
-   **Content Security Policy**: Headers

#### 5. CSRF Protection

```php
// Automatic for web routes
Route::post('/form', function (Request $request) {
    // CSRF token validated
});

// Excluded for API routes
protected $except = [
    'api/*',
];
```

#### 6. Rate Limiting

```php
// Throttle middleware
Route::middleware('throttle:60,1')->group(function () {
    // 60 requests per minute
});

// Login rate limiting
Route::middleware('throttle:5,1')->post('/login', ...);
```

#### 7. CORS Configuration

```php
// config/cors.php
'paths' => ['api/*'],
'allowed_origins' => [
    'https://eps-frontend.gov.my',
],
'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE'],
'allowed_headers' => ['Content-Type', 'Authorization'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => true,
```

#### 8. Sensitive Data Protection

-   **Environment Variables**: .env for secrets
-   **Encrypted Values**: `encrypted` cast for sensitive fields
-   **Hidden Attributes**: Hide passwords from JSON

```php
class User extends Model
{
    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'ssn' => 'encrypted',
    ];
}
```

---

## Deployment Architecture

### Development Environment

```
Local Machine (Laragon/XAMPP)
├── PHP 8.1 (Development Server)
├── MySQL 8.0
├── Redis (Optional)
└── Keycloak (Docker)

Tools:
- Composer
- NPM
- Git
- Postman/Insomnia
```

### Staging Environment

```
Server
├── Nginx
├── PHP-FPM 8.1
├── MySQL 8.0
├── Redis
└── Supervisor (Queue Workers)

Services:
- Keycloak (Docker/Standalone)
- S3 Storage (AWS/MinIO)
```

### Production Environment

```
Load Balancer
    ↓
├── App Server 1
│   ├── Nginx
│   ├── PHP-FPM 8.1
│   └── Supervisor
├── App Server 2
│   ├── Nginx
│   ├── PHP-FPM 8.1
│   └── Supervisor
    ↓
Database Cluster
├── Master (Read/Write)
└── Replica (Read-only)
    ↓
Redis Cluster
├── Primary
└── Replicas
    ↓
External Services
├── Keycloak (HA Setup)
├── S3 Storage
└── Email Service
```

### Deployment Process

```bash
# 1. Pull latest code
git pull origin main

# 2. Install dependencies
composer install --no-dev --optimize-autoloader

# 3. Run migrations
php artisan migrate --force

# 4. Clear and cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Restart services
sudo supervisorctl restart all
sudo systemctl reload php8.1-fpm
sudo systemctl reload nginx

# 6. Restart queue workers
php artisan queue:restart
```

### Monitoring & Logging

```
Application Logs:
- storage/logs/laravel.log
- Daily rotation

Web Server Logs:
- /var/log/nginx/access.log
- /var/log/nginx/error.log

Database Logs:
- MySQL slow query log
- General query log (dev only)

Queue Monitoring:
- Laravel Horizon (recommended)
- Supervisor logs
```

---

## Performance Optimization

### Database Optimization

-   Eager loading to prevent N+1 queries
-   Strategic indexing on foreign keys and search fields
-   Query result caching
-   Database connection pooling

### Application Optimization

-   OPcache enabled
-   Route/config/view caching in production
-   Redis for session and cache
-   Queue for long-running tasks

### Frontend Optimization

-   Asset compilation (Vite)
-   CSS/JS minification
-   Image optimization
-   CDN for static assets

---

## Scalability Considerations

### Horizontal Scaling

-   Stateless application servers
-   Session storage in Redis (shared)
-   Load balancer for traffic distribution

### Database Scaling

-   Master-slave replication
-   Read/write splitting
-   Connection pooling

### Cache Scaling

-   Redis cluster for high availability
-   Cache warming strategies
-   Distributed caching

### Queue Scaling

-   Multiple queue workers
-   Queue prioritization
-   Horizon for monitoring

---

## Development Best Practices

### Code Standards

-   PSR-12 coding standards
-   Type hints and return types
-   DocBlocks for classes and methods
-   Meaningful variable names

### Version Control

-   Git flow branching strategy
-   Feature branches
-   Pull request reviews
-   Semantic versioning

### Testing

-   Unit tests for business logic
-   Feature tests for API endpoints
-   Database factories and seeders
-   PHPUnit for test execution

### Documentation

-   README.md for project overview
-   API documentation (Postman/Swagger)
-   Code comments for complex logic
-   Architecture documentation (this file)

---

## Technology Decisions

### Why Laravel?

-   Mature ecosystem
-   Eloquent ORM for database operations
-   Built-in authentication and authorization
-   Excellent package ecosystem
-   Active community support

### Why JWT?

-   Stateless authentication
-   Scalable across multiple servers
-   Standard format (RFC 7519)
-   Mobile-friendly

### Why Keycloak?

-   Enterprise-grade SSO
-   Open-source and free
-   OAuth 2.0 / OpenID Connect compliant
-   Centralized user management
-   Role and permission management

### Why Redis?

-   In-memory performance
-   Versatile (cache, session, queue)
-   Persistence options
-   Pub/sub capabilities

### Why Spatie Packages?

-   Well-maintained
-   Excellent documentation
-   Laravel community standard
-   Active development

---

## Conclusion

The EPS Backend Web project follows modern Laravel best practices with a modular, scalable architecture. The system is designed for:

-   **Maintainability**: Clear separation of concerns
-   **Scalability**: Horizontal scaling capability
-   **Security**: Multiple security layers
-   **Performance**: Optimized queries and caching
-   **Flexibility**: Easy to extend and modify

---

**Document Version:** 1.0  
**Author:** EPS Development Team  
**Last Updated:** January 7, 2026  
**Status:** Production Architecture

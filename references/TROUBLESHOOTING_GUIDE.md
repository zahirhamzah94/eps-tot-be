# EPS Backend Web - Comprehensive Troubleshooting Guide

**Project:** EPS Backend Web (Laravel 10)  
**Last Updated:** January 7, 2026  
**Version:** 1.0

---

## 📋 Table of Contents

1. [Installation & Setup Issues](#installation--setup-issues)
2. [Database Problems](#database-problems)
3. [Authentication & Authorization Issues](#authentication--authorization-issues)
4. [Keycloak SSO Problems](#keycloak-sso-problems)
5. [API & Route Issues](#api--route-issues)
6. [File Upload & Media Issues](#file-upload--media-issues)
7. [Performance & Query Issues](#performance--query-issues)
8. [Cache & Session Problems](#cache--session-problems)
9. [Email & Notification Issues](#email--notification-issues)
10. [Queue & Job Issues](#queue--job-issues)
11. [Permission & Role Issues](#permission--role-issues)
12. [Migration & Seeding Problems](#migration--seeding-problems)
13. [Audit Logging Issues](#audit-logging-issues)
14. [Excel Export/Import Issues](#excel-exportimport-issues)
15. [PDF Generation Issues](#pdf-generation-issues)
16. [CORS & Cross-Origin Issues](#cors--cross-origin-issues)
17. [Docker & Deployment Issues](#docker--deployment-issues)
18. [Development Environment Issues](#development-environment-issues)
19. [Third-Party Integration Issues](#third-party-integration-issues)
20. [Production Issues](#production-issues)

---

## Installation & Setup Issues

### Issue 1.1: Composer Install Fails

**Symptom:**

```
Your requirements could not be resolved to an installable set of packages.
```

**Causes:**

-   PHP version mismatch
-   Memory limit too low
-   Missing PHP extensions
-   Composer cache corruption

**Solutions:**

```powershell
# Check PHP version
php -v  # Should be 8.1 or higher

# Increase memory limit
php -d memory_limit=2G composer install

# Clear composer cache
composer clear-cache
composer install --no-cache

# Install missing PHP extensions
# Check required extensions in composer.json
php -m  # List installed extensions

# On Windows (Laragon/XAMPP):
# Edit php.ini and enable:
extension=pdo_mysql
extension=mbstring
extension=fileinfo
extension=openssl
extension=tokenizer
extension=xml
extension=ctype
extension=json
extension=bcmath
extension=zip
```

---

### Issue 1.2: Application Key Not Set

**Symptom:**

```
No application encryption key has been specified.
```

**Solution:**

```powershell
# Generate application key
php artisan key:generate

# Verify in .env
# APP_KEY=base64:...should be populated

# If still fails, manually set in .env
APP_KEY=base64:YOUR_GENERATED_KEY_HERE

# Clear config cache
php artisan config:clear
php artisan config:cache
```

---

### Issue 1.3: Storage Link Not Created

**Symptom:**

```
Files uploaded but not accessible via URL
404 error on storage URLs
```

**Solution:**

```powershell
# Create storage symlink
php artisan storage:link

# Verify symlink exists
ls public/storage

# If symlink fails on Windows
# Run PowerShell as Administrator:
New-Item -ItemType SymbolicLink -Path "public\storage" -Target "..\storage\app\public"

# Or use mklink in CMD (Administrator):
mklink /D "public\storage" "..\storage\app\public"

# Set proper permissions
icacls storage /grant Users:F /T
icacls bootstrap\cache /grant Users:F /T
```

---

### Issue 1.4: Permission Denied Errors

**Symptom:**

```
file_put_contents(...): failed to open stream: Permission denied
```

**Solution:**

```powershell
# Windows - Set folder permissions
icacls storage /grant Users:F /T
icacls bootstrap\cache /grant Users:F /T
icacls public /grant Users:F /T

# Linux/Mac
chmod -R 775 storage
chmod -R 775 bootstrap/cache
sudo chown -R www-data:www-data storage bootstrap/cache

# Check current permissions
Get-Acl storage | Format-List
```

---

### Issue 1.5: .env File Not Loaded

**Symptom:**

```
Configuration values are null
Environment variables not working
```

**Solution:**

```powershell
# Verify .env exists
ls .env

# If not, copy from example
cp .env.example .env

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Check if .env is being loaded
php artisan tinker
>>> config('app.name')  # Should return your app name

# Ensure no extra spaces in .env
# Remove any BOM characters
# Use UTF-8 encoding without BOM
```

---

## Database Problems

### Issue 2.1: Connection Refused / Can't Connect

**Symptom:**

```
SQLSTATE[HY000] [2002] Connection refused
SQLSTATE[HY000] [1045] Access denied for user
```

**Solutions:**

```powershell
# Check MySQL is running
# On Windows (Laragon):
# Open Laragon → Check if MySQL is started

# Test connection manually
mysql -u root -p -h 127.0.0.1

# Verify .env database settings
DB_CONNECTION=mysql
DB_HOST=127.0.0.1  # Try 127.0.0.1 instead of localhost
DB_PORT=3306
DB_DATABASE=eps_be_web
DB_USERNAME=root
DB_PASSWORD=

# Clear config cache
php artisan config:clear

# Check MySQL service
Get-Service mysql*  # Windows
sudo systemctl status mysql  # Linux

# Restart MySQL
# Laragon: Stop and start MySQL
# Linux: sudo systemctl restart mysql
```

---

### Issue 2.2: Database Does Not Exist

**Symptom:**

```
SQLSTATE[HY000] [1049] Unknown database 'eps_be_web'
```

**Solution:**

```powershell
# Create database manually
mysql -u root -p

# In MySQL:
CREATE DATABASE eps_be_web CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
SHOW DATABASES;
EXIT;

# Or use artisan (if you have a command)
php artisan db:create

# Verify database in .env
DB_DATABASE=eps_be_web

# Run migrations
php artisan migrate
```

---

### Issue 2.3: Migration Fails

**Symptom:**

```
SQLSTATE[42S01]: Base table or view already exists
SQLSTATE[42000]: Syntax error or access violation
```

**Solutions:**

```powershell
# Check migration status
php artisan migrate:status

# Fresh migration (WARNING: Deletes all data)
php artisan migrate:fresh

# Rollback and re-migrate
php artisan migrate:rollback
php artisan migrate

# Rollback specific batch
php artisan migrate:rollback --step=1

# Fix specific table
# Drop table in MySQL
mysql -u root -p eps_be_web
DROP TABLE IF EXISTS courses;
EXIT;

# Re-run specific migration
php artisan migrate --path=/database/migrations/2024_01_01_000000_create_courses_table.php

# Check for duplicate migrations
# Look for same table in multiple migration files

# Reset migrations (WARNING: Deletes all data)
php artisan migrate:reset
php artisan migrate
```

---

### Issue 2.4: Foreign Key Constraint Fails

**Symptom:**

```
SQLSTATE[23000]: Integrity constraint violation
Cannot add or update a child row: a foreign key constraint fails
```

**Solutions:**

```powershell
# Disable foreign key checks (temporary)
mysql -u root -p eps_be_web

SET FOREIGN_KEY_CHECKS=0;
-- Run your commands
SET FOREIGN_KEY_CHECKS=1;
EXIT;

# Check migration order
# Ensure parent tables are created before child tables
# Example: users table before courses table (if course has user_id)

# Fix in migration:
public function up()
{
    Schema::disableForeignKeyConstraints();

    // Your migration code

    Schema::enableForeignKeyConstraints();
}

# If seeding fails due to foreign key:
# Ensure parent records exist first
# Example: Create users before creating courses
```

---

### Issue 2.5: Too Many Connections

**Symptom:**

```
SQLSTATE[HY000] [1040] Too many connections
```

**Solution:**

```sql
-- Check current connections
SHOW PROCESSLIST;
SHOW STATUS WHERE `variable_name` = 'Threads_connected';

-- Increase max connections in my.cnf or my.ini
[mysqld]
max_connections = 500

-- Restart MySQL after change

-- Kill idle connections
SHOW PROCESSLIST;
KILL <process_id>;

-- In Laravel, ensure connections are properly closed
-- Use DB::disconnect() when needed
```

---

### Issue 2.6: Query Too Slow / Timeout

**Symptom:**

```
Maximum execution time exceeded
Query takes minutes to complete
```

**Solutions:**

```powershell
# Enable query logging
# In .env
DB_LOG_QUERIES=true

# Check slow queries in Laravel
DB::enableQueryLog();
// Your code
dd(DB::getQueryLog());

# Add indexes to frequently queried columns
# In migration:
$table->index('course_id');
$table->index('user_id');
$table->index(['user_id', 'course_id']); // Composite index

# Use explain to analyze queries
# In MySQL:
EXPLAIN SELECT * FROM courses WHERE status = 'active';

# Optimize tables
OPTIMIZE TABLE courses;
ANALYZE TABLE courses;

# Use eager loading to avoid N+1
Course::with('sessions', 'participants')->get();

# Add pagination
Course::paginate(20);

# Increase timeout in .env
DB_TIMEOUT=60
```

---

## Authentication & Authorization Issues

### Issue 3.1: JWT Token Not Generated

**Symptom:**

```
JWT secret not set
Token generation fails
```

**Solution:**

```powershell
# Generate JWT secret
php artisan jwt:secret

# Verify in .env
JWT_SECRET=your_generated_secret

# Clear config cache
php artisan config:clear
php artisan config:cache

# Test token generation
php artisan tinker
>>> $user = App\Models\User::first();
>>> $token = auth()->login($user);
>>> echo $token;

# If still fails, check config/jwt.php
# Ensure provider is set correctly
```

---

### Issue 3.2: Token Expired / Invalid

**Symptom:**

```
Token has expired
Token is invalid
Unauthenticated
```

**Solutions:**

```powershell
# Check token TTL in config/jwt.php
'ttl' => env('JWT_TTL', 60), // 60 minutes

# Increase TTL in .env
JWT_TTL=120  # 2 hours

# Implement token refresh in API
POST /api/auth/refresh
Authorization: Bearer <expired_token>

# In controller:
public function refresh()
{
    return response()->json([
        'access_token' => auth()->refresh(),
        'token_type' => 'Bearer',
    ]);
}

# Clear token blacklist
php artisan jwt:blacklist:flush

# Check token claims
php artisan tinker
>>> $token = "your.jwt.token";
>>> $payload = \Tymon\JWTAuth\Facades\JWTAuth::getPayload($token);
>>> print_r($payload->toArray());
```

---

### Issue 3.3: User Not Authenticated

**Symptom:**

```
401 Unauthorized
auth()->user() returns null
```

**Solutions:**

```powershell
# Verify token in header
# Correct format:
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJ...

# Check middleware on route
Route::middleware('auth:api')->group(function () {
    // Protected routes
});

# Verify guard in config/auth.php
'guards' => [
    'api' => [
        'driver' => 'jwt',
        'provider' => 'users',
    ],
],

# Test authentication
php artisan tinker
>>> $user = App\Models\User::first();
>>> $token = auth()->login($user);
>>> auth()->setToken($token);
>>> auth()->user(); // Should return user

# Check token in Postman/Insomnia
# Headers:
# Authorization: Bearer YOUR_TOKEN
# Content-Type: application/json
```

---

### Issue 3.4: Password Not Matching

**Symptom:**

```
Invalid credentials
Login fails with correct password
```

**Solutions:**

```powershell
# Check password hashing
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> Hash::check('password123', $user->password);  # Should return true

# Rehash password
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->password = Hash::make('newpassword');
>>> $user->save();

# Verify User model uses Illuminate\Foundation\Auth\User
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed', // Laravel 10+
    ];
}

# Test login attempt
php artisan tinker
>>> Auth::attempt(['email' => 'test@example.com', 'password' => 'password']);
```

---

## Keycloak SSO Problems

### Issue 4.1: Cannot Connect to Keycloak

**Symptom:**

```
Connection refused to Keycloak server
Unable to reach Keycloak
```

**Solutions:**

```powershell
# Check if Keycloak is running
docker ps | findstr keycloak

# View Keycloak logs
docker logs -f keycloak

# Restart Keycloak
docker restart keycloak

# Start Keycloak if stopped
docker start keycloak

# Test Keycloak URL
curl http://localhost:8080

# Check port availability
netstat -ano | findstr :8080

# Verify Keycloak in .env
KEYCLOAK_BASE_URL=http://localhost:8080
KEYCLOAK_REALM=eps

# Try accessing admin console
# http://localhost:8080/admin
```

---

### Issue 4.2: Invalid Client / Client Not Found

**Symptom:**

```
Invalid client or Invalid client credentials
Client authentication failed
```

**Solutions:**

```powershell
# Verify client exists in Keycloak
# Admin Console → Clients → eps_backend

# Check client ID matches .env
KEYCLOAK_CLIENT_ID=eps_backend

# Get fresh client secret
# Clients → eps_backend → Credentials → Regenerate Secret

# Update .env with new secret
KEYCLOAK_CLIENT_SECRET=new_secret_here

# Clear Laravel cache
php artisan config:clear
php artisan cache:clear

# Test client credentials
curl -X POST "http://localhost:8080/realms/eps/protocol/openid-connect/token" `
  -H "Content-Type: application/x-www-form-urlencoded" `
  -d "client_id=eps_backend" `
  -d "client_secret=your_secret" `
  -d "grant_type=client_credentials"
```

---

### Issue 4.3: Invalid Token / Token Validation Failed

**Symptom:**

```
Token signature verification failed
Invalid token
```

**Solutions:**

```powershell
# Update public key in .env
# Get from: Realm Settings → Keys → RSA256 → Public key

KEYCLOAK_REALM_PUBLIC_KEY=MIIBIjANBgk...

# Clear config cache
php artisan config:clear
php artisan config:cache

# Verify token manually
# Copy token and paste into https://jwt.io
# Check signature verification

# Test token validation
php artisan tinker
>>> $token = "your.keycloak.token";
>>> auth('keycloak')->setToken($token);
>>> auth('keycloak')->user();

# Check token expiration
# Realm Settings → Tokens → Access Token Lifespan

# Ensure time sync
# Keycloak and Laravel server should have same time
net time  # Windows
date      # Linux
```

---

### Issue 4.4: User Not Found in Keycloak

**Symptom:**

```
Invalid user credentials
User does not exist
```

**Solutions:**

```powershell
# Verify user exists in Keycloak
# Admin Console → Users → View all users

# Create test user
# Users → Add user:
# Username: test@example.com
# Email: test@example.com
# Email Verified: ON

# Set password
# Users → test@example.com → Credentials
# Set Password: Test@123
# Temporary: OFF

# Verify user is enabled
# Users → test@example.com
# Enabled: ON

# Test login
curl -X POST "http://localhost:8080/realms/eps/protocol/openid-connect/token" `
  -H "Content-Type: application/x-www-form-urlencoded" `
  -d "client_id=eps_backend" `
  -d "client_secret=your_secret" `
  -d "grant_type=password" `
  -d "username=test@example.com" `
  -d "password=Test@123"
```

---

### Issue 4.5: CORS Errors with Keycloak

**Symptom:**

```
Access to XMLHttpRequest has been blocked by CORS policy
No 'Access-Control-Allow-Origin' header
```

**Solution:**

```powershell
# In Keycloak Admin Console:
# Realm Settings → Security → Web Origins
# Add:
http://localhost:8000
http://127.0.0.1:8000
http://localhost:3000  # If using frontend

# Or use wildcard (development only):
*

# In Laravel (cors.php):
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => [
    'http://localhost:3000',
    'http://localhost:8000',
],
'allowed_methods' => ['*'],
'allowed_headers' => ['*'],

# Publish CORS config
php artisan config:publish cors
```

---

### Issue 4.6: Keycloak Roles Not Syncing

**Symptom:**

```
User roles not appearing in Laravel
Permission check fails
```

**Solution:**

```powershell
# Add role mapper in Keycloak
# Clients → eps_backend → Client scopes → eps_backend-dedicated
# Add mapper:
# Name: roles
# Mapper Type: User Realm Role
# Token Claim Name: roles
# Add to ID token: ON
# Add to access token: ON
# Add to userinfo: ON

# Verify roles in token
# Decode JWT at https://jwt.io
# Check "roles" claim exists

# In Laravel, access roles:
$user = auth('keycloak')->user();
$roles = $user->roles ?? [];

# Map Keycloak roles to Spatie permissions
public function mapKeycloakRoles($keycloakUser)
{
    $user = User::firstOrCreate([
        'email' => $keycloakUser->email
    ]);

    foreach ($keycloakUser->roles as $role) {
        $user->assignRole($role);
    }
}
```

---

## API & Route Issues

### Issue 5.1: 404 Not Found

**Symptom:**

```
Route not found
404 error on valid endpoint
```

**Solutions:**

```powershell
# List all routes
php artisan route:list

# Filter specific route
php artisan route:list --path=courses

# Clear route cache
php artisan route:clear
php artisan route:cache

# Check route definition
# routes/api.php should have:
Route::apiResource('courses', CourseController::class);

# Verify URL prefix
# API routes automatically have /api prefix
# http://localhost:8000/api/courses (correct)
# http://localhost:8000/courses (wrong)

# Check middleware
Route::middleware('auth:api')->group(function () {
    // Routes here require authentication
});

# Test route directly
php artisan tinker
>>> app('router')->getRoutes()->match(
    app('request')->create('api/courses', 'GET')
);
```

---

### Issue 5.2: 405 Method Not Allowed

**Symptom:**

```
The GET method is not supported for this route
Method not allowed
```

**Solutions:**

```powershell
# Check allowed methods for route
php artisan route:list --path=courses

# Verify HTTP method matches
# Route: POST /api/courses
# Request must use POST, not GET

# For API resources, available methods:
GET     /api/courses         index
POST    /api/courses         store
GET     /api/courses/{id}    show
PUT     /api/courses/{id}    update
DELETE  /api/courses/{id}    destroy

# Check request in Postman/Insomnia
# Ensure method dropdown matches route

# If using PATCH instead of PUT:
Route::apiResource('courses', CourseController::class)
    ->except(['update']);
Route::patch('courses/{course}', [CourseController::class, 'update']);
```

---

### Issue 5.3: Route Model Binding Not Working

**Symptom:**

```
Model not found
Route parameter not binding to model
```

**Solutions:**

```powershell
# Verify route parameter name matches
Route::get('courses/{course}', function (Course $course) {
    return $course;
});

# Parameter must be named 'course' for Course model

# For custom key binding:
public function getRouteKeyName()
{
    return 'slug'; // Instead of 'id'
}

# Or explicit binding in RouteServiceProvider:
Route::model('course', Course::class);

# Test binding
php artisan tinker
>>> $course = App\Models\Course::find(1);
>>> route('courses.show', $course);

# Check if model exists
php artisan tinker
>>> App\Models\Course::findOrFail(999); // Should throw exception
```

---

### Issue 5.4: Validation Errors Not Returned

**Symptom:**

```
422 Unprocessable Entity with empty response
Validation errors not showing
```

**Solutions:**

```powershell
# In Form Request, ensure rules() method exists
public function rules()
{
    return [
        'course_name' => 'required|string|max:255',
        'code' => 'required|unique:courses',
    ];
}

# Add custom messages
public function messages()
{
    return [
        'course_name.required' => 'Course name is required',
        'code.unique' => 'Course code already exists',
    ];
}

# Check Accept header
# Request headers:
Accept: application/json
Content-Type: application/json

# Return validation errors explicitly
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()
        ], 422);
    }
}

# Global validation error handling in Handler.php
protected function invalidJson($request, ValidationException $exception)
{
    return response()->json([
        'message' => 'The given data was invalid.',
        'errors' => $exception->errors(),
    ], 422);
}
```

---

### Issue 5.5: JSON Response Not Formatted

**Symptom:**

```
Response is HTML instead of JSON
API returns error page
```

**Solutions:**

```powershell
# Always set Accept header
Accept: application/json

# In routes/api.php
Route::middleware('api')->group(function () {
    // Routes automatically return JSON
});

# Use API Resource for consistent formatting
return new CourseResource($course);
return CourseResource::collection($courses);

# Wrap responses consistently
return response()->json([
    'success' => true,
    'data' => $data,
    'message' => 'Operation successful'
]);

# Handle exceptions as JSON in Handler.php
public function render($request, Throwable $exception)
{
    if ($request->expectsJson()) {
        return response()->json([
            'message' => $exception->getMessage(),
        ], 500);
    }

    return parent::render($request, $exception);
}
```

---

## File Upload & Media Issues

### Issue 6.1: File Upload Fails

**Symptom:**

```
The file size exceeds the upload limit
Call to a member function storeAs() on null
```

**Solutions:**

```powershell
# Increase upload limits in php.ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
memory_limit = 256M

# Restart web server after change

# Check current limits
php -i | findstr upload_max_filesize
php -i | findstr post_max_size

# Verify file input name matches
<input type="file" name="thumbnail">
// In controller:
$request->file('thumbnail')  // Must match

# Validate file upload
$request->validate([
    'file' => 'required|file|max:10240', // 10MB
    'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
]);

# Check storage disk configuration
// config/filesystems.php
'default' => env('FILESYSTEM_DISK', 'local'),

# Test file upload
php artisan tinker
>>> $request = request()->create('/', 'POST', [], [], [
    'file' => new \Illuminate\Http\UploadedFile(
        'path/to/file.jpg',
        'file.jpg',
        'image/jpeg',
        null,
        true
    )
]);
```

---

### Issue 6.2: Spatie Media Library Not Working

**Symptom:**

```
Call to undefined method addMedia()
Media not saved
```

**Solutions:**

```powershell
# Ensure model implements HasMedia
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Course extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumbnail')
            ->singleFile();
    }
}

# Run media library migration
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="migrations"
php artisan migrate

# Check media table exists
php artisan tinker
>>> Schema::hasTable('media');  // Should return true

# Test media upload
php artisan tinker
>>> $course = App\Models\Course::first();
>>> $course->addMedia('path/to/file.jpg')->toMediaCollection('thumbnail');
>>> $course->getMedia('thumbnail');

# Clear media cache
php artisan media-library:clean
```

---

### Issue 6.3: Uploaded Files Not Accessible

**Symptom:**

```
404 on file URL
File exists but returns 403 Forbidden
```

**Solutions:**

```powershell
# Create storage link
php artisan storage:link

# Verify symlink
ls -l public/storage  # Should point to ../storage/app/public

# Check file permissions
icacls storage\app\public /grant Users:F /T

# Use correct URL helper
// Wrong:
storage_path('app/public/file.jpg')

// Correct:
Storage::url('file.jpg')
// Or:
asset('storage/file.jpg')

# For media library:
$url = $model->getFirstMediaUrl('collection');

# Configure APP_URL in .env
APP_URL=http://localhost:8000

# Test file access
curl http://localhost:8000/storage/test.jpg
```

---

### Issue 6.4: S3 Upload Fails

**Symptom:**

```
S3 credentials not configured
Unable to write file to S3
```

**Solutions:**

```powershell
# Install AWS SDK
composer require league/flysystem-aws-s3-v3 "^3.0"

# Configure .env
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your-bucket-name
AWS_USE_PATH_STYLE_ENDPOINT=false

# Set S3 as default disk
FILESYSTEM_DISK=s3

# Test S3 connection
php artisan tinker
>>> Storage::disk('s3')->put('test.txt', 'Hello S3');
>>> Storage::disk('s3')->exists('test.txt');

# Check S3 bucket permissions
# Bucket must allow PutObject, GetObject, DeleteObject

# Verify IAM user has permissions
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Effect": "Allow",
            "Action": [
                "s3:PutObject",
                "s3:GetObject",
                "s3:DeleteObject"
            ],
            "Resource": "arn:aws:s3:::your-bucket/*"
        }
    ]
}
```

---

## Performance & Query Issues

### Issue 7.1: N+1 Query Problem

**Symptom:**

```
Hundreds of queries for simple operation
Page loads very slowly
```

**Solutions:**

```powershell
# Enable query logging to identify
DB::enableQueryLog();
// Your code
dd(DB::getQueryLog());

# Use Laravel Debugbar
composer require barryvdh/laravel-debugbar --dev
# Check DB queries panel

# Fix with eager loading
// Bad (N+1):
$courses = Course::all();
foreach ($courses as $course) {
    echo $course->category->name;  // Each iteration queries DB
}

// Good (Eager loading):
$courses = Course::with('category')->get();
foreach ($courses as $course) {
    echo $course->category->name;  // No additional queries
}

# Multiple relationships
Course::with(['category', 'sessions', 'participants'])->get();

# Nested relationships
Course::with('sessions.agendas')->get();

# Conditional eager loading
Course::when($includeParticipants, function ($query) {
    $query->with('participants');
})->get();

# Use withCount() for counting
Course::withCount('participants')->get();
$course->participants_count; // No additional query
```

---

### Issue 7.2: Memory Exhausted

**Symptom:**

```
Allowed memory size of X bytes exhausted
Fatal error: Out of memory
```

**Solutions:**

```powershell
# Increase memory limit in php.ini
memory_limit = 512M

# Increase for specific script
ini_set('memory_limit', '512M');

# Use chunking for large datasets
Course::chunk(100, function ($courses) {
    foreach ($courses as $course) {
        // Process course
    }
});

# Use cursor for memory efficiency
foreach (Course::cursor() as $course) {
    // Process course
}

# Use lazy loading
Course::lazy()->each(function ($course) {
    // Process course
});

# Use pagination
Course::paginate(50);

# Select only needed columns
Course::select('id', 'name', 'code')->get();

# Clear collections in loops
unset($courses);
gc_collect_cycles();
```

---

### Issue 7.3: Slow Queries

**Symptom:**

```
Queries taking 5+ seconds
Database timeout
```

**Solutions:**

```powershell
# Add indexes to frequently queried columns
Schema::table('courses', function (Blueprint $table) {
    $table->index('status');
    $table->index('created_by');
    $table->index(['course_sub_category_id', 'status']); // Composite
});

# Run migration
php artisan migrate

# Analyze query with EXPLAIN
DB::listen(function ($query) {
    Log::debug($query->sql);
    Log::debug($query->bindings);
    Log::debug($query->time . 'ms');
});

# Use EXPLAIN in MySQL
EXPLAIN SELECT * FROM courses WHERE status = 'active';

# Optimize queries
// Instead of:
Course::all()->where('status', 'active');  // Loads all then filters

// Use:
Course::where('status', 'active')->get();  // Filters in DB

# Cache expensive queries
$courses = cache()->remember('active_courses', 3600, function () {
    return Course::where('status', 'active')->get();
});

# Use database query caching
$courses = Course::where('status', 'active')
    ->remember(3600)
    ->get();
```

---

### Issue 7.4: Too Many Database Connections

**Symptom:**

```
Too many connections
Max connections reached
```

**Solutions:**

```powershell
# Check active connections
SHOW PROCESSLIST;
SHOW STATUS WHERE variable_name = 'Threads_connected';

# Increase max connections in my.cnf
[mysqld]
max_connections = 500

# Use connection pooling
# In Laravel, connections auto-close after request

# Manually disconnect when done
DB::disconnect();

# Check for connection leaks
// Ensure no infinite loops opening connections
// Close connections in finally blocks

try {
    DB::connection()->getPdo();
    // Your code
} finally {
    DB::disconnect();
}

# Kill idle connections in MySQL
SELECT CONCAT('KILL ', id, ';')
FROM INFORMATION_SCHEMA.PROCESSLIST
WHERE Command = 'Sleep'
AND Time > 300;
```

---

## Cache & Session Problems

### Issue 8.1: Cache Not Working

**Symptom:**

```
Cache::get() returns null
Cached data not persisting
```

**Solutions:**

```powershell
# Check cache driver in .env
CACHE_DRIVER=redis  # Or file, database, array

# For Redis, ensure Redis is running
redis-cli ping  # Should return PONG

# Install Redis extension
composer require predis/predis

# Clear cache
php artisan cache:clear
php artisan config:clear

# Test cache
php artisan tinker
>>> cache()->put('test', 'value', 60);
>>> cache()->get('test');  // Should return 'value'

# For file cache, check permissions
icacls storage\framework\cache /grant Users:F /T

# Verify cache configuration
php artisan config:show cache

# Use cache facade correctly
use Illuminate\Support\Facades\Cache;

Cache::put('key', 'value', 3600);
$value = Cache::get('key');

# Check Redis connection
php artisan tinker
>>> \Illuminate\Support\Facades\Redis::connection()->ping();
```

---

### Issue 8.2: Session Not Persisting

**Symptom:**

```
Session data lost after refresh
User logged out automatically
```

**Solutions:**

```powershell
# Check session driver in .env
SESSION_DRIVER=cookie  # Or file, database, redis

# For database sessions, run migration
php artisan session:table
php artisan migrate

# Check session configuration
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=false

# For HTTPS, use:
SESSION_SECURE_COOKIE=true

# Clear sessions
php artisan session:flush

# Check session permissions (file driver)
icacls storage\framework\sessions /grant Users:F /T

# Verify session middleware
// In app/Http/Kernel.php
protected $middlewareGroups = [
    'web' => [
        \Illuminate\Session\Middleware\StartSession::class,
        // ...
    ],
];

# Test session
php artisan tinker
>>> session(['test' => 'value']);
>>> session('test');  // Should return 'value'

# For API, use Sanctum or JWT instead of sessions
```

---

### Issue 8.3: Redis Connection Failed

**Symptom:**

```
Connection refused [tcp://127.0.0.1:6379]
Redis server not available
```

**Solutions:**

```powershell
# Check if Redis is running
redis-cli ping

# Windows - Start Redis
# Download Redis for Windows or use WSL
# Or use Docker:
docker run -d -p 6379:6379 redis

# Linux - Start Redis
sudo systemctl start redis
sudo systemctl enable redis

# Check Redis configuration in .env
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_CLIENT=predis  # Or phpredis

# Install Redis client
composer require predis/predis

# Test Redis connection
php artisan tinker
>>> Redis::set('test', 'value');
>>> Redis::get('test');

# Check Redis logs
# Windows: check Redis server window
# Linux: sudo journalctl -u redis

# Configure Redis password if needed
REDIS_PASSWORD=your_password
```

---

## Email & Notification Issues

### Issue 9.1: Emails Not Sending

**Symptom:**

```
Connection could not be established with host
Swift_TransportException
```

**Solutions:**

```powershell
# Check mail configuration in .env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"

# For Gmail, use App Password (not regular password)
# https://myaccount.google.com/apppasswords

# Test email
php artisan tinker
>>> Mail::raw('Test email', function ($message) {
    $message->to('recipient@example.com')->subject('Test');
});

# Check mail queue
php artisan queue:work

# Use log driver for testing
MAIL_MAILER=log
# Check storage/logs/laravel.log

# Verify mail configuration
php artisan config:show mail

# Clear config cache
php artisan config:clear

# For Mailtrap (testing):
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
```

---

### Issue 9.2: Queue Not Processing

**Symptom:**

```
Jobs stuck in queue
Queue worker not running
```

**Solutions:**

```powershell
# Start queue worker
php artisan queue:work

# For development, use --tries and --timeout
php artisan queue:work --tries=3 --timeout=60

# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
php artisan queue:retry <job-id>

# Clear failed jobs
php artisan queue:flush

# Monitor queue
php artisan queue:monitor redis:default --max=100

# For Windows, use supervisor or run as service
# Or use Laravel Horizon for Redis queues
composer require laravel/horizon
php artisan horizon:install
php artisan horizon

# Check queue configuration
QUEUE_CONNECTION=redis  # Or sync, database

# Ensure jobs implement ShouldQueue
class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
}

# Restart queue after code changes
php artisan queue:restart
```

---

## Permission & Role Issues

### Issue 10.1: Permission Denied / 403 Forbidden

**Symptom:**

```
This action is unauthorized
User doesn't have the right permissions
```

**Solutions:**

```powershell
# Check user has role/permission
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->roles;
>>> $user->permissions;
>>> $user->hasRole('admin');
>>> $user->can('edit-course');

# Assign role to user
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->assignRole('admin');

# Assign permission to user
>>> $user->givePermissionTo('edit-course');

# Assign permission to role
>>> $role = Spatie\Permission\Models\Role::findByName('admin');
>>> $role->givePermissionTo('edit-course');

# Sync permissions cache
php artisan permission:cache-reset
php artisan cache:clear

# Check middleware on route
Route::middleware('permission:edit-course')->put('/courses/{id}', ...);
Route::middleware('role:admin')->get('/admin/dashboard', ...);

# In controller, use authorization
$this->authorize('update', $course);

# Create policy
php artisan make:policy CoursePolicy --model=Course

# Register policy in AuthServiceProvider
protected $policies = [
    Course::class => CoursePolicy::class,
];
```

---

### Issue 10.2: Roles Not Syncing

**Symptom:**

```
Roles assigned but not working
Permission check fails
```

**Solutions:**

```powershell
# Clear permission cache
php artisan permission:cache-reset

# Clear all caches
php artisan cache:clear
php artisan config:clear

# Verify permission tables exist
php artisan tinker
>>> Schema::hasTable('roles');
>>> Schema::hasTable('permissions');
>>> Schema::hasTable('model_has_roles');

# Run Spatie migrations
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate

# Check role assignments
php artisan tinker
>>> $user = App\Models\User::find(1);
>>> $user->roles()->get();
>>> $user->permissions()->get();

# Sync roles from scratch
>>> $user->syncRoles(['admin', 'editor']);

# Check User model has trait
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
}

# Create missing roles/permissions
php artisan tinker
>>> Spatie\Permission\Models\Role::create(['name' => 'admin']);
>>> Spatie\Permission\Models\Permission::create(['name' => 'edit-course']);
```

---

## Migration & Seeding Problems

### Issue 11.1: Migration Already Exists

**Symptom:**

```
Migration already exists
Duplicate migration name
```

**Solutions:**

```powershell
# Delete duplicate migration file
ls database/migrations | findstr create_courses_table

# Keep the newer one, delete older

# Or rollback and re-run
php artisan migrate:rollback --step=1
php artisan migrate

# Reset migrations (WARNING: Deletes all data)
php artisan migrate:reset
php artisan migrate

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

---

### Issue 11.2: Seeder Class Not Found

**Symptom:**

```
Class DatabaseSeeder does not exist
Target class [XyzSeeder] does not exist
```

**Solutions:**

```powershell
# Dump autoload
composer dump-autoload

# Check seeder namespace
namespace Database\Seeders;

# Call seeder correctly
php artisan db:seed --class=CourseSeeder

# In DatabaseSeeder:
public function run()
{
    $this->call([
        CourseSeeder::class,
        UserSeeder::class,
    ]);
}

# Create seeder
php artisan make:seeder CourseSeeder

# Check seeder file exists
ls database/seeders/CourseSeeder.php
```

---

## Excel Export/Import Issues

### Issue 12.1: Excel Export Fails

**Symptom:**

```
Call to undefined method Excel::download
Memory exhausted during export
```

**Solutions:**

```powershell
# Install Maatwebsite Excel
composer require maatwebsite/excel

# Publish config
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"

# Use facade correctly
use Maatwebsite\Excel\Facades\Excel;

return Excel::download(new CoursesExport, 'courses.xlsx');

# For large datasets, use chunking
class CoursesExport implements FromQuery, WithChunkSize
{
    public function query()
    {
        return Course::query();
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}

# Increase memory limit
ini_set('memory_limit', '512M');

# Use queue for large exports
return Excel::queue(new CoursesExport, 'courses.xlsx')
    ->chain([
        new NotifyUserOfCompletedExport($request->user()),
    ]);
```

---

### Issue 12.2: Excel Import Fails

**Symptom:**

```
Invalid row data
Headers not matching
```

**Solutions:**

```powershell
# Validate import data
class CoursesImport implements WithValidation
{
    public function rules(): array
    {
        return [
            '*.course_name' => 'required|string',
            '*.code' => 'required|unique:courses',
        ];
    }
}

# Handle import errors
try {
    Excel::import(new CoursesImport, $file);
} catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
    $failures = $e->failures();

    foreach ($failures as $failure) {
        $failure->row(); // Row that failed
        $failure->attribute(); // Column
        $failure->errors(); // Error messages
    }
}

# Use headings row
class CoursesImport implements WithHeadingRow
{
    public function model(array $row)
    {
        return new Course([
            'course_name' => $row['name'], // Uses header
            'code' => $row['code'],
        ]);
    }
}
```

---

## PDF Generation Issues

### Issue 13.1: PDF Not Generating

**Symptom:**

```
Call to undefined method PDF::loadView
DomPDF not working
```

**Solutions:**

```powershell
# Install DomPDF
composer require barryvdh/laravel-dompdf

# Publish config
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"

# Use facade
use Barryvdh\DomPDF\Facade\Pdf;

$pdf = Pdf::loadView('pdf.course', ['course' => $course]);
return $pdf->download('course.pdf');

# Or stream in browser
return $pdf->stream('course.pdf');

# Configure paper size
$pdf = Pdf::loadView('pdf.course', $data)
    ->setPaper('a4', 'portrait');

# Increase timeout for large PDFs
set_time_limit(300);

# Check view file exists
ls resources/views/pdf/course.blade.php
```

---

### Issue 13.2: PDF Styling Not Working

**Symptom:**

```
CSS not applied to PDF
Images not showing
```

**Solutions:**

```blade
<!-- Use inline CSS -->
<style>
    body { font-family: DejaVu Sans, sans-serif; }
    .header { background: #cc0000; color: white; }
</style>

<!-- Use absolute paths for images -->
<img src="{{ public_path('images/logo.png') }}" />

<!-- Or use base64 -->
<img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" />

<!-- Avoid external CSS files -->
<!-- DomPDF has limited CSS support -->

<!-- Use supported fonts -->
'font_family' => 'DejaVu Sans',
```

---

## CORS & Cross-Origin Issues

### Issue 14.1: CORS Error in Browser

**Symptom:**

```
Access to fetch has been blocked by CORS policy
No 'Access-Control-Allow-Origin' header
```

**Solutions:**

```powershell
# Install CORS package (if not included)
composer require fruitcake/laravel-cors

# Publish CORS config
php artisan config:publish cors

# Configure cors.php
'paths' => ['api/*', 'sanctum/csrf-cookie'],

'allowed_origins' => [
    'http://localhost:3000',
    'http://localhost:8000',
],

# Or allow all origins (development only)
'allowed_origins' => ['*'],

'allowed_methods' => ['*'],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => true,

# Add CORS middleware
// In app/Http/Kernel.php
protected $middleware = [
    \Fruitcake\Cors\HandleCors::class,
];

# Clear config cache
php artisan config:clear
```

---

## Docker & Deployment Issues

### Issue 15.1: Docker Container Won't Start

**Symptom:**

```
Container exits immediately
Docker compose up fails
```

**Solutions:**

```powershell
# Check container logs
docker logs keycloak
docker logs <container-name>

# Check if port is already in use
netstat -ano | findstr :8080

# Kill process using port (Windows)
taskkill /PID <pid> /F

# Restart Docker
# Docker Desktop: Restart

# Remove and recreate container
docker rm -f keycloak
docker run -d --name keycloak ...

# Check Docker Compose file syntax
docker-compose config

# Pull latest images
docker-compose pull
docker-compose up -d

# View all container logs
docker-compose logs -f
```

---

## Development Environment Issues

### Issue 16.1: Laravel Server Not Starting

**Symptom:**

```
Address already in use
Port 8000 is busy
```

**Solutions:**

```powershell
# Check what's using port 8000
netstat -ano | findstr :8000

# Kill process
taskkill /PID <pid> /F

# Use different port
php artisan serve --port=8001

# Or set in .env
APP_URL=http://localhost:8001
```

---

### Issue 16.2: Composer Very Slow

**Symptom:**

```
Composer update takes hours
Composer hangs
```

**Solutions:**

```powershell
# Disable xdebug
# In php.ini, comment out:
;zend_extension=xdebug

# Clear composer cache
composer clear-cache

# Increase memory
php -d memory_limit=2G composer update

# Use Hirak plugin for parallel downloads
composer global require hirak/prestissimo

# Or use Composer 2 (faster)
composer self-update
composer --version  # Should be 2.x
```

---

## Production Issues

### Issue 17.1: 500 Error in Production

**Symptom:**

```
Server Error (500)
Page shows generic error
```

**Solutions:**

```powershell
# Check Laravel logs
tail -f storage/logs/laravel.log

# Enable debug temporarily (be careful!)
APP_DEBUG=true

# Check web server logs
# Apache: /var/log/apache2/error.log
# Nginx: /var/log/nginx/error.log

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check file permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Check .env is present
ls -la .env
```

---

## Quick Troubleshooting Checklist

### When Something Goes Wrong:

```powershell
# 1. Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 2. Check logs
tail -f storage/logs/laravel.log

# 3. Check environment
php artisan about
php artisan env

# 4. Dump autoload
composer dump-autoload

# 5. Run migrations
php artisan migrate:status
php artisan migrate

# 6. Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# 7. Check permissions
icacls storage /grant Users:F /T
icacls bootstrap\cache /grant Users:F /T

# 8. Restart services
# Restart web server
# Restart queue workers
# Restart Redis/MySQL if needed

# 9. Check configuration
php artisan config:show
php artisan route:list

# 10. Enable debug mode temporarily
APP_DEBUG=true
```

---

## Getting Help

### Internal Resources

-   HANDS_ON_TRAINING_GUIDE.md
-   KEYCLOAK_SETUP_GUIDE.md
-   README.md

### External Resources

-   Laravel Documentation: https://laravel.com/docs
-   Laravel Forums: https://laracasts.com/discuss
-   Stack Overflow: https://stackoverflow.com/questions/tagged/laravel

### Debugging Tools

-   Laravel Telescope: `composer require laravel/telescope`
-   Laravel Debugbar: `composer require barryvdh/laravel-debugbar`
-   Laravel Log Viewer: `composer require rap2hpoutre/laravel-log-viewer`

---

**Document Version:** 1.0  
**Last Updated:** January 7, 2026  
**Status:** Production Ready

For additional support, consult the project documentation or Laravel official resources.

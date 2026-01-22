# Keycloak Setup Guide for EPS Backend Web Training

## Overview

Keycloak is an open-source identity and access management (IAM) solution that provides Single Sign-On (SSO) capabilities for the EPS Backend Web system. This guide provides step-by-step instructions for installing and configuring Keycloak for the training environment.

---

## Table of Contents

1. [System Requirements](#system-requirements)
2. [Installation Methods](#installation-methods)
3. [Docker Installation](#docker-installation)
4. [Standalone Installation](#standalone-installation)
5. [Initial Configuration](#initial-configuration)
6. [Realm Setup](#realm-setup)
7. [Client Configuration](#client-configuration)
8. [User Management](#user-management)
9. [Role & Permission Setup](#role--permission-setup)
10. [Laravel Integration](#laravel-integration)
11. [Testing Keycloak SSO](#testing-keycloak-sso)
12. [Troubleshooting](#troubleshooting)

---

## System Requirements

### Minimum Requirements

-   **RAM:** 2 GB (4 GB recommended)
-   **Disk Space:** 1 GB
-   **Java:** JDK 11+ (for standalone installation)
-   **Network:** Open port 8080 (or custom port)

### Supported Deployment Options

1. Docker (Recommended for training)
2. Standalone (Self-contained JAR)
3. Kubernetes (Production)
4. OpenShift (Enterprise)

---

## Installation Methods

### Quick Decision Tree

```
Choose your installation method:

├─ Docker Available?
│  ├─ YES → Use Docker (recommended, fastest)
│  └─ NO → Continue below
│
├─ Want Standalone?
│  ├─ YES → Use Standalone Installation
│  └─ NO → Install Docker first
│
└─ Need Production Ready?
   ├─ YES → Use Docker + Docker Compose + Volume Persistence
   └─ NO → Use simple Docker run
```

---

## Docker Installation (Recommended)

### Step 1: Verify Docker Installation

```powershell
# Check if Docker is installed
docker --version

# Check if Docker daemon is running
docker ps
```

### Step 2: Pull Keycloak Image

```powershell
# Pull the official Keycloak image
docker pull keycloak/keycloak:latest

# Verify image downloaded
docker images | grep keycloak
```

### Step 3: Run Keycloak Container

#### Option A: Simple Docker Run (Training)

```powershell
docker run -d `
  --name keycloak `
  -p 8080:8080 `
  -e KEYCLOAK_ADMIN=admin `
  -e KEYCLOAK_ADMIN_PASSWORD=admin `
  keycloak/keycloak:latest `
  start-dev
```

#### Option B: Docker Compose (Better for Development)

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
        networks:
            - eps-network

    # Optional: PostgreSQL for persistent database
    postgres:
        image: postgres:15-alpine
        container_name: keycloak-db
        environment:
            POSTGRES_DB: keycloak
            POSTGRES_USER: keycloak
            POSTGRES_PASSWORD: keycloak_password
        volumes:
            - postgres-data:/var/lib/postgresql/data
        networks:
            - eps-network

volumes:
    keycloak-data:
    postgres-data:

networks:
    eps-network:
        driver: bridge
```

Run with compose:

```powershell
docker-compose up -d
```

### Step 4: Verify Keycloak is Running

```powershell
# Check container status
docker ps | grep keycloak

# View logs
docker logs -f keycloak

# Wait for startup (check for "Listening on" message)
```

### Step 5: Access Keycloak Admin Console

```
URL: http://localhost:8080
Admin Console: http://localhost:8080/admin
Username: admin
Password: admin
```

---

## Standalone Installation

### Step 1: Download Keycloak

```powershell
# Download latest Keycloak standalone distribution
Invoke-WebRequest -Uri "https://github.com/keycloak/keycloak/releases/download/21.1.1/keycloak-21.1.1.zip" `
  -OutFile "keycloak-21.1.1.zip"

# Extract archive
Expand-Archive -Path "keycloak-21.1.1.zip" -DestinationPath "C:\keycloak"
```

### Step 2: Set Environment Variables

```powershell
# Set Java home if needed
$env:JAVA_HOME="C:\Program Files\Java\jdk-11"

# Add Keycloak bin to PATH
$env:PATH += ";C:\keycloak\bin"
```

### Step 3: Start Keycloak Server

```powershell
# Navigate to Keycloak directory
cd C:\keycloak

# Start in development mode
.\bin\kc.bat start-dev `
  --hostname=localhost `
  --http-port=8080
```

### Step 4: Initial Admin User Setup

First startup will prompt for admin credentials:

```
Create the initial admin user with:
Username: admin
Password: (set your password)
```

### Step 5: Verify Installation

```
Access: http://localhost:8080
Admin Console: http://localhost:8080/admin
```

---

## Initial Configuration

### Step 1: Access Admin Console

1. Go to http://localhost:8080/admin
2. Login with admin credentials
3. Navigate to Admin Console

### Step 2: Create New Realm

**A "Realm" is an isolated environment** for managing users, applications, and permissions.

**Steps:**

1. Hover over "Realm" dropdown (top-left)
2. Click "Create Realm"
3. Enter realm details:
    - **Name:** `eps` (or your project name)
    - **Display Name:** `EPS Backend Web`
4. Click "Create"

### Step 3: Configure Realm Security

1. Go to Realm Settings
2. Under "Tokens" tab:

    - **Access Token Lifespan:** 60 minutes
    - **Refresh Token Lifespan:** 7 days
    - **SSO Session Max Lifespan:** 30 days

3. Under "General" tab:
    - Enable "User-Managed Access"
    - Enable "Remember Me"

### Step 4: Configure Smtp (Optional, for Email Notifications)

1. Go to Realm Settings → Email
2. Configure SMTP:
    ```
    From: noreply@eps.local
    From Display Name: EPS System
    Host: smtp.gmail.com (or your SMTP server)
    Port: 587
    Enable TLS: ON
    ```

---

## Realm Setup

### Create Development Realm

```sql
-- Key Realm Configuration
Realm Name: eps-dev
Display Name: EPS Backend Web (Development)
Access Token Lifespan: 1 hour
Refresh Token Lifespan: 7 days
User Session Max: 30 days
Remember Me: Enabled
```

### Realm Roles (Create These)

1. **Super Admin**

    - Description: Full system access
    - Permissions: All

2. **Course Manager**

    - Description: Manages courses and curriculum
    - Permissions: Course CRUD

3. **Instructor**

    - Description: Teaches courses
    - Permissions: Course view, Grade management

4. **Student**

    - Description: Enrolled in courses
    - Permissions: View own courses, Submit assignments

5. **Facility Manager**

    - Description: Manages facility resources
    - Permissions: Facility CRUD

6. **Auditor**
    - Description: View-only audit access
    - Permissions: Read all audit logs

---

## Client Configuration

### Step 1: Create New Client

1. Go to Clients → Create
2. Configure:

    ```
    Client ID: eps_backend
    Name: EPS Backend API
    Description: REST API Server for EPS
    Client Type: OpenID Connect
    ```

3. Click "Next"

4. Configure access settings:

    ```
    Client Authentication: ON
    Authorization: ON
    Authentication Flow:
      ✓ Standard flow
      ✓ Direct access grants
      ✓ Service account
    ```

5. Click "Save"

### Step 2: Get Client Credentials

1. Go to Clients → eps_backend → Credentials
2. Copy:
    - **Client ID:** `eps_backend`
    - **Client Secret:** (copy the secret value)

### Step 3: Configure Redirect URIs

1. Go to Clients → eps_backend
2. Under "Access Settings":

    ```
    Valid Redirect URIs:
    - http://localhost:3000/*
    - http://localhost:8000/callback
    - http://127.0.0.1:8000/*
    - http://local.eps.com/*
    ```

3. Web Origins:
    ```
    - http://localhost:3000
    - http://localhost:8000
    - http://127.0.0.1:8000
    - http://local.eps.com
    ```

### Step 4: Configure Service Account

For backend-to-backend communication:

1. Go to Clients → eps_backend → Service Accounts Roles
2. Assign roles:
    - `realm-management`
    - `manage-users`
    - `manage-realm`

---

## User Management

### Step 1: Create Test Users

**Super Admin User:**

```
Username: admin
Email: admin@eps.local
First Name: System
Last Name: Administrator
Password: Admin@123 (temporary)
Email Verified: YES
User Enabled: YES
```

**Course Manager User:**

```
Username: manager
Email: manager@eps.local
First Name: Course
Last Name: Manager
Password: Manager@123 (temporary)
Email Verified: YES
User Enabled: YES
```

**Instructor User:**

```
Username: instructor
Email: instructor@eps.local
First Name: John
Last Name: Instructor
Password: Instructor@123 (temporary)
Email Verified: YES
User Enabled: YES
```

**Student User:**

```
Username: student
Email: student@eps.local
First Name: Jane
Last Name: Student
Password: Student@123 (temporary)
Email Verified: YES
User Enabled: YES
```

### Step 2: Set Password (First Login)

1. Go to Users → Select User
2. Go to "Credentials" tab
3. Set password and mark as temporary
4. User must change on first login

### Step 3: User Attributes

Add custom attributes:

```
Go to Users → User Details → Attributes

Add:
- department: Course Development
- location: Head Office
- phone: +60-3-XXXX-XXXX
- title: Manager
```

---

## Role & Permission Setup

### Step 1: Create Realm Roles

Navigate to Realm Roles and create:

```
1. super_admin
   - Description: Full system access

2. course_manager
   - Description: Manage courses and curriculum

3. course_instructor
   - Description: Teach and manage courses

4. course_student
   - Description: Enroll and participate in courses

5. facility_manager
   - Description: Manage facilities

6. auditor
   - Description: View audit logs
```

### Step 2: Assign Roles to Users

**Admin User:**

```
Realm Roles: super_admin
Client Roles (eps_backend): Manage
```

**Manager User:**

```
Realm Roles: course_manager
Client Roles (eps_backend): Manage courses
```

**Instructor User:**

```
Realm Roles: course_instructor
Client Roles (eps_backend): Teach courses
```

**Student User:**

```
Realm Roles: course_student
Client Roles (eps_backend): Enroll courses
```

### Step 3: Configure Client Scopes

1. Go to Client Scopes
2. Create new scope: `roles`
3. Add mapper to include roles in tokens

### Step 4: Configure Role Mapper

1. Go to eps_backend Client → Mappers
2. Create mapper:
    ```
    Name: roles-mapper
    Mapper Type: User Realm Role
    Token Claim Name: roles
    Add to ID Token: ON
    Add to Access Token: ON
    Add to Userinfo: ON
    ```

---

## Laravel Integration

### Step 1: Install Keycloak Package

```bash
composer require laravel-keycloak-guard/laravel-keycloak-guard
```

### Step 2: Publish Configuration

```bash
php artisan vendor:publish --provider="LaravelKeycloakGuard\LaravelKeycloakGuardServiceProvider"
```

### Step 3: Configure .env

```env
# Keycloak Configuration
KEYCLOAK_BASE_URL=http://localhost:8080
KEYCLOAK_REALM=eps
KEYCLOAK_REALM_PUBLIC_KEY=<your_public_key>
KEYCLOAK_CLIENT_ID=eps_backend
KEYCLOAK_CLIENT_SECRET=<your_client_secret>
KEYCLOAK_CACHE_OPEN_ID_CONFIG=false
```

### Step 4: Get Public Key

1. Go to Keycloak Admin Console
2. Realm Settings → Keys
3. Copy the public key (RSA256 algorithm)
4. Add to .env as `KEYCLOAK_REALM_PUBLIC_KEY`

### Step 5: Configure Middleware

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

### Step 6: Add Keycloak Middleware to Routes

```php
// In routes/api.php
Route::middleware('auth:keycloak')->group(function () {
    Route::get('/protected-resource', function () {
        return auth()->user();
    });
});
```

---

## Testing Keycloak SSO

### Test 1: Get Access Token

```powershell
# Using curl or Postman
POST http://localhost:8080/realms/eps/protocol/openid-connect/token

Body (form-data):
{
    "client_id": "eps_backend",
    "client_secret": "<client_secret>",
    "grant_type": "password",
    "username": "student",
    "password": "Student@123",
    "scope": "openid profile email"
}

Response:
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "token_type": "Bearer",
    "expires_in": 3600,
    "refresh_token": "eyJ0eXAiOiJKV1QiLCJhbGc...",
    "id_token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

### Test 2: Validate Token

```powershell
# Use token to access protected resource
GET http://localhost:8000/api/protected-resource

Headers:
Authorization: Bearer <access_token>

Response:
{
    "id": "123",
    "username": "student",
    "email": "student@eps.local",
    "roles": ["course_student"]
}
```

### Test 3: Refresh Token

```powershell
POST http://localhost:8080/realms/eps/protocol/openid-connect/token

Body (form-data):
{
    "client_id": "eps_backend",
    "client_secret": "<client_secret>",
    "grant_type": "refresh_token",
    "refresh_token": "<refresh_token>"
}
```

### Test 4: User Info Endpoint

```powershell
GET http://localhost:8080/realms/eps/protocol/openid-connect/userinfo

Headers:
Authorization: Bearer <access_token>

Response:
{
    "sub": "user-id",
    "name": "Jane Student",
    "preferred_username": "student",
    "given_name": "Jane",
    "family_name": "Student",
    "email": "student@eps.local",
    "email_verified": true
}
```

---

## Troubleshooting

### Issue 1: Cannot Connect to Keycloak

**Symptom:** Connection refused or timeout

**Solution:**

```powershell
# Check if container/service is running
docker ps | grep keycloak

# Check logs
docker logs keycloak

# Restart service
docker restart keycloak

# Verify port is listening
netstat -ano | findstr :8080
```

### Issue 2: Invalid Client Secret

**Symptom:** 401 Unauthorized on token request

**Solution:**

1. Go to Clients → eps_backend → Credentials
2. Regenerate client secret if needed
3. Update .env with new secret
4. Test again

### Issue 3: Token Validation Fails

**Symptom:** 401 on protected routes

**Solution:**

```powershell
# Get public key from Keycloak
curl http://localhost:8080/realms/eps/protocol/openid-connect/certs

# Update .env KEYCLOAK_REALM_PUBLIC_KEY with latest key
# Clear Laravel cache
php artisan cache:clear

# Restart application
php artisan serve
```

### Issue 4: CORS Errors

**Symptom:** CORS error when calling from frontend

**Solution:**

1. Go to Keycloak Realm Settings → Security
2. Add CORS allowed origins:
    ```
    http://localhost:3000
    http://localhost:8000
    ```

### Issue 5: Users Cannot Login

**Symptom:** Invalid credentials error

**Solution:**

1. Verify user is enabled in Keycloak
2. Check password hasn't expired
3. Verify account is not locked
4. Check user email is verified if required

---

## Common Configuration Scenarios

### Scenario 1: Development Environment

```
Keycloak: http://localhost:8080
Realm: eps-dev
Token Lifespan: 1 hour
User Self-Registration: Enabled
Email Required: No
```

### Scenario 2: Testing Environment

```
Keycloak: http://keycloak.test.local
Realm: eps-test
Token Lifespan: 30 minutes
User Self-Registration: Disabled
Email Required: Yes
MFA: Optional
```

### Scenario 3: Production Environment

```
Keycloak: https://keycloak.eps.gov.my
Realm: eps
Token Lifespan: 15 minutes
User Self-Registration: Disabled
Email Required: Yes (verified)
MFA: Required
HTTPS Only: Yes
```

---

## Security Best Practices

1. **Change Default Credentials**

    ```
    Never use admin/admin in production
    Use strong passwords (16+ characters)
    Use SecureRandom for client secrets
    ```

2. **Enable HTTPS**

    ```
    In production, always use HTTPS
    Configure SSL certificates
    Redirect HTTP to HTTPS
    ```

3. **Token Security**

    ```
    Set appropriate token lifespans
    Use refresh tokens for long sessions
    Implement token rotation
    Log token issuance
    ```

4. **User Security**

    ```
    Enforce strong password policies
    Enable email verification
    Implement account lockout
    Log authentication attempts
    ```

5. **Realm Security**
    ```
    Limit realm administrators
    Audit all changes
    Regular security reviews
    Keep Keycloak updated
    ```

---

## Useful Keycloak Admin Tasks

### Export Realm Configuration

```powershell
# Backup realm
docker exec keycloak /opt/keycloak/bin/kc.sh export \
  --realm eps \
  --users realm_file \
  --file /tmp/eps-realm.json

# Copy from container
docker cp keycloak:/tmp/eps-realm.json ./eps-realm-backup.json
```

### Import Realm Configuration

```powershell
# Copy to container
docker cp ./eps-realm.json keycloak:/tmp/

# Import realm
docker exec keycloak /opt/keycloak/bin/kc.sh import \
  --realm eps \
  --file /tmp/eps-realm.json
```

### Reset Admin Password

```powershell
# For Docker
docker exec keycloak /opt/keycloak/bin/kc.sh config credentials \
  --server http://localhost:8080 \
  --realm master \
  --user admin \
  --password new_password
```

---

## Next Steps

1. ✅ Install Keycloak (Docker or Standalone)
2. ✅ Create realm and client
3. ✅ Create test users and roles
4. ✅ Integrate with Laravel
5. ✅ Test token generation and validation
6. ✅ Configure for your environment
7. ✅ Implement in production

---

## References

-   [Keycloak Official Documentation](https://www.keycloak.org/documentation)
-   [Keycloak Admin Guide](https://www.keycloak.org/docs/latest/server_admin)
-   [OpenID Connect Protocol](https://openid.net/connect/)
-   [JWT Token Standard](https://tools.ietf.org/html/rfc7519)
-   [Laravel Keycloak Guard](https://github.com/laravel-keycloak-guard/laravel-keycloak-guard)

---

**This guide is part of the EPS Backend Web Training Package**
Last Updated: January 7, 2026

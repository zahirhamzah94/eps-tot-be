<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Facades\Auth;

class Course extends Model implements Auditable, HasMedia
{
    // Auditing trait - logs all changes
    use \OwenIt\Auditing\Auditable;
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = [
        'category_id', // Adapted from course_sub_category_id
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'category_id');
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

    /**
     * Inverse polymorphic - get all audit logs for this course
     */
    public function auditLogs(): MorphMany
    {
        return $this->morphMany(AuditLog::class, 'auditable');
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

        if (!$user) {
            return false;
        }

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

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

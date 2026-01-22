<?php

namespace App\Observers;

use App\Models\Course;

class CourseObserver
{
    /**
     * Handle the Course "created" event.
     */
    public function created(Course $course): void
    {
        // Generate unique course code if not provided or to standardize
        if (empty($course->code)) {
            $course->update(['code' => 'COURSE-' . $course->id]);
        }

        // Dispatch notification event
        // event(new CourseCreated($course));
    }

    /**
     * Handle the Course "updated" event.
     */
    public function updated(Course $course): void
    {
        // Log changes for audit (using spatie/laravel-activitylog if installed, or just logic)
        // activity()
        //     ->performedOn($course)
        //     ->log('Course updated: ' . $course->course_name);
    }

    /**
     * Handle the Course "deleted" event.
     */
    public function deleted(Course $course): void
    {
        //
    }

    /**
     * Handle the Course "restored" event.
     */
    public function restored(Course $course): void
    {
        //
    }

    /**
     * Handle the Course "force deleted" event.
     */
    public function forceDeleted(Course $course): void
    {
        //
    }
}

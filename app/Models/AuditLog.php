<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $guarded = [];

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
}

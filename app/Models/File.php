<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'filename',
        'original_filename',
        'mime_type',
        'size',
        'path',
        'disk',
        'hash',
        'user_id',
        'fileable_type',
        'fileable_id',
        'category',
        'metadata',
        'description',
        'is_public',
        'expires_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_public' => 'boolean',
        'expires_at' => 'datetime',
        'size' => 'integer',
    ];

    /**
     * Get the user who uploaded the file.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Polymorphic relationship - file can belong to any model.
     */
    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the full storage path.
     */
    public function getFullPathAttribute(): string
    {
        return storage_path("app/{$this->disk}/{$this->path}");
    }

    /**
     * Check if file is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if file can be accessed by the given user.
     */
    public function canAccess(?User $user = null): bool
    {
        if ($this->is_public) {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $this->user_id === $user->id;
    }

    /**
     * Increment download count.
     */
    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }

    /**
     * Get file size in human-readable format.
     */
    public function getHumanSize(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}

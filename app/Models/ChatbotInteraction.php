<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ChatbotInteraction extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'session_id', 'role',
        'message', 'response', 'context', 'sources',
        'tokens_used', 'response_time_ms', 'was_helpful', 'timestamp',
    ];

    protected function casts(): array
    {
        return [
            'context' => 'array',
            'sources' => 'array',
            'was_helpful' => 'boolean',
            'timestamp' => 'datetime',
        ];
    }

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function course(): BelongsTo { return $this->belongsTo(Course::class); }

    public static function generateSessionId(): string
    {
        return Str::uuid()->toString();
    }
}

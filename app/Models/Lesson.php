<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $fillable = [
        'module_id', 'title', 'content', 'sort_order',
        'duration_minutes', 'is_visible', 'is_free_preview',
    ];

    protected function casts(): array
    {
        return ['is_visible' => 'boolean', 'is_free_preview' => 'boolean'];
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function resources(): HasMany
    {
        return $this->hasMany(Resource::class);
    }

    public function progress(): HasMany
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function isCompletedBy(User $user): bool
    {
        return $this->progress()->where('user_id', $user->id)->where('is_completed', true)->exists();
    }
}

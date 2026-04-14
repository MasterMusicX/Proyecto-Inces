<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiQuery extends Model
{
    protected $fillable = ['user_id', 'course_id', 'question', 'response', 'was_helpful'];
    protected function casts(): array {
        return ['context' => 'array', 'was_helpful' => 'boolean'];
    }
    public function user()   { return $this->belongsTo(User::class); }
    public function course() { return $this->belongsTo(Course::class); }
}

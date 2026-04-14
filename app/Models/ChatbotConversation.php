<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotConversation extends Model
{
    protected $fillable = ['user_id', 'session_id', 'title', 'course_id', 'is_active'];
    protected function casts(): array { return ['is_active' => 'boolean']; }

    public function user()     { return $this->belongsTo(User::class); }
    public function course()   { return $this->belongsTo(Course::class); }
    public function messages() {
        return $this->hasMany(ChatbotMessage::class, 'conversation_id')->orderBy('created_at');
    }
}

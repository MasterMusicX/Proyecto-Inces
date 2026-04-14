<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatbotMessage extends Model
{
    protected $fillable = ['conversation_id', 'user_id', 'role', 'content', 'metadata'];
    protected function casts(): array { return ['metadata' => 'array']; }

    public function conversation() { return $this->belongsTo(ChatbotConversation::class); }
    public function user()         { return $this->belongsTo(User::class); }
}

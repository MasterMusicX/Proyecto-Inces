<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'role',
        'avatar', 'phone', 'bio', 'is_active', 'last_login_at'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at'     => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    public function isAdmin(): bool      { return $this->role === 'admin'; }
    public function isInstructor(): bool { return $this->role === 'instructor'; }
    public function isStudent(): bool    { return $this->role === 'student'; }

    public function coursesAsInstructor() {
        return $this->hasMany(Course::class, 'instructor_id');
    }
    public function enrollments() {
        return $this->hasMany(Enrollment::class);
    }
    public function enrolledCourses() {
        return $this->belongsToMany(Course::class, 'enrollments')
            ->withPivot('status', 'progress_percentage', 'completed_at')
            ->withTimestamps();
    }
    public function aiQueries() {
        return $this->hasMany(AiQuery::class);
    }
    public function chatbotConversations() {
        return $this->hasMany(ChatbotConversation::class);
    }
    public function resourcesUploaded() {
        return $this->hasMany(Resource::class, 'created_by');
    }
    public function getAvatarUrlAttribute(): string {
        if ($this->avatar) return asset('storage/' . $this->avatar);
        $name = urlencode($this->name);
        return "https://ui-avatars.com/api/?name={$name}&background=005A9E&color=fff&size=128&bold=true";
    }
}

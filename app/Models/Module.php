<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    // 👇 CAMBIAMOS is_published por is_visible 👇
    protected $fillable = ['course_id', 'title', 'description', 'sort_order', 'is_visible'];

    protected function casts(): array {
        return ['is_visible' => 'boolean'];
    }

    public function course()    { return $this->belongsTo(Course::class); }
    public function resources() { return $this->hasMany(Resource::class)->orderBy('sort_order'); }
}
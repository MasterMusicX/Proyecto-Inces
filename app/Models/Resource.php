<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resource extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id', 'module_id', 'created_by', 'title', 'description',
        'type', 'file_path', 'external_url', 'mime_type', 'file_size',
        'order', 'metadata', 'is_downloadable', 'is_published'
    ];

    protected function casts(): array {
        return [
            'metadata'       => 'array',
            'is_downloadable' => 'boolean',
            'is_published'    => 'boolean',
        ];
    }

    public function course()   { return $this->belongsTo(Course::class); }
    public function module()   { return $this->belongsTo(Module::class); }
    public function creator()  { return $this->belongsTo(User::class, 'created_by'); }
    public function analysis() { return $this->hasOne(DocumentAnalysis::class); }
    public function views()    { return $this->hasMany(ResourceView::class); }

    public function getFileUrlAttribute(): ?string {
        if ($this->file_path) return asset('storage/' . $this->file_path);
        return $this->external_url;
    }
    public function getFileSizeHumanAttribute(): string {
        if (!$this->file_size) return 'N/A';
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) return number_format($bytes/1073741824, 2).' GB';
        if ($bytes >= 1048576)    return number_format($bytes/1048576, 2).' MB';
        if ($bytes >= 1024)       return number_format($bytes/1024, 2).' KB';
        return $bytes.' B';
    }
    public function getTypeIconAttribute(): string {
        return match($this->type) {
            'pdf'   => '📄',
            'docx'  => '📝',
            'xlsx'  => '📊',
            'pptx'  => '📋',
            'video' => '🎬',
            'url'   => '🔗',
            'image' => '🖼️',
            default  => '📎',
        };
    }
    public function isDocument(): bool { return in_array($this->type, ['pdf','docx','xlsx','pptx']); }
    public function isVideo(): bool    { return $this->type === 'video'; }
}

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
        'order', 'metadata', 'is_downloadable', 'is_published', 'is_visible'
    ];

    protected function casts(): array {
        return [
            'metadata'        => 'array',
            'is_downloadable' => 'boolean',
            'is_published'    => 'boolean',
            'is_visible'      => 'boolean',
        ];
    }

    public function course()   { return $this->belongsTo(Course::class); }
    public function module()   { return $this->belongsTo(Module::class); }
    public function creator()  { return $this->belongsTo(User::class, 'created_by'); }
    public function analysis() { return $this->hasOne(DocumentAnalysis::class); }
    public function views()    { return $this->hasMany(ResourceView::class); }

    public function getFileUrlAttribute(): ?string {
        if ($this->file_path) {
            if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
                return $this->file_path;
            }
            return asset('storage/' . $this->file_path);
        }
        return $this->external_url;
    }

    public function getIsPublishedAttribute(): bool {
        if (array_key_exists('is_visible', $this->attributes)) {
            return (bool) $this->attributes['is_visible'];
        }
        if (array_key_exists('is_published', $this->attributes)) {
            return (bool) $this->attributes['is_published'];
        }
        return true;
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
            'pdf'   => '<svg class="w-5 h-5 text-red-500 inline-block" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>',
            'video' => '<svg class="w-5 h-5 text-blue-500 inline-block" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m15.75 10.5 4.72-4.72a.75.75 0 0 1 1.28.53v11.38a.75.75 0 0 1-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25h-9A2.25 2.25 0 0 0 2.25 7.5v9a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>',
            'url'   => '<svg class="w-5 h-5 text-green-500 inline-block" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" /></svg>',
            'image' => '<svg class="w-5 h-5 text-purple-500 inline-block" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>',
            default  => '<svg class="w-5 h-5 text-amber-500 inline-block" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>',
        };
    }
    public function isDocument(): bool { return in_array($this->type, ['pdf','docx','xlsx','pptx']); }
    public function isVideo(): bool    { return $this->type === 'video'; }
}

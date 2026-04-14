<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentAnalysis extends Model
{
    protected $fillable = [
        'resource_id', 'extracted_text', 'summary',
        'keywords', 'topics', 'language', 'status', 'error_message'
    ];

    protected function casts(): array {
        return ['keywords' => 'array', 'topics' => 'array'];
    }

    public function resource()    { return $this->belongsTo(Resource::class); }
    public function isCompleted() { return $this->status === 'completed'; }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceView extends Model
{
    protected $fillable = ['resource_id', 'user_id', 'view_count', 'last_viewed_at'];
    protected function casts(): array { return ['last_viewed_at' => 'datetime']; }
}

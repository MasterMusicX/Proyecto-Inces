<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Models\ResourceView;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    public function show(Resource $resource)
    {
        // Check enrollment
        $isEnrolled = $resource->course->students()->where('users.id', Auth::id())->exists();
        abort_unless($isEnrolled, 403, 'Debes inscribirte en el curso para acceder a este recurso.');

        // Track view
        ResourceView::updateOrCreate(
            ['resource_id' => $resource->id, 'user_id' => Auth::id()],
            ['last_viewed_at' => now()]
        );
        ResourceView::where('resource_id', $resource->id)
            ->where('user_id', Auth::id())
            ->increment('view_count');

        $resource->load(['course', 'module', 'analysis']);
        return view('student.resources.show', compact('resource'));
    }
}

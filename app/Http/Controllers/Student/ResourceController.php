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

    public function download(Resource $resource)
    {
        // Check enrollment
        $isEnrolled = $resource->course->students()->where('users.id', Auth::id())->exists();
        abort_unless($isEnrolled, 403, 'Debes inscribirte en el curso para acceder a este recurso.');

        if (!$resource->is_downloadable) {
            return back()->with('error', 'Este recurso no está disponible para descarga.');
        }

        if ($resource->external_url && !$resource->file_path) {
            return redirect()->away($resource->external_url);
        }

        if ($resource->file_path) {
            if (str_starts_with($resource->file_path, 'http://') || str_starts_with($resource->file_path, 'https://')) {
                return redirect()->away($resource->file_path);
            }

            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($resource->file_path)) {
                $extension = pathinfo($resource->file_path, PATHINFO_EXTENSION);
                $filename = \Illuminate\Support\Str::slug($resource->title) . '.' . ($extension ?: 'file');
                
                // Incrementar contador de descargas si existe la columna
                $resource->increment('download_count');

                return \Illuminate\Support\Facades\Storage::disk('public')->download($resource->file_path, $filename);
            }
        }

        return back()->with('error', 'El archivo no se encuentra disponible en el servidor en este momento.');
    }
}

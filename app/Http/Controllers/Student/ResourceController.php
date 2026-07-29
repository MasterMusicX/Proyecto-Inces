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
            return back()->with('error', 'Este recurso no está habilitado para descarga por el instructor.');
        }

        if ($resource->external_url && !$resource->file_path) {
            return redirect()->away($resource->external_url);
        }

        if ($resource->file_path) {
            if (str_starts_with($resource->file_path, 'http://') || str_starts_with($resource->file_path, 'https://')) {
                return redirect()->away($resource->file_path);
            }

            $cleanPath = preg_replace('#^/?(storage/)?#', '', $resource->file_path);
            $fullPath = storage_path('app/public/' . $cleanPath);

            if (file_exists($fullPath)) {
                $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
                $filename = \Illuminate\Support\Str::slug($resource->title) . '.' . ($extension ?: 'file');
                
                try {
                    $resource->increment('download_count');
                } catch (\Exception $e) {}

                return response()->download($fullPath, $filename);
            }
        }

        return back()->with('error', 'El archivo físico no se encuentra en el servidor. Pide al instructor resubir el recurso.');
    }

    public function file(Resource $resource)
    {
        // Check enrollment
        $isEnrolled = $resource->course->students()->where('users.id', Auth::id())->exists();
        abort_unless($isEnrolled, 403, 'Debes inscribirte en el curso para acceder a este recurso.');

        if ($resource->external_url && !$resource->file_path) {
            return redirect()->away($resource->external_url);
        }

        if ($resource->file_path) {
            if (str_starts_with($resource->file_path, 'http://') || str_starts_with($resource->file_path, 'https://')) {
                return redirect()->away($resource->file_path);
            }

            $cleanPath = preg_replace('#^/?(storage/)?#', '', $resource->file_path);
            $fullPath = storage_path('app/public/' . $cleanPath);

            if (file_exists($fullPath)) {
                $mimeType = $resource->mime_type ?: (mime_content_type($fullPath) ?: 'application/octet-stream');

                return response()->file($fullPath, [
                    'Content-Type'        => $mimeType,
                    'Content-Disposition' => 'inline; filename="' . basename($fullPath) . '"',
                ]);
            }
        }

        abort(404, 'El archivo físico no se encuentra disponible en el servidor.');
    }
}

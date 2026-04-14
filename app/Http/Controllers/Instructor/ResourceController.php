<?php
namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessDocumentJob;
use App\Models\Course;
use Illuminate\Support\Facades\Gate;
use App\Models\Module;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    public function index(Course $course)
    {
        Gate::authorize('view', $course);
        $resources = $course->resources()->with('module', 'analysis')->orderBy('sort_order')->paginate(20);
        return view('instructor.resources.index', compact('course', 'resources'));
    }

    public function create(Course $course)
    {
        Gate::authorize('update', $course);
        $modules = $course->modules()->orderBy('sort_order')->get();
        return view('instructor.resources.create', compact('course', 'modules'));
    }

    public function store(Request $request, Course $course)
    {
        Gate::authorize('update', $course);

        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'module_id'       => 'nullable|exists:modules,id',
            'type'            => 'required|in:pdf,docx,xlsx,pptx,video,url,image',
            'file'            => 'required_if:type,pdf,docx,xlsx,pptx,video,image|nullable|file|max:51200',
            'external_url'    => 'required_if:type,url|nullable|url',
            'is_downloadable' => 'boolean',
            'order'           => 'integer|min:0',
        ]);

        $data['course_id']  = $course->id;
        $data['created_by'] = Auth::id();

        if ($request->hasFile('file')) {
            $file            = $request->file('file');
            $path            = $file->store("courses/{$course->id}/resources", 'public');
            $data['file_path']  = $path;
            $data['mime_type']  = $file->getMimeType();
            $data['file_size']  = $file->getSize();
        }

        $resource = Resource::create($data);

        // Dispatch background job for document analysis
        if ($resource->isDocument()) {
            ProcessDocumentJob::dispatch($resource);
        }

        return redirect()->route('instructor.courses.resources.index', $course)
            ->with('success', 'Recurso subido exitosamente. El análisis de IA se procesará en breve.');
    }

    public function destroy(Course $course, Resource $resource)
    {
        Gate::authorize('update', $course);
        if ($resource->file_path) Storage::disk('public')->delete($resource->file_path);
        $resource->delete();
        return back()->with('success', 'Recurso eliminado.');
    }
}

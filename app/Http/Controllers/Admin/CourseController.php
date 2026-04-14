<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::with(['instructor', 'category'])
            ->withCount('enrollments')
            ->when($request->search, fn($q) =>
                $q->where('title', 'ilike', '%' . $request->search . '%')
            )
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->paginate(12)->withQueryString();

        return view('admin.courses.index', compact('courses'));
    }

    public function create()
    {
        $instructors = User::where('role', 'instructor')->orderBy('name')->get();
        $categories  = Category::orderBy('name')->get();
        return view('admin.courses.create', compact('instructors', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'objectives'     => 'nullable|string',
            'instructor_id'  => 'required|exists:users,id',
            'category_id'    => 'nullable|exists:categories,id',
            'level'          => 'required|in:beginner,intermediate,advanced',
            'duration_hours' => 'integer|min:0',
            'max_students'   => 'nullable|integer|min:1',
            'status'         => 'required|in:draft,published,archived',
            'is_featured'    => 'boolean',
            'thumbnail'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);
        Course::create($data);

        return redirect()->route('admin.courses.index')->with('success', 'Curso creado exitosamente.');
    }

    public function edit(Course $course)
    {
        $instructors = User::where('role', 'instructor')->orderBy('name')->get();
        $categories  = Category::orderBy('name')->get();
        return view('admin.courses.edit', compact('course', 'instructors', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'objectives'     => 'nullable|string',
            'instructor_id'  => 'required|exists:users,id',
            'category_id'    => 'nullable|exists:categories,id',
            'level'          => 'required|in:beginner,intermediate,advanced',
            'duration_hours' => 'integer|min:0',
            'max_students'   => 'nullable|integer|min:1',
            'status'         => 'required|in:draft,published,archived',
            'is_featured'    => 'boolean',
            'thumbnail'      => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            if ($course->thumbnail) Storage::disk('public')->delete($course->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $course->update($data);
        return redirect()->route('admin.courses.index')->with('success', 'Curso actualizado.');
    }

    public function destroy(Course $course)
    {
        if ($course->thumbnail) Storage::disk('public')->delete($course->thumbnail);
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Curso eliminado.');
    }
}

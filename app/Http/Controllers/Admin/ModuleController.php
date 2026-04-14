<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(Course $course)
    {
        $modules = $course->modules()->withCount('resources')->orderBy('order')->get();
        return view('admin.modules.index', compact('course', 'modules'));
    }

    public function store(Request $request, Course $course)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:1000',
            'order'        => 'integer|min:0',
            'is_published' => 'boolean',
        ]);
        $data['course_id'] = $course->id;
        $data['order']     = $data['order'] ?? ($course->modules()->max('order') + 1);
        Module::create($data);
        return back()->with('success', 'Módulo creado exitosamente.');
    }

    public function update(Request $request, Course $course, Module $module)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string|max:1000',
            'order'        => 'integer|min:0',
            'is_published' => 'boolean',
        ]);
        $module->update($data);
        return back()->with('success', 'Módulo actualizado.');
    }

    public function destroy(Course $course, Module $module)
    {
        $module->delete();
        return back()->with('success', 'Módulo eliminado.');
    }

    public function reorder(Request $request, Course $course)
    {
        $request->validate(['order' => 'required|array']);
        foreach ($request->order as $position => $moduleId) {
            Module::where('id', $moduleId)
                ->where('course_id', $course->id)
                ->update(['order' => $position]);
        }
        return response()->json(['success' => true]);
    }
}

<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('courses')->orderBy('name')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100|unique:course_categories,name',
            'description' => 'nullable|string|max:500',
            'color'       => 'required|string|max:7',
        ]);
        
        $data['slug'] = Str::slug($data['name']);
        Category::create($data);
        
        return back()->with('success', 'Categoría creada exitosamente.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100|unique:course_categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'color'       => 'required|string|max:7',
        ]);
        
        $data['slug'] = Str::slug($data['name']);
        $category->update($data);
        
        return redirect()->route('admin.categories.index')
                         ->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy(Category $category)
    {
        if ($category->courses()->count() > 0) {
            return back()->with('error', 'No puedes eliminar una categoría que tiene cursos asignados.');
        }
        
        $category->delete();
        return back()->with('success', 'Categoría eliminada.');
    }
}
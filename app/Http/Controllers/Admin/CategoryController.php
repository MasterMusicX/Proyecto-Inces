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
            'name'        => 'required|string|max:100|unique:categories',
            'description' => 'nullable|string|max:500',
            'color'       => 'required|string|max:7',
            'icon'        => 'nullable|string|max:10', // <-- Mejoramos recibiendo el Emoji
        ]);
        
        $data['slug'] = Str::slug($data['name']);
        Category::create($data);
        
        return back()->with('success', 'Categoría creada exitosamente.');
    }

    // <-- MEJORA: Agregamos la función para abrir la pantalla de edición
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            // <-- MEJORA: Ignorar el nombre actual para la regla 'unique' al editar
            'name'        => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'color'       => 'required|string|max:7',
            'icon'        => 'nullable|string|max:10', // <-- Mejoramos recibiendo el Emoji
        ]);
        
        $data['slug'] = Str::slug($data['name']);
        $category->update($data);
        
        // <-- MEJORA: Redirigir a la lista principal después de editar
        return redirect()->route('admin.categories.index')
                         ->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy(Category $category)
    {
        // Tu validación aquí estaba excelente
        if ($category->courses()->count() > 0) {
            return back()->with('error', 'No puedes eliminar una categoría que tiene cursos asignados.');
        }
        
        $category->delete();
        return back()->with('success', 'Categoría eliminada.');
    }
}
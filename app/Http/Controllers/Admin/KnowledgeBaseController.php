<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KnowledgeBase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KnowledgeBaseController extends Controller
{
    public function index(Request $request)
    {
        $entries = KnowledgeBase::query()
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->when($request->search, fn($q) => $q->where('question', 'ilike', "%{$request->search}%"))
            ->orderByDesc('views')
            ->paginate(20)
            ->withQueryString();

        // Obtenemos las categorías únicas para el filtro
        $categories = KnowledgeBase::distinct()->pluck('category');

        return view('admin.knowledge-base.index', compact('entries', 'categories'));
    }

    public function create()
    {
        return view('admin.knowledge-base.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'question'    => 'required|string|max:500',
            'answer'      => 'required|string',
            'category'    => 'required|string|max:100',
            'tags_string' => 'nullable|string', // Lo recibimos como el string que viene de la vista
        ]);

        // Procesamos el string de tags para convertirlo en array (JSON en la BD)
        if ($request->filled('tags_string')) {
            $data['tags'] = array_map('trim', explode(',', $request->tags_string));
        } else {
            $data['tags'] = null;
        }

        // Eliminamos la clave temporal que no existe en la base de datos
        unset($data['tags_string']);

        KnowledgeBase::create($data);

        return redirect()->route('admin.knowledge-base.index')
            ->with('success', 'Entrada añadida exitosamente a la base de conocimiento.');
    }

    public function edit($id)
    {
        $knowledgeBase = KnowledgeBase::findOrFail($id);
        
        // Convertimos el array de tags de nuevo a string para mostrarlo en el input
        $tagsString = $knowledgeBase->tags ? implode(', ', $knowledgeBase->tags) : '';

        return view('admin.knowledge-base.edit', compact('knowledgeBase', 'tagsString'));
    }

    public function update(Request $request, $id)
    {
        $knowledgeBase = KnowledgeBase::findOrFail($id);

        $data = $request->validate([
            'question'    => 'required|string|max:500',
            'answer'      => 'required|string',
            'category'    => 'required|string|max:100',
            'tags_string' => 'nullable|string',
        ]);

        if ($request->filled('tags_string')) {
            $data['tags'] = array_map('trim', explode(',', $request->tags_string));
        } else {
            $data['tags'] = null;
        }

        unset($data['tags_string']);

        $knowledgeBase->update($data);

        return redirect()->route('admin.knowledge-base.index')
            ->with('success', 'Entrada de conocimiento actualizada.');
    }

    public function destroy($id)
    {
        $entry = KnowledgeBase::findOrFail($id);
        $entry->delete();

        return redirect()->route('admin.knowledge-base.index')
            ->with('success', 'Entrada eliminada correctamente.');
    }
}
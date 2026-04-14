<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\DocumentAnalysis;
use App\Models\Resource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        $request->validate(['q' => 'required|string|min:2|max:200']);
        $query = $request->q;

        $courses = Course::where('status', 'published')
            ->where(fn($q) =>
                $q->where('title', 'ilike', "%{$query}%")
                  ->orWhere('description', 'ilike', "%{$query}%")
            )
            ->with('instructor')
            ->limit(5)
            ->get()
            ->map(fn($c) => [
                'type'        => 'course',
                'id'          => $c->id,
                'title'       => $c->title,
                'description' => \Str::limit($c->description, 100),
                'url'         => route('student.courses.show', $c),
                'thumbnail'   => $c->thumbnail_url,
            ]);

        $resources = Resource::where('is_published', true)
            ->where(fn($q) =>
                $q->where('title', 'ilike', "%{$query}%")
                  ->orWhere('description', 'ilike', "%{$query}%")
            )
            ->with('course')
            ->limit(5)
            ->get()
            ->map(fn($r) => [
                'type'        => 'resource',
                'id'          => $r->id,
                'title'       => $r->title,
                'description' => \Str::limit($r->description ?? '', 100),
                'url'         => route('student.resources.show', $r),
                'icon'        => $r->type_icon,
            ]);

        $documents = DocumentAnalysis::where('status', 'completed')
            ->where(fn($q) =>
                $q->where('summary', 'ilike', "%{$query}%")
                  ->orWhereRaw("keywords::text ilike ?", ["%{$query}%"])
            )
            ->with('resource.course')
            ->limit(3)
            ->get()
            ->map(fn($a) => [
                'type'        => 'document',
                'id'          => $a->resource_id,
                'title'       => $a->resource->title,
                'description' => \Str::limit($a->summary, 100),
                'url'         => route('student.resources.show', $a->resource_id),
            ]);

        return response()->json([
            'success' => true,
            'query'   => $query,
            'results' => [
                'courses'   => $courses,
                'resources' => $resources,
                'documents' => $documents,
                'total'     => $courses->count() + $resources->count() + $documents->count(),
            ],
        ]);
    }
}

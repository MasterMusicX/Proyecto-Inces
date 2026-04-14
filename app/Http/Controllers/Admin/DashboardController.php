<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Resource;
use App\Models\User;
use App\Models\AiQuery;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'       => User::where('role', 'student')->count(),
            'total_instructors' => User::where('role', 'instructor')->count(),
            'total_courses'     => Course::count(),
            'total_resources'   => Resource::count(),
            'total_enrollments' => Enrollment::count(),
            'total_ai_queries'  => AiQuery::count(),
        ];

        $recentUsers   = User::latest()->limit(5)->get();
        $recentCourses = Course::with('instructor')->latest()->limit(5)->get();
        $popularCourses = Course::withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentCourses', 'popularCourses'));
    }
}

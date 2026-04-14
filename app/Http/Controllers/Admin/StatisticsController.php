<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiQuery;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Resource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        // Registrations per month (last 6 months)
        $registrationsPerMonth = User::where('role', 'student')
            ->where('created_at', '>=', now()->subMonths(6))
            ->selectRaw("TO_CHAR(created_at, 'YYYY-MM') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Enrollments per month
        $enrollmentsPerMonth = Enrollment::where('created_at', '>=', now()->subMonths(6))
            ->selectRaw("TO_CHAR(created_at, 'YYYY-MM') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top courses
        $topCourses = Course::withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->limit(8)
            ->get();

        // AI usage stats
        $aiStats = [
            'total_queries'    => AiQuery::count(),
            'today_queries'    => AiQuery::whereDate('created_at', today())->count(),
            'helpful_rate'     => AiQuery::whereNotNull('was_helpful')->count() > 0
                ? round(AiQuery::where('was_helpful', true)->count() / AiQuery::whereNotNull('was_helpful')->count() * 100)
                : 0,
        ];

        // Resource type distribution
        $resourceTypes = Resource::selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->get();

        // User role distribution
        $userRoles = User::selectRaw('role, COUNT(*) as total')
            ->groupBy('role')
            ->get();

        // Recent activity
        $recentEnrollments = Enrollment::with(['user', 'course'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.statistics', compact(
            'registrationsPerMonth', 'enrollmentsPerMonth',
            'topCourses', 'aiStats', 'resourceTypes',
            'userRoles', 'recentEnrollments'
        ));
    }
}

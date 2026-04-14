<?php
namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $courses = $user->coursesAsInstructor()->withCount('enrollments')->get();

        $stats = [
            'total_courses'     => $courses->count(),
            'total_students'    => $courses->sum('enrollments_count'),
            'published_courses' => $courses->where('status', 'published')->count(),
        ];

        return view('instructor.dashboard', compact('user', 'courses', 'stats'));
    }
}

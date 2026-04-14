<?php
namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $enrolledCourses = $user->enrolledCourses()
            ->withPivot('status', 'progress_percentage')
            ->with('instructor')
            ->latest('enrollments.created_at')
            ->get();

        $featuredCourses = Course::where('status', 'published')
            ->where('is_featured', true)
            ->whereDoesntHave('students', fn($q) => $q->where('users.id', $user->id))
            ->with('instructor')
            ->withCount('enrollments')
            ->limit(3)
            ->get();

        return view('student.dashboard', compact('user', 'enrolledCourses', 'featuredCourses'));
    }
}

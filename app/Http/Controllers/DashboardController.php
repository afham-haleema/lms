<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;



class DashboardController extends Controller
{
    public function index(Request $request)
    {

        $user = auth()->user();
        $provider = $user->provider ?? null;
        // assignments due in next 7 days
        $upcomingAssignments = Assignment::whereBetween('due_date', [now(), now()->addDays(7)])
            ->whereDoesntHave('submission', function ($query) {
                $query->where('student_id', Auth::id());
            })
            ->orderBy('due_date', 'asc')
            ->limit(5)
            ->get();

        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();

        $daysInMonth = [];
        for ($date = $startOfMonth; $date->lte($endOfMonth); $date->addDay()) {
            $daysInMonth[$date->format('Y-m-d')] = Assignment::whereDate('due_date', $date)->get();
        }

        // recently accessed courses (use pivot last_accessed_at)
        $recentCourses = auth()->user()
            ->courses()
            ->whereNotNull('course_user.last_accessed_at')
            ->orderByDesc('course_user.last_accessed_at')
            ->limit(6)
            ->get();


        return view('dashboard', compact('upcomingAssignments', 'daysInMonth', 'recentCourses', 'provider'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search', '');

        if ($user->role_id == 2) { // Instructor
            // Get instructor record for logged-in user
            $instructor = DB::table('instructors')->where('instructor_email', $user->email)->first();

            $courses = Course::when($instructor, function ($query, $instructor) {
                $query->where('instructor_id', $instructor->id);
            })
                ->when($search, function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                })->get();
        } else { // Student
            $courses = $user->courses()
                ->when($search, function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                })->get();
        }

        return view('courses.index', compact('courses'));
    }

    public function ajaxSearch(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search', '');

        if ($user->role_id == 2) { // Instructor
            $instructor = DB::table('instructors')->where('instructor_email', $user->email)->first();

            $courses = Course::when($instructor, function ($query, $instructor) {
                $query->where('instructor_id', $instructor->id);
            })
                ->when($search, function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                })->get();
        } else { // Student
            $courses = $user->courses()
                ->when($search, function ($query, $search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                })->get();
        }

        return view('courses.partials.course-cards', compact('courses'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $instructors = User::whereHas('role', fn($q) => $q->where('name', 'instructor'))->get();
        return view('courses.create', compact('instructors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Course::create($validated);
        return redirect()->route('courses.index')->with('success', 'Course added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $user = auth()->user();

        // Update last_accessed_at in pivot table
        $user->courses()->syncWithoutDetaching([
            $course->id => ['last_accessed_at' => now()]
        ]);

        // Redirect to modules page for that course
        return redirect()->route('courses.modules.index', $course->id);
    }




    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $instructors = User::whereHas('role', fn($q) => $q->where('name', 'instructor'))->get();
        return view('courses.edit', compact('instructors', 'course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $course->update($validated);
        return redirect()->route('courses.index')->with('success', 'Course updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Course deleted successfully');
    }
}

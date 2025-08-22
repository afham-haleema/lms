<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CourseRegistrationController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('register-course', compact('courses'));
    }

    public function store(Request $request)
    {

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->withErrors('Please login first.');
        }

        $request->validate([
            'courses' => 'required|array|min:4|max:6',
            'courses.*' => 'exists:courses,id'
        ]);

        $user->courses()->sync($request->courses);
        $user->is_registered = true;
        $user->save();
        return redirect()->route('dashboard')->with('success', 'Courses registered successfully');
    }
}

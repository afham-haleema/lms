<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Module;
use App\Models\Submissions;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $assignment = Assignment::with('module')->get();
        return view('modules.index', compact('assignment'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('assignments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:255',
            'description' => 'required',
            'file_path' => 'required|string',
            'due_date' => 'required|date'

        ]);

        Assignment::create($validated);

        return redirect()->back()->with('success', 'Assignment added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course, Module $module, Assignment $assignment)
    {
        if (auth()->user()->role_id == 2) { // instructor
        $submissions = Submissions::with(['student', 'grade'])
                ->where('assignment_id', $assignment->id)
                ->get();

        return view('assignments.instructor-show', compact('assignment', 'module', 'course', 'submissions'));
    }

        $submission = $assignment->submission()->where('student_id', auth()->user()->id)->first();
        $grade = Grade::where('student_id', auth()->id())->where('assignment_id', $assignment->id)->first();
        return view('assignments.index', compact('assignment', 'module', 'course', 'submission', 'grade'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Assignment $assignment)
    {
        return view('assignments.edit', compact('assignment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Assignment $assignment)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'module_id' => 'required|exists:modules,id',
            'title' => 'required|string|max:255',
            'description' => 'required',
            'file_path' => 'required|string',
            'due_date' => 'required|date'

        ]);

        $assignment->update($validated);
        return redirect()->back()->with('success', 'Assignment updated successfully');
    }

    public function saveGrades(Request $request, Course $course, Module $module, Assignment $assignment)
{
    foreach ($request->grades as $student_id => $gradeValue) {
        $feedback = $request->feedbacks[$student_id] ?? null;

        // Get existing grade or create a new instance
        $grade = Grade::where('student_id', $student_id)
                      ->where('assignment_id', $assignment->id)
                      ->firstOrNew([
                          'student_id' => $student_id,
                          'assignment_id' => $assignment->id,
                      ]);

        $grade->grade = $gradeValue;
        $grade->feedback = $feedback;
        $grade->save();
    }

    return back()->with('success', 'Grades updated successfully.');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Assignment $assignment)
    {
        $assignment->delete();
        return redirect()->back()->with('success', 'Resource deleted successfully');
    }
}
